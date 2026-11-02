<?= $this->extend('auth/layout', ['title' => 'Masuk', 'subtitle' => 'Masuk untuk mulai memesan.']) ?>

<?= $this->section('content') ?>
<form action="<?= site_url('login') ?>" method="post">
    <?= csrf_field() ?>
    <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" value="<?= old('username') ?>" autofocus required>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn">Masuk</button>
</form>
<p class="alt">Belum punya akun? <a href="<?= site_url('register') ?>">Daftar sekarang</a></p>
<?= $this->endSection() ?>