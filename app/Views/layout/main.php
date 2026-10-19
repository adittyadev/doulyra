<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Petshop Bigboss <?= $title ?? '' ?></title>

    <link rel="shortcut icon" href="<?= base_url('favicon.ico?v=<?= time() ?>') ?>" type="image/x-icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/datatables-buttons/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/select2/css/select2.min.css">
    <link rel="stylesheet" href="<?php base_url() ?>/assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

    <style>
        .nav-sidebar .nav-link.active,
        .nav-sidebar .nav-link.active:hover {
            background-color: #FFC107 !important;
            color: #000 !important;
            box-shadow: none !important;
        }

        .nav-sidebar .nav-link.active .nav-icon {
            color: #000 !important;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed">
    <div class="wrapper">
        <?= base_url() ?>


        <?= $this->include('layout/navbar') ?>

        <?= $this->include('layout/sidebar') ?>

        <div class="content-wrapper">
            <section class="content-header">

            </section>

            <section class="content">
                <?= $this->renderSection('content') ?>
            </section>
        </div>

        <?= $this->include('layout/footer') ?>
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
    </div>

    <script src="<?php base_url() ?>/assets/plugins/jquery/jquery.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php base_url() ?>/assets/dist/js/adminlte.min.js"></script>
    <?php base_url() ?>
    <script src="<?php base_url() ?>/assets/plugins/datatables/jquery.dataTables.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-buttons/js/dataTables.buttons.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/jszip/jszip.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/pdfmake/pdfmake.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/pdfmake/vfs_fonts.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-buttons/js/buttons.html5.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-buttons/js/buttons.print.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/datatables-buttons/js/buttons.colVis.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/toastr/toastr.min.js"></script>
    <script src="<?php base_url() ?>/assets/plugins/sweetalert2/sweetalert2.min.js"></script>

    <script src="<?php base_url() ?>/assets/plugins/select2/js/select2.full.min.js"></script>

    <?= $this->renderSection('script') ?>

    <script>
        <?= base_url() ?>

        $(document).on('click', '.btnLogout', function(e) {
            e.preventDefault();

            Swal.fire({
                title: "Apakah anda yakin ingin keluar?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Ya",
                cancelButtonText: "Tidak"
            }).then((result) => {
                if (result.isConfirmed) {
                    setTimeout(function() {
                        window.location.href = '<?= site_url('/logout') ?>';
                    }, 1000);
                }
            });
        });

        $('.select2').select2()

        $('.select2bs4').select2({
            theme: 'bootstrap4'
        })
    </script>

    <script>
        function muatBadgeNotifikasi() {
            fetch('<?= base_url('notifikasi/jumlah-baru') ?>', {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    const badgeTransaksi = document.getElementById('badge-transaksi');
                    const badgeGrooming = document.getElementById('badge-grooming');

                    if (badgeTransaksi) {
                        if (data.transaksi > 0) {
                            badgeTransaksi.textContent = data.transaksi;
                            badgeTransaksi.style.display = 'inline-block';
                        } else {
                            badgeTransaksi.style.display = 'none';
                        }
                    }

                    if (badgeGrooming) {
                        if (data.grooming > 0) {
                            badgeGrooming.textContent = data.grooming;
                            badgeGrooming.style.display = 'inline-block';
                        } else {
                            badgeGrooming.style.display = 'none';
                        }
                    }
                })
                .catch(err => console.error('Gagal muat notifikasi:', err));
        }

        muatBadgeNotifikasi();
        setInterval(muatBadgeNotifikasi, 15000);
    </script>
</body>

</html>