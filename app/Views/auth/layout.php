<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Doulyra') ?> | Doulyra</title>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --purple: #6610f2;
            --purple-dark: #3d0a91;
            --purple-soft: #ede3ff;
            --cream: #fff6e3;
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
            background: var(--purple);
            color: var(--ink);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 24px 16px;
            background-image: radial-gradient(circle at 15% 20%, #8a45ff 0, transparent 40%), radial-gradient(circle at 85% 90%, var(--purple-dark) 0, transparent 45%)
        }

        .card {
            width: min(440px, 100%);
            background: var(--cream);
            border-radius: 28px;
            padding: 36px 32px;
            box-shadow: 0 30px 70px rgba(20, 0, 60, .45)
        }

        .logo {
            display: block;
            text-align: center;
            font-family: 'Bricolage Grotesque', sans-serif;
            font-weight: 800;
            font-size: 2rem;
            color: var(--purple);
            text-decoration: none;
            margin-bottom: 6px
        }

        .logo i {
            font-style: normal;
            color: var(--purple-dark)
        }

        .sub {
            text-align: center;
            color: var(--muted);
            margin-bottom: 24px;
            font-size: .95rem
        }

        .field {
            margin-bottom: 14px
        }

        .field label {
            display: block;
            font-weight: 600;
            font-size: .9rem;
            margin-bottom: 6px
        }

        .field input,
        .field textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--purple-soft);
            border-radius: 14px;
            background: #fff;
            font: 500 1rem 'Figtree', sans-serif;
            color: var(--ink);
            transition: .2s
        }

        .field input:focus,
        .field textarea:focus {
            outline: 0;
            border-color: var(--purple)
        }

        .field textarea {
            resize: vertical;
            min-height: 70px
        }

        .row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .btn {
            width: 100%;
            margin-top: 6px;
            border: 0;
            border-radius: 99px;
            background: var(--purple);
            color: var(--cream);
            padding: 14px;
            font: 600 1rem 'Figtree', sans-serif;
            cursor: pointer;
            transition: .25s
        }

        .btn:hover {
            background: var(--purple-dark);
            transform: translateY(-2px)
        }

        .alert {
            padding: 12px 16px;
            border-radius: 14px;
            font-size: .9rem;
            margin-bottom: 16px
        }

        .alert--err {
            background: #ffe0e0;
            color: #7a1010
        }

        .alert--ok {
            background: #dcf7e6;
            color: #145a32
        }

        .alert ul {
            padding-left: 18px
        }

        .alt {
            text-align: center;
            margin-top: 20px;
            font-size: .92rem;
            color: var(--muted)
        }

        .alt a {
            color: var(--purple);
            font-weight: 600;
            text-decoration: none
        }

        @media(max-width:480px) {
            .row {
                grid-template-columns: 1fr
            }

            .card {
                padding: 28px 22px
            }
        }
    </style>
</head>

<body>
    <div class="card">
        <a href="<?= site_url('/') ?>" class="logo">Doulyra<i>.</i></a>
        <p class="sub"><?= esc($subtitle ?? '') ?></p>

        <?php if (session('success')): ?>
            <div class="alert alert--ok"><?= esc(session('success')) ?></div>
        <?php endif; ?>
        <?php if (session('error')): ?>
            <div class="alert alert--err"><?= esc(session('error')) ?></div>
        <?php endif; ?>
        <?php if (session('errors')): ?>
            <div class="alert alert--err">
                <ul><?php foreach (session('errors') as $e): ?><li><?= esc($e) ?></li><?php endforeach; ?></ul>
            </div>
        <?php endif; ?>

        <?= $this->renderSection('content') ?>
    </div>
</body>

</html>