<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>DankTerm — Memes Neon</title>
    <meta name="description"
        content="Pantalla completa, header con enlaces, tarjetas de estadísticas, panel Dexscreener amplio, pizarra de prompts y grid de memes con acciones." />
    <style>
        /* =========================
       TOKENS / THEME
       ========================= */
        :root {
            --accent: #9CFFD1;
            /* brighter mint neon */
            --accent-weak: #6fffc0;
            --text: #f0fff9;
            --muted: #c6f5dd;
            --bg: #04120c;
            --panel: #0b1f16;
            --border: rgba(156, 255, 209, .36);
            --glow: 0 0 8px rgba(156, 255, 209, .35), 0 0 18px rgba(156, 255, 209, .18);
            --radius: 14px;
            --font-mono: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            --shadow-soft: 0 24px 70px rgba(0, 0, 0, .6);
        }

        * {
            scrollbar-width: thin;
            scrollbar-color: var(--accent) rgba(255, 255, 255, 0.06);
        }

        .meme-maker__file {
            font-family: var(--font-mono);
            color: var(--text);
            background: linear-gradient(180deg, rgba(113, 255, 151, .14), rgba(113, 255, 151, .06));
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 6px;
            cursor: pointer;
        }

        /* Botón nativo (Choose File) */
        .meme-maker__file::file-selector-button {
            padding: 8px 12px;
            border-radius: 10px;
            font-weight: 800;
            font-family: var(--font-mono);
            background: linear-gradient(180deg, rgba(113, 255, 151, .22), rgba(113, 255, 151, .08));
            color: var(--text);
            border: 1px solid var(--border);
            cursor: pointer;
            transition: transform .12s ease, box-shadow .2s ease;
        }

        .meme-maker__file::file-selector-button:hover {
            transform: translateY(-1px);
            box-shadow: 0 0 8px #71FF97;
        }


        /* Chrome, Safari, Edge */
        *::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        *::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.06);
            border-radius: 999px;
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent), #44ff8b);
            border-radius: 999px;
            box-shadow:
                0 0 8px var(--accent),
                inset 0 0 6px rgba(0, 0, 0, 0.5);
            border: 2px solid rgba(0, 0, 0, 0.35);
        }

        *::-webkit-scrollbar-thumb:hover {
            background: linear-gradient(180deg, #8affb3, var(--accent));
            box-shadow: 0 0 12px var(--accent);
        }


        .meme-maker__range {
            -webkit-appearance: none;
            width: 180px;
            height: 6px;
            border-radius: 4px;
            background: var(--border);
            outline: none;
            cursor: pointer;
        }

        /* WebKit (Chrome, Edge, Safari) */
        .meme-maker__range::-webkit-slider-runnable-track {
            height: 6px;
            border-radius: 4px;
            background: var(--accent);
        }

        .meme-maker__range::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: var(--glow);
            margin-top: -5px;
            /* centers thumb on track */
            cursor: grab;
        }

        /* Firefox */
        .meme-maker__range::-moz-range-track {
            height: 6px;
            border-radius: 4px;
            background: var(--accent);
        }

        .meme-maker__range::-moz-range-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: var(--glow);
            cursor: grab;
            border: none;
        }

        /* IE / Edge Legacy */
        .meme-maker__range::-ms-track {
            height: 6px;
            border-radius: 4px;
            background: transparent;
            border-color: transparent;
            color: transparent;
        }

        .meme-maker__range::-ms-fill-lower,
        .meme-maker__range::-ms-fill-upper {
            background: var(--accent);
        }

        .meme-maker__range::-ms-thumb {
            width: 16px;
            height: 16px;
            border-radius: 50%;
            background: var(--accent);
            box-shadow: var(--glow);
            cursor: grab;
            border: none;
        }



        /* Base */
        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            min-height: 100vh;
            color: var(--text);
            font-family: var(--font-mono);
            background:
                radial-gradient(1200px 900px at 10% -10%, rgba(113, 255, 151, .10), transparent 55%),
                radial-gradient(900px 900px at 100% 0%, rgba(113, 255, 151, .08), transparent 65%),
                var(--bg);
            letter-spacing: .15px;
            line-height: 1.45;
            overflow: hidden;
            /* scroll en main */
        }

        a {
            color: var(--accent);
            text-decoration: none
        }

        .app::before {
            content: "";
            position: fixed;
            inset: 0;
            pointer-events: none;
            z-index: -1;
            background-image:
                linear-gradient(rgba(113, 255, 151, .06) 1px, transparent 1px),
                linear-gradient(90deg, rgba(113, 255, 151, .06) 1px, transparent 1px);
            background-size: 32px 32px;
            mask-image: radial-gradient(1300px 1000px at 50% 5%, black, transparent 70%);
            opacity: .5;
        }

        /* =========================
       LAYOUT (BEM: layout)
       ========================= */
        .layout {
            position: fixed;
            inset: 0;
            display: grid;
            grid-template-rows: 70px 1fr;
            grid-template-areas:
                "header"
                "main";
        }

        .header {
            grid-area: header;
        }

        .main {
            grid-area: main;
            overflow: auto;
        }

        /* =========================
       HEADER (BEM: header)
       ========================= */
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 0 16px;
            backdrop-filter: blur(8px);
            background: linear-gradient(180deg, rgba(0, 0, 0, .55), rgba(0, 0, 0, .18));
            border-bottom: 1px solid var(--border);
        }

        .header__brand {
            display: flex;
            align-items: center;
            gap: 10px;
            color: var(--accent);
            text-transform: uppercase;
            letter-spacing: 2px;
            text-shadow: var(--glow)
        }

        .header__logo {
            width: 26px;
            height: 26px;
            border-radius: 8px;
            box-shadow: var(--glow);
            background: conic-gradient(from 180deg at 50% 50%, var(--accent), var(--accent-weak), var(--accent));
            position: relative;
        }

        .header__logo::after {
            content: ">";
            position: absolute;
            inset: 0;
            display: grid;
            place-items: center;
            color: #013a1a;
            font-weight: 900;
            mix-blend-mode: soft-light
        }

        .header__links {
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap
        }

        .header__link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 10px;
            border-radius: 12px;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(113, 255, 151, .16), rgba(113, 255, 151, .06));
            transition: transform .12s ease;
            box-shadow: 0 2px 0 black;
            color: var(--text);
        }

        .header__link:hover {
            transform: translateY(-1px)
        }

        .header__icon {
            width: 18px;
            height: 18px;
            fill: var(--accent)
        }

        .header__contract {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            border-radius: 999px;
            cursor: pointer;
            border: 2px solid var(--accent);
            background: linear-gradient(145deg, rgba(9, 40, 25, .8), rgba(4, 18, 12, .9));
            color: var(--text);
            font-family: var(--font-mono);
            font-size: 14px;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
            box-shadow: 0 0 12px rgba(156, 255, 209, .5), inset 0 0 6px rgba(156, 255, 209, .2);
            transition: all .2s ease-in-out;
        }

        .header__contract:hover {
            border-color: var(--accent-weak);
            box-shadow: 0 0 18px rgba(156, 255, 209, .8), inset 0 0 8px rgba(156, 255, 209, .25);
            transform: translateY(-1px) scale(1.02);
        }

        .header__contract:active {
            transform: translateY(1px) scale(0.98);
            box-shadow: 0 0 8px rgba(156, 255, 209, .4), inset 0 0 4px rgba(156, 255, 209, .15);
        }

        .header__contract-badge {
            padding: 3px 8px;
            border-radius: 999px;
            background: var(--accent);
            color: #04120c;
            font-size: 11px;
            font-weight: 700;
            box-shadow: 0 0 8px var(--accent);
        }

        .header__contract-code {
            font-weight: 800;
            font-size: 14px;
            letter-spacing: 0.5px;
            color: var(--accent);
            text-shadow: 0 0 6px rgba(156, 255, 209, .6);
        }


        /* =========================
       MAIN (BEM: main, section, panel)
       ========================= */
        .main {
            padding: 18px
        }

        .main__section {
            display: grid;
            gap: 14px;
            margin-bottom: 18px
        }

        /* Stats cards grid */
        .stats {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(6, minmax(0, 1fr));
        }

        @media (max-width: 1200px) {
            .stats {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 720px) {
            .stats {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .stat-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(16, 26, 20, .9), rgba(8, 12, 9, .98));
            padding: 12px;
            box-shadow: var(--shadow-soft);
            display: grid;
            gap: 8px;
        }

        .stat-card__label {
            color: var(--muted);
            font-size: 12px
        }

        .stat-card__value {
            font-weight: 900;
            font-size: 18px
        }

        .stat-card__bar {
            width: 100%;
            height: 8px;
            border-radius: 999px;
            background: rgba(113, 255, 151, .15);
            outline: 1px solid rgba(113, 255, 151, .22);
            position: relative;
            overflow: hidden
        }

        .stat-card__fill {
            height: 100%;
            width: 40%;
            background: linear-gradient(90deg, var(--accent-weak), var(--accent));
            box-shadow: var(--glow)
        }

        .myselfdone {
            display: grid;
            grid-template-columns: 1fr 3fr;
            gap: 2rem;
        }

        .stat-card__hint {
            font-size: 12px;
            color: var(--muted)
        }

        /* Dexscreener panel — now roomy */
        .panel {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(180deg, rgba(15, 25, 18, .85), rgba(6, 10, 8, .95));
            box-shadow: var(--glow);
            overflow: clip
        }

        .panel__header {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(113, 255, 151, .22);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .panel__title {
            margin: 0;
            font-size: 15px;
            color: var(--accent);
            text-shadow: var(--glow)
        }

        .panel__body {
            padding: 0;
        }

        .panel--dex .panel__body {
            height: 620px;
            /* más espacio para el chart */
            min-height: 100%;
            /* nunca comprimido */
        }

        .panel__iframe {
            width: 100%;
            height: 100%;
            border: 0;
            background: #061108;
            border-radius: 0 0 var(--radius) var(--radius);
        }

        /* Random: Pizarra de Prompts (BEM: board) */
        .board {
            border: 1px solid var(--border);
            border-radius: var(--radius);
            background: linear-gradient(180deg, rgba(7, 12, 9, .92), rgba(4, 8, 6, .98));
            box-shadow: var(--glow);
            display: grid;
            grid-template-rows: auto 1fr auto;
        }

        .board__header {
            padding: 12px 14px;
            border-bottom: 1px solid rgba(113, 255, 151, .22);
            display: flex;
            align-items: center;
            justify-content: space-between
        }

        .board__title {
            margin: 0;
            font-size: 15px;
            color: var(--accent);
            text-shadow: var(--glow)
        }

        .board__body {
            padding: 12px;
            display: grid;
            gap: 10px
        }

        .board__item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid var(--border);
        }

        .board__text {
            flex: 1;
            font-size: 13px
        }

        .board__actions {
            display: flex;
            gap: 8px
        }

        .btn {
            display: inline-grid;
            place-items: center;
            width: 36px;
            height: 36px;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(113, 255, 151, .16), rgba(113, 255, 151, .06));
            transition: transform .12s ease;
        }

        .btn:hover {
            transform: translateY(-1px)
        }

        .btn__icon {
            width: 18px;
            height: 18px;
            fill: var(--accent)
        }

        .board__footer {
            padding: 12px 14px;
            border-top: 1px dashed rgba(113, 255, 151, .22);
            font-size: 12px;
            color: var(--muted)
        }

        /* Memes grid */
        .memes__title {
            margin: 8px 0 10px 0;
            font-size: 16px;
            color: var(--accent);
            text-shadow: var(--glow)
        }

        .meme-grid {
            display: grid;
            gap: 12px;
            grid-template-columns: repeat(4, minmax(0, 1fr));
        }

        .terminal-footer {
            margin-top: 16px;
            padding: 12px 16px;
            border-top: 1px solid var(--border);
            background: #061108;
            border-radius: 0 0 var(--radius) var(--radius);
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: center;
            justify-content: space-between;
            font-family: var(--font-mono);
            font-size: 12px;
            color: var(--muted);
        }

        .terminal-footer__col {
            display: flex;
            gap: 8px;
            align-items: center
        }

        .terminal-footer__link {
            color: #71FF97;
            font-weight: 700;
            text-decoration: none;
        }

        .terminal-footer__link:hover {
            text-shadow: 0 0 6px #71FF97;
        }

        .terminal-footer__donate {
            gap: 8px;
            flex-wrap: wrap
        }

        .terminal-footer__donate-label {
            color: var(--text);
            font-weight: 600
        }

        .terminal-footer__wallet {
            border: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(113, 255, 151, .22), rgba(113, 255, 151, .08));
            color: var(--text);
            font-family: var(--font-mono);
            font-size: 11px;
            padding: 6px 10px;
            border-radius: 10px;
            cursor: pointer;
            user-select: all;
        }

        .terminal-footer__wallet:hover {
            text-shadow: 0 0 6px #71FF97;
        }



        @media (max-width: 1200px) {
            .meme-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 820px) {
            .meme-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 520px) {
            .meme-grid {
                grid-template-columns: 1fr;
            }
        }

        .meme-card {
            border: 1px solid var(--border);
            border-radius: 12px;
            background: linear-gradient(180deg, rgba(16, 26, 20, .9), rgba(8, 12, 9, .98));
            overflow: hidden;
            display: flex;
            flex-direction: column
        }

        .meme-card__media {
            position: relative;
            width: 100%;
            padding-top: 75%;
            background: #061108;
            overflow: hidden
        }

        .meme-card__img {
            position: absolute;
            inset: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            filter: saturate(1.05) contrast(1.02)
        }

        .meme-card__caption {
            padding: 10px;
            display: grid;
            gap: 8px
        }

        .meme-card__text {
            margin: 0;
            font-size: 13px
        }

        .meme-card__actions {
            display: flex;
            align-items: center;
            gap: 10px
        }

        .icon-btn {
            width: 38px;
            height: 38px;
            display: grid;
            place-items: center;
            border-radius: 10px;
            cursor: pointer;
            border: 1px solid var(--border);
            background: linear-gradient(180deg, rgba(113, 255, 151, .16), rgba(113, 255, 151, .06));
            transition: transform .12s ease;
        }

        .icon-btn:hover {
            transform: translateY(-1px)
        }

        .icon-btn__svg {
            width: 18px;
            height: 18px;
            fill: var(--accent)
        }

        /* Toast */
        .toast {
            position: fixed;
            left: 50%;
            transform: translateX(-50%) translateY(20px);
            bottom: 18px;
            padding: 10px 14px;
            border-radius: 999px;
            background: rgba(8, 18, 12, .95);
            color: var(--text);
            border: 1px solid var(--border);
            box-shadow: var(--glow);
            font-size: 13px;
            opacity: 0;
            pointer-events: none;
            transition: opacity .18s ease, transform .18s ease;
            z-index: 2000;
        }

        .toast--show {
            opacity: 1;
            transform: translateX(-50%) translateY(0)
        }

        /* Focus */
        .focusable:focus-visible {
            outline: 3px solid #c4ffd4;
            outline-offset: 2px
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {

            .header__link:hover,
            .btn:hover,
            .icon-btn:hover {
                transform: none
            }
        }

        @media screen and (max-width: 768px) {

            #linkx,
            #linkdx,
            #linktg {
                display: none;
            }

            .myselfdone {
                grid-template-columns: 1fr
            }

            .panel--dex .panel__body {
                height: auto;
            }

            .header__logo {
                display: none;
            }
        }
    </style>
    <link rel="icon" type="image/png" href="/assets/boot/base.png">

