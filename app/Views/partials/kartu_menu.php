<?php

/** @var array $item @var string $jenis ('makanan'|'minuman') */
helper('doulyra');
$nama  = $jenis === 'makanan' ? $item['namamakanan'] : $item['namaminuman'];
$id    = $jenis === 'makanan' ? $item['idmakanan']   : $item['idminuman'];
$foto  = foto_url($jenis, $item['foto']);
$habis = (int) $item['stok'] <= 0;
$emoji = $jenis === 'makanan' ? '🍽️' : '🥤';
?>
<article class="card <?= $habis ? 'is-out' : '' ?>">
    <div class="card__img">
        <?php if ($foto): ?>
            <img src="<?= esc($foto) ?>" alt="<?= esc($nama) ?>" loading="lazy"
                onerror="this.replaceWith(Object.assign(document.createElement('span'),{className:'card__emoji',textContent:'<?= $emoji ?>'}))">
        <?php else: ?>
            <span class="card__emoji"><?= $emoji ?></span>
        <?php endif; ?>
        <span class="badge <?= $habis ? 'badge--out' : 'badge--ok' ?>">
            <?= $habis ? 'Stok habis' : 'Stok ' . (int) $item['stok'] ?>
        </span>
    </div>
    <div class="card__body">
        <h3><?= esc($nama) ?></h3>
        <p><?= esc($item['keterangan'] ?: 'Dibuat segar setiap hari untuk Anda.') ?></p>
        <div class="card__foot">
            <strong><?= rupiah($item['harga']) ?></strong>
            <?php if ($habis): ?>
                <span class="btn btn--disabled">Habis</span>
            <?php else: ?>
                <a class="btn btn--buy" href="<?= site_url('pesan/' . $jenis . '/' . urlencode($id)) ?>">Pesan</a>
            <?php endif; ?>
        </div>
    </div>
</article>