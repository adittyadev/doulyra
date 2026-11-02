<?php

namespace App\Controllers;

use App\Models\MakananModel;
use App\Models\MinumanModel;

class Home extends BaseController
{
    public function index(): string
    {
        helper('doulyra');

        $makanan = (new MakananModel())->orderBy('namamakanan', 'ASC')->findAll();
        $minuman = (new MinumanModel())->orderBy('namaminuman', 'ASC')->findAll();

        // Data contoh agar tampilan terlihat saat tabel masih kosong
        $demo = false;
        if (! $makanan && ! $minuman) {
            $demo    = true;
            $makanan = [
                ['idmakanan' => 'M001', 'namamakanan' => 'Nasi Ayam Geprek',   'harga' => 18000, 'stok' => 25, 'foto' => '', 'keterangan' => 'Ayam krispi dengan sambal geprek pedas segar dan nasi hangat.'],
                ['idmakanan' => 'M002', 'namamakanan' => 'Mie Goreng Spesial', 'harga' => 15000, 'stok' => 18, 'foto' => '', 'keterangan' => 'Mie goreng bumbu khas dengan telur, sayur, dan bakso.'],
                ['idmakanan' => 'M003', 'namamakanan' => 'Burger Beef Lumer',  'harga' => 25000, 'stok' => 0,  'foto' => '', 'keterangan' => 'Daging sapi juicy, keju leleh, dan saus rahasia.'],
                ['idmakanan' => 'M004', 'namamakanan' => 'Kentang Goreng',     'harga' => 12000, 'stok' => 40, 'foto' => '', 'keterangan' => 'Renyah di luar, lembut di dalam, taburan bumbu pilihan.'],
            ];
            $minuman = [
                ['idminuman' => 'D001', 'namaminuman' => 'Taro Latte',      'harga' => 16000, 'stok' => 30, 'foto' => '', 'keterangan' => 'Perpaduan taro creamy dan susu yang manis lembut.'],
                ['idminuman' => 'D002', 'namaminuman' => 'Es Kopi Susu',    'harga' => 14000, 'stok' => 22, 'foto' => '', 'keterangan' => 'Kopi pekat, susu segar, dan gula aren.'],
                ['idminuman' => 'D003', 'namaminuman' => 'Lemon Tea Segar', 'harga' => 10000, 'stok' => 35, 'foto' => '', 'keterangan' => 'Teh dingin dengan perasan lemon asli.'],
                ['idminuman' => 'D004', 'namaminuman' => 'Thai Tea',        'harga' => 13000, 'stok' => 0,  'foto' => '', 'keterangan' => 'Teh Thailand manis gurih dengan susu kental.'],
            ];
        }

        // Item unggulan untuk banner: yang pertama dengan stok tersedia
        $pilih = static function (array $arr) {
            foreach ($arr as $a) {
                if ((int) $a['stok'] > 0) {
                    return $a;
                }
            }
            return $arr[0] ?? null;
        };

        return view('home', [
            'makanan'   => $makanan,
            'minuman'   => $minuman,
            'demo'      => $demo,
            'heroMakan' => $pilih($makanan),
            'heroMinum' => $pilih($minuman),
        ]);
    }
}
