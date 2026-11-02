<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<meta name="csrf-token" content="<?= csrf_hash() ?>">

<style>
    .chat-box {
        height: 380px;
        overflow-y: auto;
        background: #fff6e3;
        padding: 16px;
        display: flex;
        flex-direction: column;
        gap: 10px
    }

    .chat-msg {
        max-width: 78%;
        padding: 8px 14px;
        border-radius: 18px;
        font-size: .93rem;
        word-wrap: break-word;
        white-space: pre-wrap
    }

    .chat-msg small {
        display: block;
        font-size: .7rem;
        opacity: .65;
        margin-top: 2px
    }

    .chat-msg.admin {
        align-self: flex-end;
        background: #6610f2;
        color: #fff6e3;
        border-bottom-right-radius: 4px
    }

    .chat-msg.pelanggan {
        align-self: flex-start;
        background: #fff;
        border: 1px solid #ede3ff;
        border-bottom-left-radius: 4px
    }

    .chat-empty {
        margin: auto;
        color: #6f6488;
        text-align: center
    }

    .pesan-terakhir {
        max-width: 320px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap
    }
</style>

<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Pesan Masuk</h1>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap mb-0">
                    <thead>
                        <tr>
                            <th style="width:50px">No</th>
                            <th>Pelanggan</th>
                            <th>Pesan Terakhir</th>
                            <th>Waktu</th>
                            <th>Status</th>
                            <th style="width:140px">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyPesan">
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">Memuat...</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>

<!-- ================= MODAL BALAS ================= -->
<div class="modal fade" id="chatModal" tabindex="-1" role="dialog" aria-labelledby="chatModalTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-indigo">
                <h5 class="modal-title" id="chatModalTitle">Chat</h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Tutup"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="chat-box" id="chatBox"></div>
            <form class="modal-footer d-flex" id="chatForm" autocomplete="off">
                <input type="text" class="form-control flex-fill" id="chatInput" placeholder="Tulis balasan..." maxlength="500" required>
                <button type="submit" class="btn btn-primary">Kirim</button>
            </form>
        </div>
    </div>
</div>

<script>
    (function() {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const tbody = document.getElementById('tbodyPesan');
        const box = document.getElementById('chatBox');
        const input = document.getElementById('chatInput');
        let aktifId = null,
            lastId = -1,
            timerChat = null;

        const jam = s => (s || '').substring(11, 16);
        const tgl = s => s ? s.substring(8, 10) + '/' + s.substring(5, 7) + '/' + s.substring(0, 4) + ' ' + s.substring(11, 16) : '';

        // ---------- TABEL PER PELANGGAN ----------
        async function muatDaftar() {
            try {
                const r = await fetch('<?= site_url('chat/admin/daftar') ?>');
                if (!r.ok) return;
                const rows = await r.json();
                tbody.innerHTML = '';
                if (!rows.length) {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center text-muted py-4">Belum ada pesan dari pelanggan.</td></tr>';
                    return;
                }
                rows.forEach((p, i) => {
                    const belumDibalas = p.pengirim_terakhir === 'pelanggan';
                    const tr = document.createElement('tr');

                    const add = (node) => {
                        const td = document.createElement('td');
                        td.append(node);
                        tr.appendChild(td);
                        return td;
                    };
                    add(String(i + 1));

                    const nm = document.createElement('div');
                    const b = document.createElement('strong');
                    b.textContent = p.namapelanggan;
                    nm.appendChild(b);
                    if (+p.belum_dibaca > 0) {
                        const bd = document.createElement('span');
                        bd.className = 'badge badge-danger ml-2';
                        bd.textContent = p.belum_dibaca + ' baru';
                        nm.appendChild(bd);
                    }
                    add(nm);

                    const pt = document.createElement('div');
                    pt.className = 'pesan-terakhir';
                    pt.textContent = (belumDibalas ? '' : 'Anda: ') + p.pesan_terakhir;
                    add(pt);

                    add(tgl(p.waktu_terakhir));

                    const st = document.createElement('span');
                    st.className = 'badge ' + (belumDibalas ? 'badge-warning' : 'badge-success');
                    st.textContent = belumDibalas ? 'Belum dibalas' : 'Sudah dibalas';
                    add(st);

                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'btn btn-sm ' + (belumDibalas ? 'btn-primary' : 'btn-outline-secondary');
                    btn.innerHTML = belumDibalas ? '<i class="fas fa-reply"></i> Balas' : '<i class="fas fa-eye"></i> Lihat chat';
                    btn.addEventListener('click', () => bukaModal(p.idpelanggan, p.namapelanggan));
                    add(btn);

                    tbody.appendChild(tr);
                });
            } catch (e) {}
        }

        // ---------- MODAL ----------
        function render(msgs) {
            const idTerakhir = msgs.length ? msgs[msgs.length - 1].idchat : 0;
            if (idTerakhir === lastId) return;
            lastId = idTerakhir;
            box.innerHTML = '';
            if (!msgs.length) {
                box.innerHTML = '<p class="chat-empty">Belum ada pesan.</p>';
                return;
            }
            msgs.forEach(m => {
                const d = document.createElement('div');
                d.className = 'chat-msg ' + m.pengirim;
                d.textContent = m.pesan; // aman dari XSS
                const t = document.createElement('small');
                t.textContent = jam(m.created_at);
                d.appendChild(t);
                box.appendChild(d);
            });
            box.scrollTop = box.scrollHeight;
        }

        async function muatPesan() {
            if (!aktifId) return;
            try {
                const r = await fetch('<?= site_url('chat/admin') ?>/' + aktifId);
                if (r.ok) render(await r.json());
            } catch (e) {}
        }

        function bukaModal(id, nama) {
            aktifId = id;
            lastId = -1;
            document.getElementById('chatModalTitle').textContent = 'Chat dengan ' + nama;
            box.innerHTML = '';
            window.jQuery('#chatModal').modal('show');
            muatPesan().then(muatDaftar);
            clearInterval(timerChat);
            timerChat = setInterval(muatPesan, 3000);
        }

        document.getElementById('chatForm').addEventListener('submit', async e => {
            e.preventDefault();
            const teks = input.value.trim();
            if (!teks || !aktifId) return;
            input.value = '';
            const fd = new FormData();
            fd.append('pesan', teks);
            await fetch('<?= site_url('chat/admin') ?>/' + aktifId + '/kirim', {
                method: 'POST',
                body: fd,
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            await muatPesan();
            muatDaftar();
        });

        // jQuery (Bootstrap) baru siap setelah halaman termuat penuh
        window.addEventListener('load', () => {
            window.jQuery('#chatModal').on('shown.bs.modal', () => input.focus());
            window.jQuery('#chatModal').on('hidden.bs.modal', () => {
                clearInterval(timerChat);
                aktifId = null;
                muatDaftar();
            });
        });

        muatDaftar();
        setInterval(muatDaftar, 5000);
    })();
</script>
<?= $this->endSection() ?>