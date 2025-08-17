<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dank Terminal — Upload</title>
    <link rel="icon" type="image/png" href="/fav.png" />
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --bg-0: #0a0f0e;
            --bg-1: #0d1513;
            --bg-2: #0f1a17;
            --line: #173c2f;
            --line-2: #215943;
            --text: #d7fbe2;
            --muted: #8fb0a2;
            --accent: #71FF97;
            --danger: #ff5c7c;
            --radius: 16px;
            --shadow: 0 0 0 1px var(--line) inset, 0 0 24px rgba(113, 255, 151, .05);
            --glow: 0 0 0 1px var(--line-2) inset, 0 0 40px rgba(113, 255, 151, .12);
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            height: 100%
        }

        body {
            margin: 0;
            color: var(--text);
            font-family: "JetBrains Mono", ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            background:
                radial-gradient(1200px 800px at 80% -10%, rgba(113, 255, 151, .08), transparent 60%),
                radial-gradient(800px 600px at -10% 110%, rgba(33, 247, 197, .07), transparent 50%),
                var(--bg-0);
        }

        /* Header (minimal, no extra widgets) */
        .hdr {
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            padding: 14px 18px;
            background: linear-gradient(180deg, rgba(8, 12, 11, .9), rgba(8, 12, 11, .7));
            border-bottom: 1px solid var(--line);
            box-shadow: 0 10px 30px rgba(0, 0, 0, .35);
            backdrop-filter: saturate(120%) blur(6px);
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 700;
            letter-spacing: .08em;
        }

        .brand__dot {
            width: 12px;
            height: 12px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 16px rgba(113, 255, 151, .8);
        }

        .brand__name {
            font-size: 14px;
            color: var(--text)
        }

        .btn {
            appearance: none;
            border: 1px solid var(--line);
            color: var(--text);
            background: linear-gradient(180deg, var(--bg-1), var(--bg-2));
            padding: 9px 12px;
            border-radius: 999px;
            font-size: 12px;
            line-height: 1;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            box-shadow: var(--shadow);
        }

        .btn:hover {
            border-color: var(--line-2);
            box-shadow: var(--glow)
        }

        .btn--accent {
            border-color: #2fb86a;
            color: #06130f;
            background: linear-gradient(180deg, #71ff97, #2fd26e);
            box-shadow: 0 0 0 1px #2fb86a inset, 0 0 24px rgba(113, 255, 151, .18);
        }

        /* Shell */
        .wrap {
            max-width: 820px;
            margin: 28px auto;
            padding: 0 18px;
        }

        .card {
            background: linear-gradient(180deg, var(--bg-1), var(--bg-2));
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .card__hd {
            padding: 12px 14px;
            border-bottom: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .08em;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .card__bd {
            padding: 16px;
        }

        /* Form */
        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 14px;
        }

        .label {
            color: var(--muted);
            font-size: 12px;
            letter-spacing: .06em;
            text-transform: uppercase;
        }

        .input,
        .textarea {
            width: 100%;
            background: #0a1411;
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 12px;
            padding: 12px;
            font-family: inherit;
            font-size: 13px;
            box-shadow: var(--shadow)
        }

        .input:focus,
        .textarea:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 1px var(--accent) inset, 0 0 30px rgba(113, 255, 151, .18)
        }

        .textarea {
            min-height: 140px;
            resize: vertical
        }

        /* Pretty file input without extra elements */
        input[type="file"] {
            padding: 10px;
            cursor: pointer;
            color: var(--muted);
        }

        input[type="file"]::file-selector-button {
            margin-right: 10px;
            padding: 8px 10px;
            border: 1px solid var(--line);
            border-radius: 999px;
            background: linear-gradient(180deg, var(--bg-1), var(--bg-2));
            color: var(--text);
            box-shadow: var(--shadow);
            cursor: pointer;
            font-family: inherit;
            font-size: 12px;
        }

        input[type="file"]::file-selector-button:hover {
            border-color: var(--line-2);
            box-shadow: var(--glow)
        }

        /* Scrollbar */
        *::-webkit-scrollbar {
            height: 10px;
            width: 10px
        }

        *::-webkit-scrollbar-thumb {
            background: linear-gradient(180deg, var(--accent), #21f7c5);
            border-radius: 999px
        }

        *::-webkit-scrollbar-track {
            background: #0b1714
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header class="hdr">
        <div class="brand">
            <span class="brand__dot"></span>
            <span class="brand__name">DANK TERMINAL / <span style="color:var(--muted)">Upload new meme</span></span>
        </div>
        <a class="btn btn--accent" href="/logout">Log out</a>
    </header>

    <main class="wrap">
        <section class="card">
            <div class="card__hd">
                <span>Upload</span>
            </div>
            <div class="card__bd">
                <form method="POST" action="/upload" enctype="multipart/form-data">
                    <!-- If this is a Blade view, uncomment the next line -->
                    <!-- @csrf -->
                    <div class="field">
                        <label class="label" for="img">Image</label>
                        <input class="input" id="img" type="file" name="image" required />
                    </div>

                    <div class="field">
                        <label class="label" for="desc">Description</label>
                        <textarea class="textarea" id="desc" name="description" placeholder="description" required></textarea>
                    </div>

                    <button class="btn btn--accent" type="submit">Upload</button>
                </form>
            </div>
        </section>
    </main>
</body>

</html>
