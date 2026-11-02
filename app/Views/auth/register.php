<?= $this->extend('auth/layout', ['title' => 'Daftar', 'subtitle' => 'Buat akun pelanggan Doulyra.']) ?>

<?= $this->section('content') ?>
<form action="<?= site_url('register') ?>" method="post">
    <?= csrf_field() ?>
    <div class="field">
        <label for="nama_lengkap">Nama lengkap</label>
        <input type="text" id="nama_lengkap" name="nama_lengkap" value="<?= old('nama_lengkap') ?>" required>
    </div>
    <div class="row">
        <div class="field">
            <label for="username">Username</label>
            <input type="text" id="username" name="username" value="<?= old('username') ?>" required>
        </div>
        <div class="field">
            <label for="no_hp">No. HP</label>
            <input type="text" id="no_hp" name="no_hp" value="<?= old('no_hp') ?>" required>
        </div>
    </div>
    <div class="field">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" value="<?= old('email') ?>" required>
    </div>
    <div class="field">
        <label for="alamat">Alamat</label>
        <textarea id="alamat" name="alamat" required><?= old('alamat') ?></textarea>
    </div>
    <div class="row">
        <div class="field">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        <div class="field">
            <label for="konfirmasi">Ulangi password</label>
            <input type="password" id="konfirmasi" name="konfirmasi" required>
        </div>
    </div>
    <button type="submit" class="btn">Daftar</button>
</form>
<p class="alt">Sudah punya akun? <a href="<?= site_url('login') ?>">Masuk</a></p>
<?= $this->endSection() ?>