</head>

<body>
    <div class="app">
        <div class="layout">
            <!-- ============== HEADER ============== -->
            <header class="header" aria-label="Encabezado">
                <div class="header__brand">
                    <span class="header__logo" aria-hidden="true"></span>
                    <strong>Dank Terminal</strong>
                </div>

                <nav class="header__links" aria-label="Enlaces">
                    <a class="header__link focusable" id="linktg" href="https://t.me/DANKCTO3" target="_blank"
                        rel="noopener" aria-label="Telegram">
                        <svg class="header__icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M9.03 15.5 8.9 19c.43 0 .62-.18.84-.4l2.02-1.94 4.19 3.06c.77.42 1.33.2 1.55-.71l2.81-13.19h.01c.25-1.17-.42-1.63-1.16-1.35L2.3 9.3C1.16 9.76 1.18 10.42 2.12 10.7l4.7 1.46 10.9-6.86c.51-.33.98-.15.6.18" />
                        </svg>
                        <span>Telegram</span>
                    </a>
                    <a class="header__link focusable" id ="linkdx"
                        href="https://dexscreener.com/solana/4d1ldienf5rktjivgbvfbbuw4kpcnpyprpnnjk1at8z2"
                        target="_blank" rel="noopener" aria-label="Dexscreener">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <rect width="24" height="24" fill="url(#pattern0_4954_8)" />
                            <defs>
                                <pattern id="pattern0_4954_8" patternContentUnits="objectBoundingBox" width="1"
                                    height="1">
                                    <use xlink:href="#image0_4954_8" transform="scale(0.00333333)" />
                                </pattern>
                                <image id="image0_4954_8" width="300" height="300" preserveAspectRatio="none"
                                    xlink:href="data:image/png;base64,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" />
                            </defs>
                        </svg>

                        <span>Dexscreener</span>
                    </a>
                    <a class="header__link focusable" id="linkx" href="https://x.com/dankmemessolana"
                        target="_blank" rel="noopener" aria-label="X">
                        <svg class="header__icon" viewBox="0 0 24 24" aria-hidden="true">
                            <path
                                d="M18.244 2H21l-6.5 7.43L22 22h-6.84l-4.3-5.63L5.8 22H3l6.98-7.98L2 2h6.84l3.92 5.24L18.244 2Zm-2.4 18h2.02L8.26 4H6.24l9.6 16Z" />
                        </svg>
                        <span>X</span>
                    </a>
                    <button class="header__contract focusable" id="copy-contract" type="button"
                        data-address="GPwwihLa1w9Qsz27SmNrj7aLVnE7pr2cAvHe6aVVpump">
                        <span class="header__contract-badge">CA</span>
                        <span class="header__contract-code">GPww...VVpump</span>
                    </button>
                </nav>
            </header>

            <!-- ============== MAIN ============== -->
            <main class="main" aria-label="Contenido principal">
                <!-- Stats -->
                <section class="main__section">
                    <div class="stats">
                        <div class="stat-card stat-card--memes">
                            <div class="stat-card__label">Memes generated</div>
                            <div class="stat-card__value" id="stat-memes">12,340</div>
                            <div class="stat-card__hint">+124 today</div>
                        </div>
                        <div class="stat-card stat-card--cpu">
                            <div class="stat-card__label">CPU</div>
                            <div class="stat-card__value" id="stat-cpu">35%</div>
                            <div class="stat-card__bar">
                                <div class="stat-card__fill" id="stat-cpu-bar" style="width:35%"></div>
                            </div>
                            <div class="stat-card__hint">Dank energy</div>
                        </div>
                        <div class="stat-card stat-card--gpu">
                            <div class="stat-card__label">GPU</div>
                            <div class="stat-card__value" id="stat-gpu">62%</div>
                            <div class="stat-card__bar">
                                <div class="stat-card__fill" id="stat-gpu-bar" style="width:62%"></div>
                            </div>
                            <div class="stat-card__hint">rendering</div>
                        </div>
                        <div class="stat-card stat-card--tps">
                            <div class="stat-card__label">TPS (fake)</div>
                            <div class="stat-card__value" id="stat-tps">2,345</div>
                            <div class="stat-card__hint">Vibes</div>
                        </div>
                        <div class="stat-card stat-card--queue">
                            <div class="stat-card__label">Queue</div>
                            <div class="stat-card__value" id="stat-queue">0</div>
                            <div class="stat-card__hint">Low</div>
                        </div>
                        <div class="stat-card stat-card--uptime">
                            <div class="stat-card__label">Uptime</div>
                            <div class="stat-card__value" id="stat-uptime">00:00:00</div>
                            <div class="stat-card__hint">since boot</div>
                        </div>
                    </div>
                </section>

                <div class="myselfdone">
                    <!-- Reemplazo de la consola: Pizarra de Prompts -->
                    <!-- === Meme Maker (Konva) — replaces the old "Pizarra de Prompts" section === -->
                    <section class="main__section">
                        <article class="board meme-maker" aria-label="Meme Generator">
                            <div class="board__header">
                                <h3 class="board__title">Generate your own meme</h3>
                            </div>

                            <div class="board__body">
                                <!-- Controls -->
                                <div class="meme-maker__controls">
                                    <label class="meme-maker__control">
                                        <span class="meme-maker__label">Upload image</span>
                                        <input class="meme-maker__file" type="file" id="mm-file"
                                            accept="image/*" />
                                    </label>

                                    <label class="meme-maker__control">
                                        <span class="meme-maker__label">Caption</span>
                                        <input class="meme-maker__input" type="text" id="mm-caption"
                                            placeholder="Type your caption..." />
                                    </label>

                                    <label class="meme-maker__control">
                                        <span class="meme-maker__label">Font size</span>
                                        <input class="meme-maker__range" type="range" id="mm-font"
                                            min="16" max="64" value="32" />
                                    </label>

                                    <button class="meme-maker__button" id="mm-download" type="button"
                                        title="Download PNG">
                                        ⤓ Download
                                    </button>
                                </div>

                                <!-- Canvas container (responsive) -->
                                <div id="mm-stage-wrap" class="meme-maker__stage-wrap">
                                    <div id="mm-stage"></div>
                                </div>
                            </div>
                        </article>
                    </section>

                    <!-- Minimal styles specific to this widget (BEM and current theme vars) -->
                    <style>
                        .meme-maker__controls {
                            display: flex;
                            flex-wrap: wrap;
                            gap: 10px;
                            align-items: flex-end;
                            margin-bottom: 10px;
                        }

                        .meme-maker__control {
                            display: flex;
                            flex-direction: column;
                            gap: 6px;
                            min-width: 200px;
                        }

                        .meme-maker__label {
                            font-size: 12px;
                            color: var(--muted)
                        }

                        .meme-maker__file,
                        .meme-maker__input,
                        .meme-maker__range {
                            padding: 10px 0;
                            border-radius: 10px;
                            background: linear-gradient(180deg, rgba(113, 255, 151, .14), rgba(113, 255, 151, .06));
                            color: var(--text);
                            font-family: var(--font-mono);
                        }

                        .meme-maker__file {
                            padding: 8px;
                        }

                        .meme-maker__input {
                            min-width: 260px;
                        }

                        .meme-maker__range {
                            width: 180px;
                            padding: 6px 8px;
                        }

                        .meme-maker__button {
                            padding: 10px 14px;
                            border-radius: 10px;
                            cursor: pointer;
                            font-weight: 800;
                            border: 1px solid var(--border);
                            color: var(--text);
                            background: linear-gradient(180deg, rgba(113, 255, 151, .22), rgba(113, 255, 151, .08));
                            box-shadow: 0 0 0 rgba(0, 0, 0, 0);
                            transition: transform .12s ease;
                        }

                        .meme-maker__button:hover {
                            transform: translateY(-1px);
                        }

                        .meme-maker__stage-wrap {
                            width: 100%;
                            max-width: 900px;
                            /* roomy chart/canvas */
                            margin: 6px 0 0 0;
                            border: 1px dashed var(--border);
                            border-radius: var(--radius);
                            overflow: hidden;
                            background: #061108;
                        }
                    </style>

                    <!-- Konva.js (only include once on the page) -->
                    <script src="https://cdn.jsdelivr.net/npm/konva@9/konva.min.js"></script>
                    <script>
                        (function() {
                            // DOM
                            const wrap = document.getElementById('mm-stage-wrap');
                            const captionInput = document.getElementById('mm-caption');
                            const fileInput = document.getElementById('mm-file');
                            const fontRange = document.getElementById('mm-font');
                            const downloadBtn = document.getElementById('mm-download');

                            // Stage sizing: square image area + caption bar on top
                            let stage, layer, captionGroup, captionRect, captionText, imageGroup, imageNode;
                            let imgNatural = null; // {w,h} when an image is loaded

                            function initStage() {
                                // Compute widths responsively
                                const width = Math.min(wrap.clientWidth || 600, 900);
                                const capH = Math.round(Math.max(72, Math.min(140, width * 0.16))); // white bar height
                                const imgArea = {
                                    x: 0,
                                    y: capH,
                                    w: width,
                                    h: width
                                }; // square area

                                if (!stage) {
                                    stage = new Konva.Stage({
                                        container: 'mm-stage',
                                        width: width,
                                        height: capH + imgArea.h
                                    });
                                    layer = new Konva.Layer();
                                    stage.add(layer);

                                    // Caption group (white rect + black text)
                                    captionGroup = new Konva.Group({
                                        x: 0,
                                        y: 0,
                                        width: width,
                                        height: capH
                                    });
                                    captionRect = new Konva.Rect({
                                        x: 0,
                                        y: 0,
                                        width: width,
                                        height: capH,
                                        fill: '#ffffff'
                                    });
                                    captionText = new Konva.Text({
                                        x: 16,
                                        y: 0,
                                        width: width - 32,
                                        text: 'Buy $dank memes',
                                        align: 'center',
                                        verticalAlign: 'middle',
                                        fontFamily: 'Impact, "Arial Black", Arial, sans-serif',
                                        fontStyle: 'bold',
                                        fontSize: parseInt(fontRange.value, 10),
                                        fill: '#000000',
                                        listening: false
                                    });

                                    captionGroup.add(captionRect);
                                    captionGroup.add(captionText);
                                    layer.add(captionGroup);

                                    // Image group clipped to image area (to allow panning without overflow)
                                    imageGroup = new Konva.Group({
                                        x: imgArea.x,
                                        y: imgArea.y,
                                        clip: {
                                            x: 0,
                                            y: 0,
                                            width: imgArea.w,
                                            height: imgArea.h
                                        }
                                    });
                                    layer.add(imageGroup);
                                } else {
                                    // Resize stage & groups
                                    stage.size({
                                        width: width,
                                        height: capH + imgArea.h
                                    });
                                    captionGroup.size({
                                        width: width,
                                        height: capH
                                    });
                                    captionRect.size({
                                        width: width,
                                        height: capH
                                    });
                                    captionText.width(width - 32);
                                    captionGroup.position({
                                        x: 0,
                                        y: 0
                                    });

                                    imageGroup.position({
                                        x: imgArea.x,
                                        y: imgArea.y
                                    });
                                    imageGroup.clip({
                                        x: 0,
                                        y: 0,
                                        width: imgArea.w,
                                        height: imgArea.h
                                    });
                                }

                                captionInput.value = 'Buy $dank memes';
                                captionText.text(captionInput.value);
                                layoutCaption();
                                layoutImage();
                                layer.draw();
                            }

                            function loadDefaultImage(url) {
                                const img = new Image();
                                img.crossOrigin = 'anonymous'; // por si necesitas CORS en producción/CDN
                                img.onload = () => {
                                    imgNatural = {
                                        w: img.naturalWidth,
                                        h: img.naturalHeight
                                    };

                                    if (imageNode) {
                                        imageNode.destroy();
                                        imageNode = null;
                                    }

                                    imageNode = new Konva.Image({
                                        image: img,
                                        draggable: true
                                    });
                                    imageGroup.add(imageNode);
                                    layoutImage();
                                    layer.draw();
                                };
                                img.src = url;
                            }


                            function layoutCaption() {
                                if (!captionText) return;
                                const capH = captionGroup.height();
                                const maxH = capH - 20; // padding top/bottom
                                // Keep the user's chosen font size, but ensure the text fits vertically by reducing if needed.
                                let fs = parseInt(fontRange.value, 10);
                                captionText.fontSize(fs);

                                // Measure and shrink until fits
                                while (captionText.height() > maxH && fs > 10) {
                                    fs -= 1;
                                    captionText.fontSize(fs);
                                }

                                // Center vertically inside the bar
                                const y = (capH - captionText.height()) / 2;
                                captionText.y(Math.max(0, y));
                            }

                            function layoutImage() {
                                if (!imgNatural || !imageNode) return;
                                const areaW = imageGroup.clip().width;
                                const areaH = imageGroup.clip().height;

                                const scale = Math.min(areaW / imgNatural.w, areaH / imgNatural.h);
                                const newW = imgNatural.w * scale;
                                const newH = imgNatural.h * scale;

                                imageNode.size({
                                    width: newW,
                                    height: newH
                                });

                                // Center within the image area (sin sumar offset del grupo)
                                const cx = (areaW - newW) / 2;
                                const cy = (areaH - newH) / 2;
                                imageNode.position({
                                    x: cx,
                                    y: cy
                                });

                                // Drag bounds para que no se salga del área visible
                                imageNode.dragBoundFunc((pos) => {
                                    const minX = areaW - imageNode.width();
                                    const maxX = 0;
                                    const minY = areaH - imageNode.height();
                                    const maxY = 0;
                                    return {
                                        x: Math.max(minX, Math.min(pos.x, maxX)),
                                        y: Math.max(minY, Math.min(pos.y, maxY))
                                    };
                                });
                            }


                            // --- File upload ---
                            fileInput.addEventListener('change', (e) => {
                                const file = e.target.files && e.target.files[0];
                                if (!file) return;

                                const reader = new FileReader();
                                reader.onload = (evt) => {
                                    const img = new Image();
                                    img.onload = () => {
                                        imgNatural = {
                                            w: img.naturalWidth,
                                            h: img.naturalHeight
                                        };

                                        // Remove previous image node if any
                                        if (imageNode) {
                                            imageNode.destroy();
                                            imageNode = null;
                                        }

                                        imageNode = new Konva.Image({
                                            image: img,
                                            draggable: true
                                        });
                                        imageGroup.add(imageNode);
                                        layoutImage();
                                        layer.draw();
                                    };
                                    img.src = evt.target.result;
                                };
                                reader.readAsDataURL(file);
                            });

                            // --- Caption text ---
                            captionInput.addEventListener('input', () => {
                                captionText.text(captionInput.value);
                                layoutCaption();
                                layer.batchDraw();
                            });

                            // --- Font size ---
                            fontRange.addEventListener('input', () => {
                                layoutCaption();
                                layer.batchDraw();
                            });

                            // --- Download PNG (2x DPI) ---
                            downloadBtn.addEventListener('click', () => {
                                const dataURL = stage.toDataURL({
                                    pixelRatio: 2
                                });
                                const link = document.createElement('a');
                                link.download = 'meme.png';
                                link.href = dataURL;
                                link.click();
                            });

                            // --- Responsive: recalc on resize ---
                            window.addEventListener('resize', () => {
                                // debounce-ish
                                clearTimeout(window.__mm_rsz);
                                window.__mm_rsz = setTimeout(initStage, 120);
                            });

                            // Init
                            initStage();

                            loadDefaultImage('/assets/boot/base.png');

                        })();
                    </script>
                    <!-- Dexscreener amplio -->
                    <section class="main__section">
                        <article class="panel panel--dex">
                            <div class="panel__header">
                                <h3 class="panel__title">Dexscreener</h3>
                                <span style="color:var(--muted); font-size:12px">SOL • Live</span>
                            </div>
                            <div class="panel__body">
                                <style>
                                    #dexscreener-embed {
                                        position: relative;
                                        width: 100%;
                                        padding-bottom: 125%;
                                    }

                                    @media(min-width:1400px) {
                                        #dexscreener-embed {
                                            padding-bottom: 65%;
                                        }
                                    }

                                    #dexscreener-embed iframe {
                                        position: absolute;
                                        width: 100%;
                                        height: 100%;
                                        top: 0;
                                        left: 0;
                                        border: 0;
                                    }
                                </style>
                                <style>
                                    #dexscreener-embed {
                                        position: relative;
                                        width: 100%;
                                        padding-bottom: 125%;
                                    }

                                    @media(min-width:1400px) {
                                        #dexscreener-embed {
                                            padding-bottom: 65%;
                                        }
                                    }

                                    #dexscreener-embed iframe {
                                        position: absolute;
                                        width: 100%;
                                        height: 100%;
                                        top: 0;
                                        left: 0;
                                        border: 0;
                                    }
                                </style>
                                <style>
                                    #dexscreener-embed {
                                        position: relative;
                                        width: 100%;
                                        padding-bottom: 125%;
                                    }

                                    @media(min-width:1400px) {
                                        #dexscreener-embed {
                                            padding-bottom: 65%;
                                        }
                                    }

                                    #dexscreener-embed iframe {
                                        position: absolute;
                                        width: 100%;
                                        height: 100%;
                                        top: 0;
                                        left: 0;
                                        border: 0;
                                    }
                                </style>
                                <div id="dexscreener-embed"><iframe
                                        src="https://dexscreener.com/solana/4D1LDiENf5RkTJivGBvFbbUW4KPCNpyPRPnNJk1aT8z2?embed=1&loadChartSettings=0&trades=0&tabs=0&info=0&chartLeftToolbar=0&chartDefaultOnMobile=1&chartTheme=dark&theme=dark&chartStyle=0&chartType=usd&interval=15"></iframe>
                                </div>
                            </div>
                        </article>
                    </section>



                </div>
                <!-- Memes -->
                <section class="main__section">
                    <h3 class="memes__title">Memes generated</h3>
                    <div class="meme-grid">
                        <?php foreach ($items as $i => $item): ?>
                        <article class="meme-card">
                            <div class="meme-card__media">
                                <img class="meme-card__img" src="<?= htmlspecialchars($item['image']) ?>"
                                    alt="Meme <?= $i ?>">
                            </div>
                            <div class="meme-card__caption">
                                <p class="meme-card__text">“<?= htmlspecialchars($item['caption']) ?>”</p>
                                <div class="meme-card__actions">
                                    <a class="icon-btn focusable" href="<?= htmlspecialchars($item['image']) ?>"
                                        download aria-label="Descargar meme <?= $i ?>" title="Descargar">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 16L7 11L8.4 9.55L11 12.15V4H13V12.15L15.6 9.55L17 11L12 16ZM4 20V15H6V18H18V15H20V20H4Z"
                                                fill="#9CFFD1" />
                                        </svg>

                                    </a>
                                    @php
                                        $baseUrl =
                                            (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'
                                                ? 'https'
                                                : 'http') . "://{$_SERVER['HTTP_HOST']}";
                                    @endphp

                                    <a class="icon-btn focusable share-x"
                                        data-text="Another cursed creation from the Dank Terminal: {{ htmlspecialchars($item['caption']) }}"
                                        data-url="{{ $baseUrl . $item['image'] }}" href="#"
                                        aria-label="Compartir en X" title="Compartir en X">
                                        <svg class="icon-btn__svg" viewBox="0 0 24 24" aria-hidden="true">
                                            <path
                                                d="M18.244 2H21l-6.5 7.43L22 22h-6.84l-4.3-5.63L5.8 22H3l6.98-7.98L2 2h6.84l3.92 5.24L18.244 2Zm-2.4 18h2.02L8.26 4H6.24l9.6 16Z" />
                                        </svg>
                                    </a>

                                </div>
                            </div>
                        </article>
                        <?php endforeach; ?>
                    </div>
                </section>


            </main>
            <footer class="terminal-footer" role="contentinfo">
                <div class="terminal-footer__col">
                    <span>© 2025 Dank Terminal</span>
                </div>

                <div class="terminal-footer__col">
                    <a href="https://x.com/Eserya77" target="_blank" rel="noopener" class="terminal-footer__link">
                        X / @Eserya77
                    </a>
                </div>

                <div class="terminal-footer__col terminal-footer__donate">
                    <span class="terminal-footer__donate-label">Help support the terminal:</span>
                    <button class="terminal-footer__wallet" type="button">
                        3kYY3piES6rjjAmzTBtjgxW5sEc4k5L2L3VsLPB7FbqL
                    </button>
                </div>
            </footer>
        </div>

    </div>




    <div class="toast" id="toast" role="status" aria-live="polite">Copied!</div>
    <script>
        // Copy wallet on click
        (function() {
            const btn = document.querySelector('.terminal-footer__wallet');
            if (!btn) return;
            const addr = btn.textContent.trim();
            btn.addEventListener('click', async () => {
                try {
                    await navigator.clipboard.writeText(addr);
                    const old = btn.textContent;
                    btn.textContent = 'Copied!';
                    setTimeout(() => (btn.textContent = old), 1100);
                } catch (e) {
                    // fallback selection
                    const r = document.createRange();
                    r.selectNodeContents(btn);
                    const sel = window.getSelection();
                    sel.removeAllRanges();
                    sel.addRange(r);
                }
            });
        })();
    </script>

    <script>
        // ======== Toast ========
        const toastEl = document.getElementById('toast');

        function showToast(text = "Copied!") {
            if (!toastEl) return;
            toastEl.textContent = text;
            toastEl.classList.add('toast--show');
            setTimeout(() => toastEl.classList.remove('toast--show'), 1400);
        }
        async function copyText(text) {
            try {
                await navigator.clipboard.writeText(text);
                showToast();
            } catch (e) {
                alert('No se pudo copiar.');
            }
        }

        // ======== Header: copy CA ========
        (function() {
            const btn = document.getElementById('copy-contract');
            if (!btn) return;
            const addr = btn.getAttribute('data-address');
            btn.addEventListener('click', async () => {
                await copyText(addr);
                const codeEl = btn.querySelector('.header__contract-code');
                const original = codeEl.textContent;
                codeEl.textContent = '✓ Copied';
                setTimeout(() => codeEl.textContent = original, 900);
            });
        })();

        // ======== Stats fake data ========
        (function() {
            const cpuBar = document.getElementById('stat-cpu-bar');
            const cpuTxt = document.getElementById('stat-cpu');
            const gpuBar = document.getElementById('stat-gpu-bar');
            const gpuTxt = document.getElementById('stat-gpu');
            const memesTxt = document.getElementById('stat-memes');
            const uptimeTxt = document.getElementById('stat-uptime');
            const queueTxt = document.getElementById('stat-queue');
            const tpsTxt = document.getElementById('stat-tps');
            const start = Date.now();
            let memes = 12340,
                queue = 0;

            function pad(n) {
                return String(n).padStart(2, '0');
            }

            setInterval(() => {
                // CPU 18..92%
                const t = (Date.now() - start) / 1000;
                const base = 55 + 35 * Math.sin(t * 1.1);
                const jitter = (Math.random() - .5) * 8;
                let cpu = Math.max(18, Math.min(92, Math.round(base + jitter)));
                cpuBar.style.width = cpu + '%';
                cpuTxt.textContent = cpu + '%';

                // GPU 22..88%
                const gbase = 55 + 30 * Math.sin(t * 0.9 + 1.2);
                const gj = (Math.random() - .5) * 6;
                let gpu = Math.max(22, Math.min(88, Math.round(gbase + gj)));
                if (gpuBar) gpuBar.style.width = gpu + '%';
                if (gpuTxt) gpuTxt.textContent = gpu + '%';

                // Memes counter + TPS
                memes += Math.random() < 0.6 ? 1 : 0;
                memesTxt.textContent = memes.toLocaleString();
                const tps = 1800 + Math.floor(900 * Math.abs(Math.sin(t * 0.7)));
                if (tpsTxt) tpsTxt.textContent = tps.toLocaleString();

                // Queue
                queue = Math.max(0, (Math.random() < 0.3 ? queue + 1 : queue - 1));
                queueTxt.textContent = queue;

                // Uptime
                const secs = Math.floor((Date.now() - start) / 1000);
                const h = Math.floor(secs / 3600);
                const m = Math.floor((secs % 3600) / 60);
                const s = secs % 60;
                uptimeTxt.textContent = `${pad(h)}:${pad(m)}:${pad(s)}`;
            }, 900);
        })();

        // ======== Board actions (copy / tweet / shuffle) ========
        (function() {
            function openTweet(text, url) {
                const intent =
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}${url ? '&url='+encodeURIComponent(url) : ''}`;
                window.open(intent, '_blank', 'noopener');
            }
            document.querySelectorAll('.copy').forEach(btn => {
                btn.addEventListener('click', () => copyText(btn.getAttribute('data-copy') || ''));
            });
            document.querySelectorAll('.tweet').forEach(btn => {
                btn.addEventListener('click', () => openTweet(btn.getAttribute('data-text') || ''));
            });
            const pool = [
                'gato neon hackea tostadora; punchline: "sudo toast -FOMO"',
                'ping a la luna — traceroute: comunidad',
                'matrix lluvia verde, sapo smug firmando bloques',
                'terminal vaporwave pide café; return code: 200 ☕',
                'devops corrigiendo YAML con ouija 🔮',
                'solana surfer sobre velas 1m, brillo neon'
            ];
            const list = document.getElementById('prompt-list');
            const shuffleBtn = document.getElementById('shuffle-prompts');

            function renderRandom() {
                if (!list) return;
                list.innerHTML = '';
                const shuffled = pool.sort(() => Math.random() - .5).slice(0, 3);
                for (const text of shuffled) {
                    const item = document.createElement('div');
                    item.className = 'board__item';
                    item.innerHTML = `
            <div class="board__text">${text}</div>
            <div class="board__actions">
              <button class="btn focusable copy" data-copy="${text.replaceAll('"','&quot;')}">
                <svg class="btn__icon" viewBox="0 0 24 24"><path d="M16 1H4c-1.1 0-2 .9-2 2v12h2V3h12V1Zm3 4H8c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h11c1.1 0 2-.9 2-2V7c0-1.1-.9-2-2-2Zm0 16H8V7h11v14Z"/></svg>
              </button>
              <button class="btn focusable tweet" data-text="${text.replaceAll('"','&quot;')}">
                <svg class="btn__icon" viewBox="0 0 24 24"><path d="M18.244 2H21l-6.5 7.43L22 22h-6.84l-4.3-5.63L5.8 22H3l6.98-7.98L2 2h6.84l3.92 5.24L18.244 2Zm-2.4 18h2.02L8.26 4H6.24l9.6 16Z"/></svg>
              </button>
            </div>`;
                    list.appendChild(item);
                }
                // rebind events
                list.querySelectorAll('.copy').forEach(btn => {
                    btn.addEventListener('click', () => copyText(btn.getAttribute('data-copy') || ''));
                });
                list.querySelectorAll('.tweet').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const t = btn.getAttribute('data-text') || '';
                        openTweet(t);
                    });
                });
            }
            if (shuffleBtn) {
                shuffleBtn.addEventListener('click', renderRandom);
            }
        })();

        // ======== Share on X desde cards ========
        (function() {
            function openTweet(text, url) {
                const intent =
                    `https://twitter.com/intent/tweet?text=${encodeURIComponent(text)}&url=${encodeURIComponent(url)}`;
                window.open(intent, '_blank', 'noopener');
            }
            document.querySelectorAll('.share-x').forEach(el => {
                el.addEventListener('click', (e) => {
                    e.preventDefault();
                    const text = el.getAttribute('data-text') || 'Meme';
                    const url = el.getAttribute('data-url') || location.href;
                    openTweet(text, url);
                });
            });
        })();
    </script>
</body>

</html>
