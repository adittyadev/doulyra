<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Pelanggan extends BaseController
{
    protected $pelangganModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
    }

    public function index()
    {
        $data['pelanggan'] = $this->pelangganModel->findAll();

        return view('pelanggan/index', $data);
    }

    public function create()
    {
        return view('pelanggan/create');
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->pelangganModel->insert([
            'namapelanggan' => $this->request->getPost('namapelanggan'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'alamat'        => $this->request->getPost('alamat'),
            'foto'          => $this->uploadFoto(),
        ]);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['pelanggan'] = $this->pelangganModel->find($id);

        if (! $data['pelanggan']) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        return view('pelanggan/edit', $data);
    }

    public function update($id)
    {
        $pelanggan = $this->pelangganModel->find($id);

        if (! $pelanggan) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->pelangganModel->update($id, [
            'namapelanggan' => $this->request->getPost('namapelanggan'),
            'no_hp'         => $this->request->getPost('no_hp'),
            'alamat'        => $this->request->getPost('alamat'),
            'foto'          => $this->uploadFoto($pelanggan['foto']),
        ]);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil diubah');
    }

    public function delete($id)
    {
        $pelanggan = $this->pelangganModel->find($id);

        if (! $pelanggan) {
            return redirect()->to('/pelanggan')->with('error', 'Data pelanggan tidak ditemukan');
        }

        try {
            $this->pelangganModel->delete($id);
        } catch (DatabaseException $e) {
            return redirect()->to('/pelanggan')->with('error', 'Pelanggan tidak bisa dihapus karena sudah dipakai di transaksi atau akun user');
        }

        $this->hapusFoto($pelanggan['foto']);

        return redirect()->to('/pelanggan')->with('success', 'Data pelanggan berhasil dihapus');
    }

    // Simpan cepat dari modal pada form transaksi (hanya nama), response JSON
    public function storeCepat()
    {
        if (! $this->request->isAJAX()) {
            return $this->response->setStatusCode(403);
        }

        $nama = trim((string) $this->request->getPost('namapelanggan'));

        if ($nama === '' || mb_strlen($nama) > 100) {
            return $this->response->setStatusCode(422)->setJSON([
                'status'  => false,
                'message' => 'Nama pelanggan wajib diisi (maksimal 100 karakter)',
                'token'   => csrf_hash(),
            ]);
        }

        $id = $this->pelangganModel->insert(['namapelanggan' => $nama]);

        return $this->response->setJSON([
            'status' => true,
            'id'     => $id,
            'nama'   => $nama,
            'token'  => csrf_hash(),
        ]);
    }

    private function rules(): array
    {
        return [
            'namapelanggan' => 'required|max_length[100]',
            'no_hp'         => 'required|numeric|max_length[100]',
            'alamat'        => 'required|max_length[100]',
            'foto'          => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ];
    }

    // Upload foto baru; jika tidak ada file, pakai foto lama
    private function uploadFoto(?string $fotoLama = null): ?string
    {
        $file = $this->request->getFile('foto');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $fotoLama;
        }

        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/pelanggan', $nama);
        $this->hapusFoto($fotoLama);

        return $nama;
    }

    private function hapusFoto(?string $foto): void
    {
        if ($foto && is_file(FCPATH . 'uploads/pelanggan/' . $foto)) {
            unlink(FCPATH . 'uploads/pelanggan/' . $foto);
        }
    }
}
