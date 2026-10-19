<nav class="main-header navbar navbar-expand navbar-dark bg-warning">
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link text-white" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
    </ul>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <span class="nav-link text-white d-flex align-items-center">
                <img src="<?= base_url('assets/img/logo.jpg') ?>" class="img-circle elevation-2 mr-2"
                    alt="User" width="28" height="28" style="object-fit:cover;">
                <span class="d-none d-md-inline">
                    Hallo, <?= esc(session()->get('nama') ?? 'Guest') ?>
                </span>
            </span>
        </li>
    </ul>
</nav>