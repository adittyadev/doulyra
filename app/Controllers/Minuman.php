<?php

namespace App\Controllers;

use App\Models\MinumanModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Minuman extends BaseController
{
    protected $minumanModel;

    public function __construct()
    {
        $this->minumanModel = new MinumanModel();
    }

    public function index()
    {
        $data['minuman'] = $this->minumanModel->findAll();

        return view('minuman/index', $data);
    }

    public function create()
    {
        return view('minuman/create');
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->minumanModel->insert([
            'idminuman'   => $this->generateId(),
            'namaminuman' => $this->request->getPost('namaminuman'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'foto'        => $this->uploadFoto(),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/minuman')->with('success', 'Data minuman berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['minuman'] = $this->minumanModel->find($id);

        if (! $data['minuman']) {
            return redirect()->to('/minuman')->with('error', 'Data minuman tidak ditemukan');
        }

        return view('minuman/edit', $data);
    }

    public function update($id)
    {
        $minuman = $this->minumanModel->find($id);

        if (! $minuman) {
            return redirect()->to('/minuman')->with('error', 'Data minuman tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->uploadFoto($minuman['foto']);

        $this->minumanModel->update($id, [
            'namaminuman' => $this->request->getPost('namaminuman'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'foto'        => $foto,
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/minuman')->with('success', 'Data minuman berhasil diubah');
    }

    public function delete($id)
    {
        $minuman = $this->minumanModel->find($id);

        if (! $minuman) {
            return redirect()->to('/minuman')->with('error', 'Data minuman tidak ditemukan');
        }

        try {
            $this->minumanModel->delete($id);
        } catch (DatabaseException $e) {
            return redirect()->to('/minuman')->with('error', 'Minuman tidak bisa dihapus karena sudah dipakai di transaksi');
        }

        $this->hapusFoto($minuman['foto']);

        return redirect()->to('/minuman')->with('success', 'Data minuman berhasil dihapus');
    }

    private function rules(): array
    {
        return [
            'namaminuman' => 'required|max_length[100]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'keterangan'  => 'permit_empty|max_length[255]',
            'foto'        => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ];
    }

    // Format ID: MNM001, MNM002, ...
    private function generateId(): string
    {
        $last = $this->minumanModel->selectMax('idminuman')->first()['idminuman'] ?? null;
        $next = $last ? ((int) substr($last, 3)) + 1 : 1;

        return 'MNM' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    // Upload foto baru; jika tidak ada file, pakai foto lama
    private function uploadFoto(?string $fotoLama = null): ?string
    {
        $file = $this->request->getFile('foto');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $fotoLama;
        }

        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/minuman', $nama);
        $this->hapusFoto($fotoLama);

        return $nama;
    }

    private function hapusFoto(?string $foto): void
    {
        if ($foto && is_file(FCPATH . 'uploads/minuman/' . $foto)) {
            unlink(FCPATH . 'uploads/minuman/' . $foto);
        }
    }
}
