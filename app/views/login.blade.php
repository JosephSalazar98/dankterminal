<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Dank Terminal — Login</title>
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
            display: grid;
            place-items: center;
            padding: 24px;
        }

        .auth {
            width: min(440px, 100%);
            background: linear-gradient(180deg, var(--bg-1), var(--bg-2));
            border: 1px solid var(--line);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }

        .auth__hd {
            padding: 14px 16px;
            border-bottom: 1px solid var(--line);
            color: var(--muted);
            font-size: 12px;
            letter-spacing: .08em;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .auth__dot {
            width: 10px;
            height: 10px;
            border-radius: 999px;
            background: var(--accent);
            box-shadow: 0 0 14px rgba(113, 255, 151, .8)
        }

        .auth__bd {
            padding: 18px
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-bottom: 14px
        }

        .label {
            color: var(--muted);
            font-size: 12px;
            letter-spacing: .06em;
            text-transform: uppercase
        }

        .input {
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

        .input:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 1px var(--accent) inset, 0 0 30px rgba(113, 255, 151, .18)
        }

        .btn {
            appearance: none;
            border: 1px solid #2fb86a;
            color: #06130f;
            background: linear-gradient(180deg, #71ff97, #2fd26e);
            padding: 11px 14px;
            border-radius: 999px;
            font-size: 13px;
            line-height: 1;
            cursor: pointer;
            box-shadow: 0 0 0 1px #2fb86a inset, 0 0 24px rgba(113, 255, 151, .18);
            width: 100%;
        }

        .btn:hover {
            filter: brightness(1.02)
        }

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
    <section class="auth">
        <div class="auth__hd">
            <span class="auth__dot"></span>
            <span>DANK TERMINAL / <span style="color:var(--muted)">Login</span></span>
        </div>
        <div class="auth__bd">
            <form method="POST" action="/login">
                <!-- If this runs in Blade, uncomment @csrf -->
                <!-- @csrf -->
                <div class="field">
                    <label class="label" for="email">Email</label>
                    <input class="input" id="email" name="email" type="text" placeholder="email" required />
                </div>
                <div class="field">
                    <label class="label" for="password">Password</label>
                    <input class="input" id="password" name="password" type="password" placeholder="password"
                        required />
                </div>
                <button class="btn" type="submit">Login</button>
            </form>
        </div>
    </section>
</body>

</html>
