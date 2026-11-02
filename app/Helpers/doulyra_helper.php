<?php

if (! function_exists('rupiah')) {
    function rupiah($n): string
    {
        return 'Rp ' . number_format((float) $n, 0, ',', '.');
    }
}

if (! function_exists('foto_url')) {
    /**
     * Foto disimpan di public/uploads/makanan/ dan public/uploads/minuman/
     * Kolom `foto` di database hanya berisi nama file.
     */
    function foto_url(string $jenis, ?string $file): string
    {
        if (! $file) {
            return '';
        }
        return base_url('uploads/' . $jenis . '/' . rawurlencode(basename($file)));
    }
}
