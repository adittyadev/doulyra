<?php

namespace App\Controllers;

use App\Models\MakananModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class Makanan extends BaseController
{
    protected $makananModel;

    public function __construct()
    {
        $this->makananModel = new MakananModel();
    }

    public function index()
    {
        $data['makanan'] = $this->makananModel->findAll();

        return view('makanan/index', $data);
    }

    public function create()
    {
        return view('makanan/create');
    }

    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->makananModel->insert([
            'idmakanan'   => $this->generateId(),
            'namamakanan' => $this->request->getPost('namamakanan'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'foto'        => $this->uploadFoto(),
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/makanan')->with('success', 'Data makanan berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['makanan'] = $this->makananModel->find($id);

        if (! $data['makanan']) {
            return redirect()->to('/makanan')->with('error', 'Data makanan tidak ditemukan');
        }

        return view('makanan/edit', $data);
    }

    public function update($id)
    {
        $makanan = $this->makananModel->find($id);

        if (! $makanan) {
            return redirect()->to('/makanan')->with('error', 'Data makanan tidak ditemukan');
        }

        if (! $this->validate($this->rules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $foto = $this->uploadFoto($makanan['foto']);

        $this->makananModel->update($id, [
            'namamakanan' => $this->request->getPost('namamakanan'),
            'harga'       => $this->request->getPost('harga'),
            'stok'        => $this->request->getPost('stok'),
            'foto'        => $foto,
            'keterangan'  => $this->request->getPost('keterangan'),
        ]);

        return redirect()->to('/makanan')->with('success', 'Data makanan berhasil diubah');
    }

    public function delete($id)
    {
        $makanan = $this->makananModel->find($id);

        if (! $makanan) {
            return redirect()->to('/makanan')->with('error', 'Data makanan tidak ditemukan');
        }

        try {
            $this->makananModel->delete($id);
        } catch (DatabaseException $e) {
            return redirect()->to('/makanan')->with('error', 'Makanan tidak bisa dihapus karena sudah dipakai di transaksi');
        }

        $this->hapusFoto($makanan['foto']);

        return redirect()->to('/makanan')->with('success', 'Data makanan berhasil dihapus');
    }

    private function rules(): array
    {
        return [
            'namamakanan' => 'required|max_length[100]',
            'harga'       => 'required|numeric',
            'stok'        => 'required|integer',
            'keterangan'  => 'permit_empty|max_length[255]',
            'foto'        => 'permit_empty|is_image[foto]|max_size[foto,2048]',
        ];
    }

    // Format ID: MKN001, MKN002, ...
    private function generateId(): string
    {
        $last = $this->makananModel->selectMax('idmakanan')->first()['idmakanan'] ?? null;
        $next = $last ? ((int) substr($last, 3)) + 1 : 1;

        return 'MKN' . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    // Upload foto baru; jika tidak ada file, pakai foto lama
    private function uploadFoto(?string $fotoLama = null): ?string
    {
        $file = $this->request->getFile('foto');

        if (! $file || ! $file->isValid() || $file->hasMoved()) {
            return $fotoLama;
        }

        $nama = $file->getRandomName();
        $file->move(FCPATH . 'uploads/makanan', $nama);
        $this->hapusFoto($fotoLama);

        return $nama;
    }

    private function hapusFoto(?string $foto): void
    {
        if ($foto && is_file(FCPATH . 'uploads/makanan/' . $foto)) {
            unlink(FCPATH . 'uploads/makanan/' . $foto);
        }
    }
}
