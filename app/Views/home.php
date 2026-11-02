<?php $isPelanggan = session()->get('logged_in') && session()->get('role') === 'pelanggan'; ?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?= csrf_hash() ?>">
    <title>Doulyra | Makanan &amp; Minuman</title>
    <meta name="description" content="Doulyra - pesan makanan dan minuman favorit Anda, disiapkan segar dan diantar sampai rumah.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">

    <style>
        :root {
            --purple: #6610f2;
            --purple-dark: #3d0a91;
            --purple-deep: #240657;
            --purple-soft: #ede3ff;
            --cream: #fff6e3;
            --cream-deep: #ffe9bd;
            --ink: #2a1650;
            --muted: #6f6488;
            --radius: 22px;
            --font-display: 'Bricolage Grotesque', 'Trebuchet MS', sans-serif;
            --font-body: 'Figtree', 'Segoe UI', sans-serif;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 84px
        }

        body {
            font-family: var(--font-body);
            background: var(--cream);
            color: var(--ink);
            line-height: 1.6;
            overflow-x: hidden
        }

        img {
            max-width: 100%;
            display: block
        }

        a {
            color: inherit;
            text-decoration: none
        }

        :focus-visible {
            outline: 3px solid var(--purple);
            outline-offset: 3px;
            border-radius: 8px
        }

        .container {
            width: min(1160px, 92%);
            margin-inline: auto
        }

        /* ---------- NAVBAR ---------- */
        .nav {
            position: fixed;
            inset: 0 0 auto 0;
            z-index: 50;
            transition: background .3s, box-shadow .3s
        }

        .nav.scrolled {
            background: rgba(255, 246, 227, .92);
            backdrop-filter: blur(10px);
            box-shadow: 0 6px 24px rgba(36, 6, 87, .12)
        }

        .nav__in {
            display: flex;
            align-items: center;
            justify-content: space-between;
            height: 72px
        }

        .logo {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: 1.7rem;
            color: var(--cream);
            letter-spacing: -.02em;
            transition: color .3s
        }

        .logo i {
            font-style: normal;
            color: var(--cream-deep)
        }

        .nav.scrolled .logo {
            color: var(--purple)
        }

        .nav.scrolled .logo i {
            color: var(--purple-dark)
        }

        .nav__links {
            display: flex;
            gap: 6px;
            align-items: center;
            list-style: none
        }

        .nav__links a {
            padding: 8px 16px;
            border-radius: 99px;
            font-weight: 500;
            color: var(--cream);
            transition: .25s
        }

        .nav.scrolled .nav__links a {
            color: var(--ink)
        }

        .nav__links a:hover {
            background: rgba(255, 255, 255, .18)
        }

        .nav.scrolled .nav__links a:hover {
            background: var(--purple-soft)
        }

        .nav__links .nav__cta {
            background: var(--cream);
            color: var(--purple) !important;
            font-weight: 600
        }

        .nav.scrolled .nav__links .nav__cta {
            background: var(--purple);
            color: var(--cream) !important
        }

        .burger {
            display: none;
            background: none;
            border: 0;
            width: 44px;
            height: 44px;
            cursor: pointer
        }

        .burger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--cream);
            margin: 6px auto;
            transition: .3s
        }

        .nav.scrolled .burger span {
            background: var(--purple)
        }

        /* ---------- HERO ---------- */
        .hero {
            position: relative;
            background: var(--purple);
            color: var(--cream);
            padding: 150px 0 0;
            overflow: hidden;
            isolation: isolate
        }

        .hero::before,
        .hero::after {
            content: "";
            position: absolute;
            border-radius: 50%;
            z-index: -1
        }

        .hero::before {
            width: 620px;
            height: 620px;
            right: -160px;
            top: -180px;
            background: radial-gradient(circle at 30% 30%, #8a45ff, #6610f2 65%)
        }

        .hero::after {
            width: 380px;
            height: 380px;
            left: -140px;
            bottom: 60px;
            background: var(--purple-dark);
            opacity: .55
        }

        .hero__grid {
            display: grid;
            grid-template-columns: 1.05fr .95fr;
            gap: 40px;
            align-items: center;
            padding-bottom: 110px
        }

        .hero__tag {
            display: inline-flex;
            gap: 8px;
            align-items: center;
            background: rgba(255, 246, 227, .14);
            border: 1px solid rgba(255, 246, 227, .3);
            padding: 6px 16px;
            border-radius: 99px;
            font-size: .92rem;
            margin-bottom: 22px
        }

        .hero__tag b {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #7dffb0;
            animation: pulse 1.8s infinite
        }

        .hero h1 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2.6rem, 6.2vw, 5rem);
            line-height: 1.02;
            letter-spacing: -.035em;
            margin-bottom: 22px
        }

        .hero h1 span {
            display: block
        }

        .hero h1 .line2 {
            color: var(--cream-deep);
            font-style: italic;
            font-weight: 700
        }

        .hero p {
            max-width: 480px;
            font-size: 1.1rem;
            opacity: .92;
            margin-bottom: 34px
        }

        .hero__btns {
            display: flex;
            gap: 14px;
            flex-wrap: wrap
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 28px;
            border-radius: 99px;
            font-weight: 600;
            font-family: var(--font-body);
            font-size: 1rem;
            border: 2px solid transparent;
            cursor: pointer;
            transition: transform .25s, box-shadow .25s, background .25s, color .25s
        }

        .btn--cream {
            background: var(--cream);
            color: var(--purple)
        }

        .btn--cream:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 26px rgba(0, 0, 0, .28)
        }

        .btn--ghost {
            border-color: rgba(255, 246, 227, .55);
            color: var(--cream)
        }

        .btn--ghost:hover {
            background: var(--cream);
            color: var(--purple);
            transform: translateY(-3px)
        }

        .hero__stats {
            display: flex;
            gap: 34px;
            margin-top: 44px
        }

        .hero__stats strong {
            display: block;
            font-family: var(--font-display);
            font-size: 1.9rem;
            line-height: 1.1
        }

        .hero__stats span {
            font-size: .88rem;
            opacity: .8
        }

        /* visual banner */
        .hero__visual {
            position: relative;
            display: grid;
            place-items: center;
            min-height: 440px
        }

        .plate {
            position: relative;
            width: min(400px, 80vw);
            aspect-ratio: 1;
            border-radius: 50%;
            background: var(--cream);
            display: grid;
            place-items: center;
            box-shadow: 0 0 0 14px rgba(255, 246, 227, .16), 0 40px 80px rgba(20, 0, 60, .5);
            animation: float 6s ease-in-out infinite
        }

        .plate img,
        .plate .plate__emoji {
            width: 78%;
            height: 78%;
            object-fit: cover;
            border-radius: 50%
        }

        .plate__emoji {
            display: grid;
            place-items: center;
            font-size: 9rem;
            background: var(--cream-deep)
        }

        .plate img.plate__logo {
            width: 80%;
            height: 80%;
            object-fit: cover;
            border-radius: 50%
        }

        .plate__mono {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2.4rem, 6vw, 3.6rem);
            letter-spacing: -.04em;
            color: var(--purple)
        }

        .plate__mono i {
            font-style: normal;
            color: var(--purple-dark)
        }

        .ring {
            position: absolute;
            width: min(480px, 92vw);
            aspect-ratio: 1;
            animation: spin 22s linear infinite
        }

        .ring text {
            font-family: var(--font-display);
            font-size: 15px;
            font-weight: 700;
            letter-spacing: .2em;
            fill: var(--cream-deep)
        }

        .chip {
            position: absolute;
            background: var(--cream);
            color: var(--ink);
            border-radius: 18px;
            padding: 10px 16px 10px 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            box-shadow: 0 18px 40px rgba(20, 0, 60, .35)
        }

        .chip__ico {
            width: 50px;
            height: 50px;
            border-radius: 14px;
            background: var(--purple-soft);
            display: grid;
            place-items: center;
            font-size: 1.7rem;
            overflow: hidden
        }

        .chip__ico img {
            width: 100%;
            height: 100%;
            object-fit: cover
        }

        .chip small {
            display: block;
            color: var(--muted);
            font-size: .78rem;
            line-height: 1.2
        }

        .chip b {
            font-family: var(--font-display);
            font-size: 1rem;
            line-height: 1.25;
            display: block;
            max-width: 150px
        }

        .chip--a {
            left: -6px;
            bottom: 50px;
            animation: float 5s ease-in-out -2s infinite
        }

        .chip--b {
            right: -6px;
            top: 40px;
            animation: float 5.5s ease-in-out -.8s infinite
        }

        /* gelombang pemisah */
        .wave {
            position: absolute;
            left: 0;
            right: 0;
            bottom: -1px;
            line-height: 0
        }

        .wave svg {
            width: 100%;
            height: 80px
        }

        /* entrance banner (sekali saat halaman dibuka) */
        .hero__tag,
        .hero h1 span,
        .hero p,
        .hero__btns,
        .hero__stats,
        .hero__visual {
            opacity: 0;
            animation: rise .9s cubic-bezier(.2, .8, .2, 1) forwards
        }

        .hero__tag {
            animation-delay: .1s
        }

        .hero h1 .line1 {
            animation-delay: .25s
        }

        .hero h1 .line2 {
            animation-delay: .4s
        }

        .hero p {
            animation-delay: .55s
        }

        .hero__btns {
            animation-delay: .7s
        }

        .hero__stats {
            animation-delay: .85s
        }

        .hero__visual {
            animation-delay: .4s
        }

        /* ---------- MARQUEE ---------- */
        .marquee {
            background: var(--purple-deep);
            color: var(--cream);
            overflow: hidden;
            padding: 16px 0;
            border-block: 3px solid var(--cream-deep)
        }

        .marquee__track {
            display: flex;
            gap: 48px;
            width: max-content;
            animation: scroll 28s linear infinite
        }

        .marquee:hover .marquee__track {
            animation-play-state: paused
        }

        .marquee__track span {
            font-family: var(--font-display);
            font-weight: 700;
            font-size: 1.25rem;
            white-space: nowrap;
            display: flex;
            align-items: center;
            gap: 48px
        }

        .marquee__track span::after {
            content: "✦";
            color: var(--cream-deep)
        }

        /* ---------- MENU ---------- */
        .section {
            padding: 96px 0
        }

        .section__head {
            text-align: center;
            max-width: 620px;
            margin: 0 auto 38px
        }

        .section__head h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(2rem, 4.5vw, 3.2rem);
            line-height: 1.1;
            letter-spacing: -.03em;
            color: var(--purple)
        }

        .section__head p {
            color: var(--muted);
            margin-top: 12px
        }

        .tabs {
            display: flex;
            justify-content: center;
            gap: 6px;
            background: var(--purple-soft);
            padding: 6px;
            border-radius: 99px;
            width: fit-content;
            margin: 0 auto 46px;
            position: relative
        }

        .tab {
            position: relative;
            z-index: 1;
            border: 0;
            background: none;
            padding: 12px 30px;
            border-radius: 99px;
            font: 600 1rem var(--font-body);
            color: var(--purple);
            cursor: pointer;
            transition: color .3s
        }

        .tab.active {
            color: var(--cream)
        }

        .tabs__pill {
            position: absolute;
            z-index: 0;
            top: 6px;
            bottom: 6px;
            left: 6px;
            border-radius: 99px;
            background: var(--purple);
            transition: transform .4s cubic-bezier(.5, 1.4, .5, 1), width .4s;
            box-shadow: 0 8px 18px rgba(102, 16, 242, .4)
        }

        .panel {
            display: none
        }

        .panel.active {
            display: block
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 26px
        }

        .empty {
            text-align: center;
            color: var(--muted);
            padding: 40px 0
        }

        .card {
            background: #fff;
            border-radius: var(--radius);
            overflow: hidden;
            border: 2px solid transparent;
            box-shadow: 0 10px 30px rgba(102, 16, 242, .1);
            transition: transform .35s cubic-bezier(.2, .8, .2, 1), box-shadow .35s, border-color .35s;
            display: flex;
            flex-direction: column
        }

        .card:hover {
            transform: translateY(-8px) rotate(-.6deg);
            box-shadow: 0 24px 44px rgba(102, 16, 242, .22);
            border-color: var(--purple)
        }

        .card__img {
            position: relative;
            aspect-ratio: 4/3;
            background: linear-gradient(135deg, var(--cream-deep), var(--purple-soft));
            display: grid;
            place-items: center;
            overflow: hidden
        }

        .card__img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform .6s
        }

        .card:hover .card__img img {
            transform: scale(1.1)
        }

        .card__emoji {
            font-size: 4.4rem;
            transition: transform .4s
        }

        .card:hover .card__emoji {
            transform: scale(1.2) rotate(8deg)
        }

        .badge {
            position: absolute;
            top: 12px;
            left: 12px;
            font-size: .78rem;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 99px
        }

        .badge--ok {
            background: var(--cream);
            color: var(--purple-dark)
        }

        .badge--out {
            background: #3a2a5c;
            color: #fff
        }

        .card__body {
            padding: 20px 20px 22px;
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1
        }

        .card__body h3 {
            font-family: var(--font-display);
            font-size: 1.25rem;
            line-height: 1.25
        }

        .card__body p {
            color: var(--muted);
            font-size: .92rem;
            flex: 1;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden
        }

        .card__foot {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 8px;
            gap: 10px
        }

        .card__foot strong {
            font-family: var(--font-display);
            font-size: 1.15rem;
            color: var(--purple)
        }

        .btn--buy {
            background: var(--purple);
            color: var(--cream);
            padding: 9px 22px
        }

        .btn--buy:hover {
            background: var(--purple-dark);
            transform: scale(1.07)
        }

        .btn--disabled {
            background: #e6e0f0;
            color: #8b82a0;
            padding: 9px 22px;
            cursor: not-allowed
        }

        .card.is-out .card__img {
            filter: grayscale(.85)
        }

        .notice {
            max-width: 760px;
            margin: 0 auto 30px;
            padding: 14px 20px;
            border-radius: 16px;
            font-size: .92rem;
            text-align: center
        }

        .notice--demo {
            background: var(--cream-deep);
            color: #5a3d00
        }

        .notice--err {
            background: #ffe0e0;
            color: #7a1010
        }

        /* ---------- CARA PESAN ---------- */
        .steps {
            background: var(--purple);
            color: var(--cream);
            border-radius: 36px;
            padding: 64px 48px;
            position: relative;
            overflow: hidden
        }

        .steps::after {
            content: "";
            position: absolute;
            width: 320px;
            height: 320px;
            border-radius: 50%;
            background: var(--purple-dark);
            right: -100px;
            bottom: -140px;
            opacity: .6
        }

        .steps h2 {
            font-family: var(--font-display);
            font-weight: 800;
            font-size: clamp(1.8rem, 4vw, 2.8rem);
            letter-spacing: -.03em;
            margin-bottom: 34px;
            position: relative;
            z-index: 1
        }

        .steps__grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 22px;
            position: relative;
            z-index: 1
        }

        .step {
            background: rgba(255, 246, 227, .12);
            border: 1px solid rgba(255, 246, 227, .25);
            border-radius: 20px;
            padding: 24px;
            transition: .3s
        }

        .step:hover {
            background: rgba(255, 246, 227, .2);
            transform: translateY(-5px)
        }

        .step__ico {
            font-size: 2rem;
            margin-bottom: 10px
        }

        .step h3 {
            font-family: var(--font-display);
            font-size: 1.15rem;
            margin-bottom: 4px
        }

        .step p {
            font-size: .92rem;
            opacity: .88
        }

        /* ---------- FOOTER ---------- */
        .footer {
            background: var(--purple-deep);
            color: var(--cream);
            margin-top: 96px;
            position: relative
        }

        .footer__wave {
            position: absolute;
            left: 0;
            right: 0;
            top: -79px;
            line-height: 0
        }

        .footer__wave svg {
            width: 100%;
            height: 80px
        }

        .footer__grid {
            display: grid;
            grid-template-columns: 1.5fr 1fr 1fr 1.2fr;
            gap: 40px;
            padding: 36px 0 50px
        }

        .footer .logo {
            color: var(--cream);
            font-size: 2rem
        }

        .footer p {
            opacity: .78;
            font-size: .95rem;
            margin-top: 10px;
            max-width: 300px
        }

        .footer h4 {
            font-family: var(--font-display);
            font-size: 1.1rem;
            margin-bottom: 14px;
            color: var(--cream-deep)
        }

        .footer ul {
            list-style: none;
            display: grid;
            gap: 9px
        }

        .footer li,
        .footer li a {
            font-size: .95rem;
            opacity: .85
        }

        .footer li a {
            display: inline-block;
            transition: .25s
        }

        .footer li a:hover {
            opacity: 1;
            color: var(--cream-deep);
            transform: translateX(5px)
        }

        .social {
            display: flex;
            gap: 10px;
            margin-top: 20px
        }

        .social a {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: rgba(255, 246, 227, .12);
            display: grid;
            place-items: center;
            font-weight: 700;
            transition: .3s
        }

        .social a:hover {
            background: var(--cream);
            color: var(--purple);
            transform: translateY(-4px) rotate(-8deg)
        }

        .footer__bottom {
            border-top: 1px solid rgba(255, 246, 227, .18);
            padding: 20px 0;
            text-align: center;
            font-size: .88rem;
            opacity: .7
        }

        /* ---------- LIVE CHAT ---------- */
        .chat-badge {
            display: inline-grid;
            place-items: center;
            min-width: 20px;
            height: 20px;
            padding: 0 6px;
            margin-left: 4px;
            border-radius: 99px;
            background: #ff4d6d;
            color: #fff;
            font-size: .72rem;
            font-weight: 700
        }

        .chat-badge[hidden] {
            display: none
        }

        .chat-modal {
            position: fixed;
            right: 24px;
            bottom: 24px;
            width: min(380px, calc(100vw - 32px));
            height: min(520px, calc(100vh - 110px));
            background: #fff;
            border-radius: 24px;
            box-shadow: 0 30px 70px rgba(36, 6, 87, .35);
            display: flex;
            flex-direction: column;
            overflow: hidden;
            z-index: 80;
            animation: rise .35s cubic-bezier(.2, .8, .2, 1)
        }

        .chat-modal[hidden] {
            display: none
        }

        .chat-modal__head {
            background: var(--purple);
            color: var(--cream);
            padding: 16px 18px;
            display: flex;
            justify-content: space-between;
            align-items: center
        }

        .chat-modal__head strong {
            font-family: var(--font-display);
            font-size: 1.1rem
        }

        .chat-modal__head small {
            display: block;
            opacity: .8;
            font-size: .78rem
        }

        .chat-modal__head button {
            background: none;
            border: 0;
            color: var(--cream);
            font-size: 1.8rem;
            line-height: 1;
            cursor: pointer
        }

        .chat-modal__body {
            flex: 1;
            overflow-y: auto;
            padding: 16px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            background: var(--cream)
        }

        .msg {
            max-width: 80%;
            padding: 9px 14px;
            border-radius: 18px;
            font-size: .93rem;
            word-wrap: break-word;
            white-space: pre-wrap
        }

        .msg small {
            display: block;
            font-size: .7rem;
            opacity: .65;
            margin-top: 2px
        }

        .msg--me {
            align-self: flex-end;
            background: var(--purple);
            color: var(--cream);
            border-bottom-right-radius: 4px
        }

        .msg--admin {
            align-self: flex-start;
            background: #fff;
            border: 1px solid var(--purple-soft);
            border-bottom-left-radius: 4px
        }

        .chat-empty {
            margin: auto;
            text-align: center;
            color: var(--muted);
            font-size: .9rem
        }

        .chat-modal__form {
            display: flex;
            gap: 8px;
            padding: 12px;
            border-top: 1px solid var(--purple-soft)
        }

        .chat-modal__form input {
            flex: 1;
            min-width: 0;
            border: 2px solid var(--purple-soft);
            border-radius: 99px;
            padding: 10px 16px;
            font: inherit;
            outline: none
        }

        .chat-modal__form input:focus {
            border-color: var(--purple)
        }

        /* ---------- SCROLL REVEAL ---------- */
        .reveal {
            opacity: 0;
            transform: translateY(34px);
            transition: opacity .8s ease, transform .8s cubic-bezier(.2, .8, .2, 1)
        }

        .reveal.in {
            opacity: 1;
            transform: none
        }

        /* ---------- KEYFRAMES ---------- */
        @keyframes rise {
            from {
                opacity: 0;
                transform: translateY(34px)
            }

            to {
                opacity: 1;
                transform: none
            }
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0)
            }

            50% {
                transform: translateY(-16px)
            }
        }

        @keyframes spin {
            to {
                transform: rotate(360deg)
            }
        }

        @keyframes scroll {
            to {
                transform: translateX(calc(-50% - 24px))
            }
        }

        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(125, 255, 176, .7)
            }

            70% {
                box-shadow: 0 0 0 10px rgba(125, 255, 176, 0)
            }

            100% {
                box-shadow: 0 0 0 0 rgba(125, 255, 176, 0)
            }
        }

        /* ---------- RESPONSIVE ---------- */
        @media (max-width:900px) {
            .hero__grid {
                grid-template-columns: 1fr;
                text-align: center
            }

            .hero p {
                margin-inline: auto
            }

            .hero__btns,
            .hero__stats {
                justify-content: center
            }

            .hero__visual {
                min-height: 380px
            }

            .footer__grid {
                grid-template-columns: 1fr 1fr
            }

            .steps {
                padding: 44px 26px
            }
        }

        @media (max-width:720px) {
            .burger {
                display: block
            }

            .nav__links {
                position: fixed;
                inset: 72px 4% auto 4%;
                flex-direction: column;
                background: var(--cream);
                padding: 16px;
                border-radius: 22px;
                box-shadow: 0 20px 50px rgba(36, 6, 87, .3);
                transform: translateY(-20px);
                opacity: 0;
                pointer-events: none;
                transition: .3s
            }

            .nav__links.open {
                transform: none;
                opacity: 1;
                pointer-events: auto
            }

            .nav__links a {
                color: var(--ink) !important;
                width: 100%;
                text-align: center
            }

            .chip {
                display: none
            }

            .footer__grid {
                grid-template-columns: 1fr
            }

            .chat-modal {
                right: 16px;
                bottom: 16px
            }
        }

        @media (prefers-reduced-motion:reduce) {

            *,
            *::before,
            *::after {
                animation-duration: .01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: .01ms !important
            }

            .reveal,
            .hero__tag,
            .hero h1 span,
            .hero p,
            .hero__btns,
            .hero__stats,
            .hero__visual {
                opacity: 1;
                transform: none
            }
        }
    </style>
