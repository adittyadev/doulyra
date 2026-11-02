<?php

namespace App\Controllers;

use App\Models\ChatModel;

class Chat extends BaseController
{
    private function pelangganId()
    {
        $s = session();
        if (!$s->get('logged_in') || $s->get('role') !== 'pelanggan') return null;
        return (int) $s->get('pelanggan_id');
    }

    private function isAdmin(): bool
    {
        return session()->get('logged_in') && session()->get('role') === 'karyawan';
    }

    // ===== PELANGGAN =====
    public function ambil()
    {
        $id = $this->pelangganId();
        if (!$id) return $this->response->setStatusCode(401)->setJSON(['error' => 'Silakan login']);

        $m = new ChatModel();
        $pesan = $m->where('pelanggan_id', $id)->orderBy('idchat', 'ASC')->findAll(200);
        $unread = $m->where(['pelanggan_id' => $id, 'pengirim' => 'admin', 'dibaca' => 0])->countAllResults();

        // kalau modal sedang dibuka, tandai balasan admin sebagai dibaca
        if ($this->request->getGet('buka') === '1' && $unread > 0) {
            $m->where(['pelanggan_id' => $id, 'pengirim' => 'admin', 'dibaca' => 0])->set('dibaca', 1)->update();
            $unread = 0;
        }
        return $this->response->setJSON(['pesan' => $pesan, 'unread' => $unread]);
    }

    public function kirim()
    {
        $id = $this->pelangganId();
        if (!$id) return $this->response->setStatusCode(401)->setJSON(['error' => 'Silakan login']);

        $teks = trim((string) $this->request->getPost('pesan'));
        if ($teks === '' || mb_strlen($teks) > 500) {
            return $this->response->setStatusCode(422)->setJSON(['error' => 'Pesan kosong atau lebih dari 500 karakter']);
        }
        (new ChatModel())->insert(['pelanggan_id' => $id, 'pengirim' => 'pelanggan', 'pesan' => $teks, 'dibaca' => 0]);
        return $this->response->setJSON(['ok' => true]);
    }

    public function adminDaftar()
    {
        if (!$this->isAdmin()) return $this->response->setStatusCode(403)->setJSON(['error' => 'Dilarang']);

        $rows = db_connect()->query("
        SELECT p.idpelanggan, p.namapelanggan,
               c.pesan      AS pesan_terakhir,
               c.created_at AS waktu_terakhir,
               c.pengirim   AS pengirim_terakhir,
               (SELECT COUNT(*) FROM chat x
                 WHERE x.pelanggan_id = p.idpelanggan AND x.pengirim = 'pelanggan' AND x.dibaca = 0) AS belum_dibaca
        FROM pelanggan p
        JOIN chat c ON c.idchat = (SELECT MAX(idchat) FROM chat WHERE pelanggan_id = p.idpelanggan)
        ORDER BY c.idchat DESC")->getResultArray();

        return $this->response->setJSON($rows);
    }

    public function adminAmbil($pelangganId)
    {
        if (!$this->isAdmin()) return $this->response->setStatusCode(403)->setJSON(['error' => 'Dilarang']);

        $m = new ChatModel();
        $m->where(['pelanggan_id' => $pelangganId, 'pengirim' => 'pelanggan', 'dibaca' => 0])->set('dibaca', 1)->update();
        return $this->response->setJSON($m->where('pelanggan_id', $pelangganId)->orderBy('idchat', 'ASC')->findAll(200));
    }

    public function adminKirim($pelangganId)
    {
        if (!$this->isAdmin()) return $this->response->setStatusCode(403)->setJSON(['error' => 'Dilarang']);

        $teks = trim((string) $this->request->getPost('pesan'));
        if ($teks === '' || mb_strlen($teks) > 500) return $this->response->setStatusCode(422)->setJSON(['error' => 'Pesan tidak valid']);

        (new ChatModel())->insert(['pelanggan_id' => $pelangganId, 'pengirim' => 'admin', 'pesan' => $teks, 'dibaca' => 0]);
        return $this->response->setJSON(['ok' => true]);
    }

    public function adminHalaman()
    {
        if (!$this->isAdmin()) return redirect()->to(site_url('login'));
        return view('chat/pesan_masuk');
    }
}
