<?php

namespace App\Controllers;

use App\Models\AlamatPelangganModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Alamat extends BaseController
{
    public function index()
    {
        return view('alamat/index', [
            'alamat' => model(AlamatPelangganModel::class)->getByUser((int) session()->get('id_user')),
        ]);
    }

    public function simpan()
    {
        $rules = [
            'nama_penerima'  => 'required|max_length[100]',
            'no_hp'          => 'required|numeric|max_length[20]',
            'alamat_lengkap' => 'required',
            'kecamatan'      => 'permit_empty|max_length[100]',
            'kota'           => 'required|max_length[100]',
            'kode_pos'       => 'permit_empty|numeric|max_length[10]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $idUser = (int) session()->get('id_user');
        $model  = model(AlamatPelangganModel::class);
        $pertama = $model->where('id_user', $idUser)->countAllResults() === 0;

        $model->insert([
            'id_user'        => $idUser,
            'nama_penerima'  => $this->request->getPost('nama_penerima'),
            'no_hp'          => $this->request->getPost('no_hp'),
            'alamat_lengkap' => $this->request->getPost('alamat_lengkap'),
            'kecamatan'      => $this->request->getPost('kecamatan') ?: null,
            'kota'           => $this->request->getPost('kota'),
            'kode_pos'       => $this->request->getPost('kode_pos') ?: null,
            'is_utama'       => $pertama ? 1 : 0,
        ]);

        return redirect()->to('/alamat')->with('success', 'Alamat disimpan.');
    }

    public function utama(int $id)
    {
        $this->milikUser($id);
        model(AlamatPelangganModel::class)->setUtama((int) session()->get('id_user'), $id);

        return redirect()->to('/alamat');
    }

    public function hapus(int $id)
    {
        $this->milikUser($id);
        model(AlamatPelangganModel::class)->delete($id);

        return redirect()->to('/alamat')->with('success', 'Alamat dihapus.');
    }

    private function milikUser(int $id): array
    {
        $alamat = model(AlamatPelangganModel::class)
            ->where(['id_alamat' => $id, 'id_user' => (int) session()->get('id_user')])
            ->first();

        if (! $alamat) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $alamat;
    }
}
