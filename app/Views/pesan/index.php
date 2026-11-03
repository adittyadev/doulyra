<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pesanan Anda | Doulyra</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple: #6610f2;
            --purple-dark: #3d0a91;
            --purple-soft: #ede3ff;
            --cream: #fff6e3;
            --cream-deep: #ffe9bd;
            --ink: #2a1650;
            --muted: #6f6488
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        body {
            font-family: 'Figtree', sans-serif;
            background: var(--cream);
            color: var(--ink);
            min-height: 100vh;
            padding: 32px 16px
        }

        .wrap {
            max-width: 720px;
            margin: 0 auto
        }

        .back {
            display: inline-block;
            margin-bottom: 18px;
            color: var(--purple);
            font-weight: 600;
            text-decoration: none
        }

        .box {
            background: #fff;
            border-radius: 28px;
            padding: 32px;
            box-shadow: 0 20px 50px rgba(102, 16, 242, .15);
            display: flex;
            flex-direction: column;
            gap: 22px
        }

        h1 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.8rem
        }

        .t {
            display: block;
            font-weight: 600;
            margin-bottom: 10px
        }

        .item {
            display: grid;
            grid-template-columns: 72px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--purple-soft)
        }

        .thumb {
            width: 72px;
            height: 72px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--cream-deep), var(--purple-soft));
            display: grid;
            place-items: center;
            overflow: hidden;
            font-size: 2rem
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .info b {
            display: block;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.05rem;
            line-height: 1.25
        }

        .info small {
            color: var(--muted)
        }

        .sub {
            font-weight: 600;
            color: var(--purple);
            text-align: right;
            margin-bottom: 6px
        }

        .qty {
            display: flex;
            align-items: center;
            gap: 4px;
            justify-content: flex-end
        }

        .qty button {
            width: 34px;
            height: 34px;
            border: 0;
            border-radius: 10px;
            background: var(--purple-soft);
            color: var(--purple);
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer
        }

        .qty button.del {
            background: #ffe0e0;
            color: #7a1010;
            font-size: .95rem
        }

        .qty span {
            min-width: 30px;
            text-align: center;
            font-weight: 600
        }

        .kosong {
            text-align: center;
            color: var(--muted);
            padding: 24px 0
        }

        .btn {
            border: 0;
            border-radius: 99px;
            background: var(--purple);
            color: var(--cream);
            padding: 14px;
            font: 600 1rem 'Figtree';
            cursor: pointer;
            transition: .25s;
            width: 100%
        }

        .btn:hover {
            background: var(--purple-dark)
        }

        .btn:disabled {
            background: #d9d2e8;
            color: #8b82a0;
            cursor: not-allowed
        }

        .btn--ghost {
            background: transparent;
            color: var(--purple);
            border: 2px dashed var(--purple)
        }

        .btn--ghost:hover {
            background: var(--purple-soft)
        }

        .bayar {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px
        }

        .bayar input {
            display: none
        }

        .bayar span {
            display: block;
            text-align: center;
            padding: 12px;
            border: 2px solid var(--purple-soft);
            border-radius: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: .2s
        }

        .bayar input:checked+span {
            background: var(--purple);
            border-color: var(--purple);
            color: var(--cream)
        }

        .qris-info {
            display: none;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 14px;
            background: var(--purple-soft);
            font-size: .92rem
        }

        .qris-info button {
            border: 0;
            background: none;
            color: var(--purple);
            font-weight: 600;
            cursor: pointer;
            white-space: nowrap
        }

        .total {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 16px;
            border-top: 2px dashed var(--purple-soft)
        }

        .total strong {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.6rem;
            color: var(--purple)
        }

        /* modal */
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(36, 6, 87, .55);
            display: none;
            align-items: center;
            justify-content: center;
            padding: 16px;
            z-index: 50
        }

        .modal.open {
            display: flex
        }

        .dialog {
            background: var(--cream);
            border-radius: 26px;
            width: min(780px, 100%);
            max-height: 88vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 30px 70px rgba(20, 0, 60, .5)
        }

        .dialog--sm {
            width: min(420px, 100%)
        }

        .dialog__head {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px 12px
        }

        .dialog__head h2 {
            font-family: 'Bricolage Grotesque', sans-serif;
            font-size: 1.4rem
        }

        .x {
            border: 0;
            background: var(--purple-soft);
            color: var(--purple);
            width: 38px;
            height: 38px;
            border-radius: 50%;
            font-size: 1.2rem;
            cursor: pointer
        }

        .tabs {
            display: flex;
            gap: 6px;
            background: var(--purple-soft);
            padding: 5px;
            border-radius: 99px;
            width: fit-content;
            margin: 0 24px 14px
        }

        .tab {
            border: 0;
            background: none;
            padding: 9px 24px;
            border-radius: 99px;
            font: 600 .95rem 'Figtree';
            color: var(--purple);
            cursor: pointer
        }

        .tab.active {
            background: var(--purple);
            color: var(--cream)
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
            gap: 16px;
            padding: 4px 24px 24px;
            overflow-y: auto
        }

        .m {
            background: #fff;
            border-radius: 18px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            box-shadow: 0 8px 20px rgba(102, 16, 242, .1)
        }

        .m .thumb {
            width: 100%;
            height: 110px;
            border-radius: 0;
            font-size: 2.6rem
        }

        .m__b {
            padding: 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            flex: 1
        }

        .m__b b {
            font-size: .95rem;
            line-height: 1.3
        }

        .m__b small {
            color: var(--muted)
        }

        .m__b strong {
            color: var(--purple)
        }

        .m__b .btn {
            padding: 9px;
            font-size: .88rem;
            margin-top: auto
        }

        /* isi modal QRIS */
        .qris-body {
            padding: 0 24px 24px;
            display: flex;
            flex-direction: column;
            gap: 14px;
            text-align: center;
            overflow-y: auto
        }

        .qris-body img {
            width: 100%;
            max-width: 280px;
            margin: 0 auto;
            border-radius: 16px;
            background: #fff;
            padding: 10px
        }

        .qris-body input[type=file] {
            width: 100%;
            background: #fff;
            padding: 10px;
            border-radius: 12px
        }

        @media(max-width:600px) {
            .box {
                padding: 22px
            }

            .item {
                grid-template-columns: 60px 1fr
            }

            .thumb {
                width: 60px;
                height: 60px
            }

            .item .aksi {
                grid-column: 1/-1
            }

            .sub,
            .qty {
                justify-content: space-between;
                text-align: left
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <a href="<?= site_url('/#menu') ?>" class="back">&larr; Kembali ke menu</a>

        <form class="box" id="formPesan" action="<?= site_url('pesan/simpan') ?>" method="post" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <h1>Pesanan Anda</h1>
            <?php if (session('error')): ?>
                <div style="padding:12px 16px;border-radius:14px;background:#ffe0e0;color:#7a1010;font-size:.92rem"><?= esc(session('error')) ?></div>
            <?php endif; ?>

            <div>
                <div id="daftar"></div>
                <button type="button" class="btn btn--ghost" id="tambah" style="margin-top:14px">+ Tambah pesanan</button>
            </div>

            <div>
                <label class="t">Metode pembayaran</label>
                <div class="bayar">
                    <label><input type="radio" name="metode" value="cash" checked><span>Cash</span></label>
                    <label><input type="radio" name="metode" value="qris"><span>QRIS</span></label>
                </div>
            </div>

            <div class="qris-info" id="infoQris">
                <span id="statusBukti">Bukti pembayaran belum diunggah</span>
                <button type="button" id="bukaQris">Lihat QRIS</button>
            </div>

            <div class="total">
                <span>Total harga</span>
                <strong id="total">Rp 0</strong>
            </div>

            <div id="fields"></div>
            <button type="submit" class="btn" id="kirim">Buat pesanan</button>
        </form>
    </div>

    <!-- Modal pilih menu -->
    <div class="modal" id="modal">
        <div class="dialog" role="dialog" aria-modal="true" aria-label="Tambah pesanan">
            <div class="dialog__head">
                <h2>Tambah pesanan</h2>
                <button type="button" class="x" id="tutup" aria-label="Tutup">&times;</button>
            </div>
            <div class="tabs">
                <button type="button" class="tab active" data-tab="makanan">Makanan</button>
                <button type="button" class="tab" data-tab="minuman">Minuman</button>
            </div>
            <div class="grid" id="gridMenu"></div>
        </div>
    </div>

    <!-- Modal QRIS: foto QRIS + upload bukti pembayaran -->
    <div class="modal" id="modalQris">
        <div class="dialog dialog--sm" role="dialog" aria-modal="true" aria-label="Pembayaran QRIS">
            <div class="dialog__head">
                <h2>Pembayaran QRIS</h2>
                <button type="button" class="x" id="tutupQris" aria-label="Tutup">&times;</button>
            </div>
            <div class="qris-body">
                <div>Total bayar <strong id="totalQris" style="color:var(--purple);font-size:1.3rem">Rp 0</strong></div>
                <?php if ($qrisStatis) : ?>
                    <div id="qrisKode" style="display:flex;justify-content:center;background:#fff;border-radius:16px;padding:12px;width:fit-content;margin:0 auto"></div>
                <?php else : ?>
                    <img src="<?= base_url('uploads/qris.jpeg') ?>" alt="QRIS Doulyra">
                    <small style="color:#b45309">QRIS statis belum terbaca dari .env, nominal harus diketik manual.</small>
                <?php endif; ?>
                <div style="text-align:left">
                    <label class="t">Unggah bukti pembayaran</label>
                    <input type="file" id="fileBukti" name="bukti" form="formPesan" accept="image/*">
                </div>
                <button type="button" class="btn" id="simpanBukti">Selesai</button>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const menu = <?= json_encode($menu, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) ?>;
        const keranjang = {};
        keranjang[<?= json_encode($awal) ?>] = 1;

        const kunci = m => m.jenis + '|' + m.id;
        const byKey = {};
        menu.forEach(m => byKey[kunci(m)] = m);

        const rp = n => 'Rp ' + n.toLocaleString('id-ID');
        const h = s => String(s).replace(/[&<>"']/g, c => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#39;'
        } [c]));
        const thumb = m => `<div class="thumb">${m.foto ? `<img src="${h(m.foto)}" alt="">` : (m.jenis === 'makanan' ? '🍽️' : '🥤')}</div>`;

        const daftar = document.getElementById('daftar');
        const fields = document.getElementById('fields');
        const total = document.getElementById('total');
        const kirim = document.getElementById('kirim');
        const modal = document.getElementById('modal');
        const gridMenu = document.getElementById('gridMenu');
        const form = document.getElementById('formPesan');
        const modalQris = document.getElementById('modalQris');
        const fileBukti = document.getElementById('fileBukti');
        const infoQris = document.getElementById('infoQris');
        const statusBukti = document.getElementById('statusBukti');
        let tab = 'makanan';

        // ===== QRIS dinamis: nominal otomatis terisi =====
        const QRIS_STATIS = <?= json_encode($qrisStatis) ?>;
        let qrisObj = null;

        function crc16(s) {
            let c = 0xFFFF;
            for (let i = 0; i < s.length; i++) {
                c ^= s.charCodeAt(i) << 8;
                for (let j = 0; j < 8; j++) c = (c & 0x8000) ? ((c << 1) ^ 0x1021) : (c << 1);
                c &= 0xFFFF;
            }
            return c.toString(16).toUpperCase().padStart(4, '0');
        }

        // Ubah QRIS statis menjadi dinamis dengan nominal tertentu
        function qrisDinamis(statis, nominal) {
            // Pecah string menjadi pasangan [id, nilai] (format TLV)
            const tlv = [];
            for (let i = 0; i < statis.length;) {
                const len = parseInt(statis.substr(i + 2, 2), 10);
                tlv.push([statis.substr(i, 2), statis.substr(i + 4, len)]);
                i += 4 + len;
            }

            const data = tlv
                .filter(([id]) => id !== '54' && id !== '63') // buang nominal & CRC lama
                .map(([id, v]) => id === '01' ? [id, '12'] : [id, v]); // 11 = statis, 12 = dinamis
            data.push(['54', String(Math.round(nominal))]);
            data.sort((a, b) => a[0].localeCompare(b[0]));

            const p = data.map(([id, v]) => id + String(v.length).padStart(2, '0') + v).join('') + '6304';
            return p + crc16(p);
        }

        function buatQris(nominal) {
            const box = document.getElementById('qrisKode');
            if (!QRIS_STATIS || !box || nominal <= 0) return;

            const teks = qrisDinamis(QRIS_STATIS, nominal);
            if (qrisObj) {
                qrisObj.clear();
                qrisObj.makeCode(teks);
            } else {
                qrisObj = new QRCode(box, {
                    text: teks,
                    width: 260,
                    height: 260,
                    correctLevel: QRCode.CorrectLevel.M
                });
            }
        }

        function render() {
            const keys = Object.keys(keranjang);

            daftar.innerHTML = keys.length ? keys.map(k => {
                const m = byKey[k],
                    n = keranjang[k];
                return `<div class="item">
                ${thumb(m)}
                <div class="info"><b>${h(m.nama)}</b><small>${rp(m.harga)} &middot; stok ${m.stok}</small></div>
                <div class="aksi">
                    <div class="sub">${rp(m.harga * n)}</div>
                    <div class="qty">
                        <button type="button" data-k="${h(k)}" data-a="-1">&minus;</button>
                        <span>${n}</span>
                        <button type="button" data-k="${h(k)}" data-a="1">+</button>
                        <button type="button" class="del" data-k="${h(k)}" data-a="del">&#128465;</button>
                    </div>
                </div>
            </div>`;
            }).join('') : '<p class="kosong">Belum ada pesanan. Klik "Tambah pesanan" untuk memilih menu.</p>';

            fields.innerHTML = keys.map((k, i) => `
            <input type="hidden" name="items[${i}][jenis]" value="${h(byKey[k].jenis)}">
            <input type="hidden" name="items[${i}][id]" value="${h(byKey[k].id)}">
            <input type="hidden" name="items[${i}][jumlah]" value="${keranjang[k]}">`).join('');

            const nominal = keys.reduce((s, k) => s + byKey[k].harga * keranjang[k], 0);
            const jumlah = rp(nominal);
            total.textContent = jumlah;
            document.getElementById('totalQris').textContent = jumlah;
            buatQris(nominal);
            kirim.disabled = !keys.length;
            renderModal();
        }

        function renderModal() {
            gridMenu.innerHTML = menu.filter(m => m.jenis === tab).map(m => {
                const k = kunci(m),
                    n = keranjang[k] || 0,
                    penuh = n >= m.stok;
                return `<div class="m">
                ${thumb(m)}
                <div class="m__b">
                    <b>${h(m.nama)}</b>
                    <small>Stok ${m.stok}${n ? ' &middot; di pesanan: ' + n : ''}</small>
                    <strong>${rp(m.harga)}</strong>
                    <button type="button" class="btn" data-add="${h(k)}" ${penuh ? 'disabled' : ''}>${penuh ? 'Stok maksimal' : (n ? '+ Tambah lagi' : '+ Tambah')}</button>
                </div>
            </div>`;
            }).join('') || '<p class="kosong">Menu belum tersedia.</p>';
        }

        daftar.addEventListener('click', e => {
            const b = e.target.closest('button[data-k]');
            if (!b) return;
            const k = b.dataset.k;
            if (b.dataset.a === 'del') {
                delete keranjang[k];
            } else {
                const n = keranjang[k] + parseInt(b.dataset.a);
                if (n >= 1 && n <= byKey[k].stok) keranjang[k] = n;
            }
            render();
        });

        gridMenu.addEventListener('click', e => {
            const b = e.target.closest('button[data-add]');
            if (!b) return;
            const k = b.dataset.add;
            keranjang[k] = Math.min((keranjang[k] || 0) + 1, byKey[k].stok);
            render();
        });

        document.querySelectorAll('.tab').forEach(t => t.addEventListener('click', () => {
            document.querySelectorAll('.tab').forEach(x => x.classList.remove('active'));
            t.classList.add('active');
            tab = t.dataset.tab;
            renderModal();
        }));

        // Modal pilih menu
        document.getElementById('tambah').onclick = () => modal.classList.add('open');
        document.getElementById('tutup').onclick = () => modal.classList.remove('open');
        modal.addEventListener('click', e => {
            if (e.target === modal) modal.classList.remove('open');
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') document.querySelectorAll('.modal').forEach(m => m.classList.remove('open'));
        });

        // Modal QRIS
        function updateQris() {
            infoQris.style.display = form.metode.value === 'qris' ? 'flex' : 'none';
            statusBukti.textContent = fileBukti.files.length ? '✓ ' + fileBukti.files[0].name : 'Bukti pembayaran belum diunggah';
        }

        document.querySelectorAll('[name=metode]').forEach(r => r.addEventListener('change', () => {
            if (r.checked && r.value === 'qris') modalQris.classList.add('open');
            if (r.checked && r.value === 'cash') fileBukti.value = '';
            updateQris();
        }));

        document.getElementById('bukaQris').onclick = () => modalQris.classList.add('open');
        document.getElementById('tutupQris').onclick = document.getElementById('simpanBukti').onclick = () => modalQris.classList.remove('open');
        modalQris.addEventListener('click', e => {
            if (e.target === modalQris) modalQris.classList.remove('open');
        });
        fileBukti.addEventListener('change', updateQris);

        // QRIS wajib ada bukti sebelum pesanan dikirim
        form.addEventListener('submit', e => {
            if (form.metode.value === 'qris' && !fileBukti.files.length) {
                e.preventDefault();
                modalQris.classList.add('open');
            }
        });

        render();
        updateQris();
    </script>
</body>

</html>