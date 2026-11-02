<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= esc($title ?? 'Pesanan') ?> | Doulyra</title>
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
            font-size: 1.8rem;
            line-height: 1.2
        }

        .muted {
            color: var(--muted);
            font-size: .92rem
        }

        .alert {
            padding: 12px 16px;
            border-radius: 14px;
            background: #ffe0e0;
            color: #7a1010;
            font-size: .92rem
        }

        .btn {
            display: block;
            text-align: center;
            border: 0;
            border-radius: 99px;
            background: var(--purple);
            color: var(--cream);
            padding: 14px;
            font: 600 1rem 'Figtree';
            cursor: pointer;
            text-decoration: none;
            width: 100%;
            transition: .25s
        }

        .btn:hover {
            background: var(--purple-dark)
        }

        .btn--ghost {
            background: transparent;
            color: var(--purple);
            border: 2px solid var(--purple)
        }

        .btn--ghost:hover {
            background: var(--purple-soft)
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

        .item {
            display: grid;
            grid-template-columns: 64px 1fr auto;
            gap: 14px;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid var(--purple-soft)
        }

        .thumb {
            width: 64px;
            height: 64px;
            border-radius: 14px;
            background: linear-gradient(135deg, var(--cream-deep), var(--purple-soft));
            display: grid;
            place-items: center;
            overflow: hidden;
            font-size: 1.8rem
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .item b {
            display: block;
            font-family: 'Bricolage Grotesque', sans-serif
        }

        .item strong {
            color: var(--purple)
        }

        .stepper {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 8px
        }

        .step {
            text-align: center;
            padding: 14px 8px;
            border-radius: 16px;
            background: var(--purple-soft);
            color: var(--muted);
            font-weight: 600;
            font-size: .9rem;
            transition: .4s
        }

        .step i {
            display: block;
            font-style: normal;
            font-size: 1.6rem;
            margin-bottom: 2px;
            filter: grayscale(1);
            opacity: .6
        }

        .step.done {
            background: #dcd0ff;
            color: var(--purple-dark)
        }

        .step.done i {
            filter: none;
            opacity: 1
        }

        .step.active {
            background: var(--purple);
            color: var(--cream)
        }

        .step.active i {
            filter: none;
            opacity: 1
        }

        .info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px
        }

        .info div {
            background: var(--cream);
            border-radius: 14px;
            padding: 12px 16px
        }

        .info small {
            display: block;
            color: var(--muted)
        }

        .info b {
            font-size: .98rem
        }

        .qr {
            width: min(280px, 100%);
            margin: 0 auto;
            border-radius: 20px;
            border: 2px solid var(--purple-soft);
            padding: 10px;
            background: #fff
        }

        .qr img {
            width: 100%;
            display: block
        }

        .qr--kosong {
            aspect-ratio: 1;
            display: grid;
            place-items: center;
            text-align: center;
            color: var(--muted);
            font-size: .9rem;
            padding: 20px
        }

        input[type=file] {
            width: 100%;
            padding: 12px;
            border: 2px dashed var(--purple-soft);
            border-radius: 14px;
            background: var(--cream)
        }

        @media(max-width:600px) {
            .box {
                padding: 22px
            }

            .info {
                grid-template-columns: 1fr
            }
        }
    </style>
</head>

<body>
    <div class="wrap">
        <?= $this->renderSection('content') ?>
    </div>
    <?= $this->renderSection('script') ?>
</body>

</html>