</head>

<body>

    <!-- ================= NAVBAR ================= -->
    <header class="nav" id="nav">
        <div class="container nav__in">
            <a href="#beranda" class="logo">Doulyra<i>.</i></a>
            <button class="burger" id="burger" aria-label="Buka menu navigasi"><span></span><span></span><span></span></button>
            <ul class="nav__links" id="navLinks">
                <li><a href="#beranda">Beranda</a></li>
                <li><a href="#menu">Menu</a></li>
                <li><a href="#kontak">Kontak</a></li>

                <!-- Tombol Live Chat -->
                <li>
                    <?php if ($isPelanggan): ?>
                        <a href="#" id="chatBtn" aria-label="Live chat dengan admin">Live Chat <span class="chat-badge" id="chatBadge" hidden>0</span></a>
                    <?php else: ?>
                        <a href="<?= site_url('login') ?>" aria-label="Masuk untuk memakai live chat">Live Chat</a>
                    <?php endif; ?>
                </li>

                <?php if (session()->get('logged_in')): ?>
                    <li><a href="#menu">Halo, <?= esc(session()->get('nama')) ?></a></li>
                    <li><a href="<?= site_url('logout') ?>" class="nav__cta">Keluar</a></li>
                <?php else: ?>
                    <li><a href="<?= site_url('login') ?>">Masuk</a></li>
                    <li><a href="<?= site_url('register') ?>" class="nav__cta">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <!-- ================= BANNER ================= -->
    <section class="hero" id="beranda">
        <div class="container hero__grid">
            <div>
                <span class="hero__tag"><b></b> Buka setiap hari &middot; Pesan antar tersedia</span>
                <h1>
                    <span class="line1">Lapar atau haus?</span>
                    <span class="line2">Doulyra jawabannya.</span>
                </h1>
                <p>Makanan hangat dan minuman segar, disiapkan saat Anda memesan lalu diantar sampai depan pintu.</p>
                <div class="hero__btns">
                    <a href="#menu" class="btn btn--cream">Lihat menu</a>
                    <a href="<?= site_url('register') ?>" class="btn btn--ghost">Buat akun</a>
                </div>
                <div class="hero__stats">
                    <div><strong data-count="<?= count($makanan) ?>">0</strong><span>Pilihan makanan</span></div>
                    <div><strong data-count="<?= count($minuman) ?>">0</strong><span>Pilihan minuman</span></div>
                    <div><strong>Cash &amp; QRIS</strong><span>Metode bayar</span></div>
                </div>
            </div>

            <div class="hero__visual" aria-hidden="true">
                <svg class="ring" viewBox="0 0 300 300">
                    <defs>
                        <path id="circ" d="M150,150 m-125,0 a125,125 0 1,1 250,0 a125,125 0 1,1 -250,0" />
                    </defs>
                    <text>
                        <textPath href="#circ">MAKANAN ✦ MINUMAN ✦ SEGAR ✦ DIANTAR ✦ DOULYRA ✦ MAKANAN ✦ MINUMAN ✦ </textPath>
                    </text>
                </svg>

                <div class="plate">
                    <?php if (is_file(FCPATH . 'assets/img/logo.jpg')): ?>
                        <img class="plate__logo" src="<?= base_url('assets/img/logo.jpg') ?>" alt="Logo Doulyra">
                    <?php else: ?>
                        <span class="plate__mono">Doulyra<i>.</i></span>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="wave">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#240657" d="M0,40 C240,90 480,0 720,30 C960,60 1200,90 1440,30 L1440,80 L0,80 Z" />
            </svg>
        </div>
    </section>

    <div class="marquee" aria-hidden="true">
        <div class="marquee__track">
            <?php for ($i = 0; $i < 2; $i++): ?>
                <span>Dimasak saat dipesan</span><span>Minuman dingin segar</span><span>Bayar cash atau QRIS</span><span>Diantar ke alamat Anda</span><span>Rasa yang bikin kembali</span>
            <?php endfor; ?>
        </div>
    </div>

    <main>
        <section class="section" id="menu">
            <div class="container">
                <div class="section__head reveal">
                    <h2>Menu Doulyra</h2>
                    <p>Pilih makanan atau minuman favoritmu. Stok diperbarui langsung dari dapur kami.</p>
                </div>

                <?php if ($demo): ?>
                    <div class="notice notice--demo">Tabel <b>makanan</b> dan <b>minuman</b> masih kosong, jadi yang tampil adalah data contoh. Isi tabel lewat halaman karyawan dan menu asli akan muncul di sini.</div>
                <?php endif; ?>

                <div class="tabs reveal" role="tablist">
                    <span class="tabs__pill" id="pill"></span>
                    <button class="tab active" role="tab" data-target="panel-makanan">Makanan</button>
                    <button class="tab" role="tab" data-target="panel-minuman">Minuman</button>
                </div>

                <div class="panel active" id="panel-makanan" role="tabpanel">
                    <div class="grid">
                        <?php foreach ($makanan as $m): ?><?= view('partials/kartu_menu', ['item' => $m, 'jenis' => 'makanan']) ?><?php endforeach; ?>
                        <?php if (!$makanan): ?><p class="empty">Menu makanan belum tersedia.</p><?php endif; ?>
                    </div>
                </div>

                <div class="panel" id="panel-minuman" role="tabpanel">
                    <div class="grid">
                        <?php foreach ($minuman as $m): ?><?= view('partials/kartu_menu', ['item' => $m, 'jenis' => 'minuman']) ?><?php endforeach; ?>
                        <?php if (!$minuman): ?><p class="empty">Menu minuman belum tersedia.</p><?php endif; ?>
                    </div>
                </div>
            </div>
        </section>

        <!-- ================= CARA PESAN ================= -->
        <section class="container" id="cara-pesan">
            <div class="steps reveal">
                <h2>Pesan dalam empat langkah</h2>
                <div class="steps__grid">
                    <div class="step">
                        <div class="step__ico">📝</div>
                        <h3>Daftar &amp; masuk</h3>
                        <p>Buat akun pelanggan dengan nama, nomor HP, dan alamat Anda.</p>
                    </div>
                    <div class="step">
                        <div class="step__ico">🍽️</div>
                        <h3>Pilih menu</h3>
                        <p>Tentukan makanan dan minuman, lalu isi jumlah pesanan.</p>
                    </div>
                    <div class="step">
                        <div class="step__ico">💳</div>
                        <h3>Bayar</h3>
                        <p>Bayar tunai atau scan QRIS lalu unggah bukti pembayaran.</p>
                    </div>
                    <div class="step">
                        <div class="step__ico">🛵</div>
                        <h3>Pesanan datang</h3>
                        <p>Pantau status: Di Siapkan, Di Antar, lalu Di Terima.</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- ================= FOOTER ================= -->
    <footer class="footer" id="kontak">
        <div class="footer__wave">
            <svg viewBox="0 0 1440 80" preserveAspectRatio="none">
                <path fill="#240657" d="M0,80 L0,40 C240,0 480,70 720,40 C960,10 1200,0 1440,40 L1440,80 Z" />
            </svg>
        </div>
        <div class="container">
            <div class="footer__grid">
                <div>
                    <a href="#beranda" class="logo">Doulyra<i>.</i></a>
                    <p>Toko makanan dan minuman yang menyiapkan pesanan Anda segar, lalu mengantarnya sampai rumah.</p>
                    <div class="social">
                        <a href="#" aria-label="Instagram">IG</a>
                        <a href="#" aria-label="Facebook">FB</a>
                        <a href="#" aria-label="WhatsApp">WA</a>
                    </div>
                </div>
                <div>
                    <h4>Jelajahi</h4>
                    <ul>
                        <li><a href="#beranda">Beranda</a></li>
                        <li><a href="#menu">Menu</a></li>
                        <li><a href="#cara-pesan">Cara pesan</a></li>
                    </ul>
                </div>
                <div>
                    <h4>Akun</h4>
                    <ul>
                        <?php if (session()->get('logged_in')): ?>
                            <li><a href="#menu">Halo, <?= esc(session()->get('nama')) ?></a></li>
                            <li><a href="<?= site_url('logout') ?>" class="nav__cta">Keluar</a></li>
                        <?php else: ?>
                            <li><a href="<?= site_url('login') ?>">Masuk</a></li>
                            <li><a href="<?= site_url('register') ?>" class="nav__cta">Daftar</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div>
                    <h4>Hubungi kami</h4>
                    <ul>
                        <li>📍 Padang, Sumatera Barat</li>
                        <li>📞 0831-8211-7492</li>
                        <li>✉️ doulyra@gmail.com</li>
                        <li>🕘 Setiap hari, 09.00 - 21.00</li>
                    </ul>
                </div>
            </div>
            <div class="footer__bottom">&copy; <?= date('Y') ?> Doulyra. Semua hak dilindungi.</div>
        </div>
    </footer>

    <!-- ================= MODAL LIVE CHAT ================= -->
    <?php if ($isPelanggan): ?>
        <div class="chat-modal" id="chatModal" role="dialog" aria-label="Live chat dengan admin" hidden>
            <div class="chat-modal__head">
                <div>
                    <strong>Chat dengan Admin</strong>
                    <small>Biasanya dibalas dalam beberapa menit</small>
                </div>
                <button type="button" id="chatClose" aria-label="Tutup chat">&times;</button>
            </div>
            <div class="chat-modal__body" id="chatBody"></div>
            <form class="chat-modal__form" id="chatForm" autocomplete="off">
                <input type="text" id="chatInput" placeholder="Tulis pesan..." maxlength="500" required>
                <button type="submit" class="btn btn--buy">Kirim</button>
            </form>
        </div>
    <?php endif; ?>

    <script>
        // Navbar berubah saat scroll
        const nav = document.getElementById('nav');
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 40);
        onScroll();
        window.addEventListener('scroll', onScroll, {
            passive: true
        });

        // Menu mobile
        const burger = document.getElementById('burger');
        const links = document.getElementById('navLinks');
        burger.addEventListener('click', () => links.classList.toggle('open'));
        links.querySelectorAll('a').forEach(a => a.addEventListener('click', () => links.classList.remove('open')));

        // Tab Makanan / Minuman dengan pil yang bergeser
        const tabs = document.querySelectorAll('.tab');
        const pill = document.getElementById('pill');

        function movePill() {
            const t = document.querySelector('.tab.active');
            pill.style.width = t.offsetWidth + 'px';
            pill.style.transform = `translateX(${t.offsetLeft - 6}px)`;
        }
        tabs.forEach(t => t.addEventListener('click', () => {
            tabs.forEach(x => x.classList.remove('active'));
            t.classList.add('active');
            document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
            document.getElementById(t.dataset.target).classList.add('active');
            movePill();
        }));
        window.addEventListener('load', movePill);
        window.addEventListener('resize', movePill);

        // Muncul saat di-scroll
        const io = new IntersectionObserver(es => es.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('in');
                io.unobserve(e.target);
            }
        }), {
            threshold: .15
        });
        document.querySelectorAll('.reveal').forEach(el => io.observe(el));

        // Hitung angka statistik
        document.querySelectorAll('[data-count]').forEach(el => {
            const end = +el.dataset.count;
            let n = 0;
            const step = Math.max(1, Math.ceil(end / 30));
            const timer = setInterval(() => {
                n = Math.min(end, n + step);
                el.textContent = n + '+';
                if (n >= end) clearInterval(timer);
            }, 40);
        });

        // ===== LIVE CHAT (pelanggan) =====
        const chatBtn = document.getElementById('chatBtn');
        if (chatBtn) {
            const modal = document.getElementById('chatModal');
            const body = document.getElementById('chatBody');
            const form = document.getElementById('chatForm');
            const input = document.getElementById('chatInput');
            const badge = document.getElementById('chatBadge');
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            let timer = null,
                lastId = -1;

            const jam = s => (s || '').substring(11, 16);

            function render(list) {
                const idTerakhir = list.length ? list[list.length - 1].idchat : 0;
                if (idTerakhir === lastId) return; // tidak ada pesan baru
                lastId = idTerakhir;
                body.innerHTML = '';
                if (!list.length) {
                    body.innerHTML = '<p class="chat-empty">Halo! Ada yang bisa kami bantu? 👋</p>';
                    return;
                }
                list.forEach(m => {
                    const d = document.createElement('div');
                    d.className = 'msg ' + (m.pengirim === 'pelanggan' ? 'msg--me' : 'msg--admin');
                    d.textContent = m.pesan; // textContent: aman dari XSS
                    const t = document.createElement('small');
                    t.textContent = jam(m.created_at);
                    d.appendChild(t);
                    body.appendChild(d);
                });
                body.scrollTop = body.scrollHeight;
            }

            async function muat(buka) {
                try {
                    const r = await fetch('<?= site_url('chat/ambil') ?>' + (buka ? '?buka=1' : ''));
                    if (!r.ok) return;
                    const j = await r.json();
                    if (buka) render(j.pesan);
                    badge.hidden = !j.unread;
                    badge.textContent = j.unread;
                } catch (e) {}
            }

            function mulaiPolling() {
                clearInterval(timer);
                const terbuka = !modal.hidden;
                timer = setInterval(() => muat(terbuka), terbuka ? 3000 : 15000);
            }

            chatBtn.addEventListener('click', e => {
                e.preventDefault();
                modal.hidden = !modal.hidden;
                if (!modal.hidden) {
                    lastId = -1;
                    muat(true);
                    input.focus();
                }
                mulaiPolling();
            });

            document.getElementById('chatClose').addEventListener('click', () => {
                modal.hidden = true;
                mulaiPolling();
            });

            form.addEventListener('submit', async e => {
                e.preventDefault();
                const teks = input.value.trim();
                if (!teks) return;
                input.value = '';
                const fd = new FormData();
                fd.append('pesan', teks);
                await fetch('<?= site_url('chat/kirim') ?>', {
                    method: 'POST',
                    body: fd,
                    headers: {
                        'X-CSRF-TOKEN': csrf,
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                muat(true);
            });

            muat(false);
            mulaiPolling();
        }
    </script>
</body>

</html>