@php use Illuminate\Support\Str; @endphp
<!DOCTYPE html>
<html lang="en" x-data="{ showModal: false, editId: null, editDesc: '' }">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Manage Memes — Dank Terminal</title>
    @alpine
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

        /* Header (kept minimal, no extra elements) */
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

        /* Shell */
        .wrap {
            max-width: 1200px;
            margin: 24px auto;
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
            padding: 14px;
        }

        /* Buttons */
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
            border-color: color-mix(in oklch, var(--accent) 40%, var(--line));
            color: #06130f;
            background: linear-gradient(180deg, color-mix(in oklch, var(--accent) 75%, #fff 0%), color-mix(in oklch, var(--accent) 55%, #000 0%))
        }

        .btn--danger {
            border-color: color-mix(in oklch, var(--danger) 50%, var(--line));
            color: #ffd7de
        }

        /* Table */
        .tbl {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            font-size: 13px
        }

        .tbl thead th {
            text-align: left;
            padding: 12px;
            color: var(--muted);
            font-weight: 600;
            border-bottom: 1px dashed var(--line-2);
            letter-spacing: .06em;
        }

        .tbl tbody td {
            padding: 12px;
            border-bottom: 1px solid var(--line)
        }

        .tbl tr:hover td {
            background: rgba(17, 39, 31, .35)
        }

        .id {
            color: var(--accent)
        }

        .row-actions {
            display: flex;
            gap: 8px;
            align-items: center
        }

        /* Modal */
        .modal-back {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, .7);
            z-index: 50
        }

        .modal {
            position: fixed;
            inset: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 60;
            padding: 16px
        }

        .modal__card {
            width: min(680px, 96vw);
            background: linear-gradient(180deg, var(--bg-1), var(--bg-2));
            border: 1px solid var(--line-2);
            border-radius: 18px;
            box-shadow: 0 0 0 1px var(--line) inset, 0 0 60px rgba(113, 255, 151, .12);
            padding: 18px;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 8px
        }

        .input,
        .textarea {
            width: 100%;
            background: #0a1411;
            color: var(--text);
            border: 1px solid var(--line);
            border-radius: 10px;
            padding: 10px 12px;
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

        /* Tiny utilities */
        .muted {
            color: var(--muted)
        }

        .toolbar {
            display: flex;
            gap: 10px;
            align-items: center
        }

        .spacer {
            height: 18px
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
    <!-- Minimal header (no extra widgets) -->
    <header class="hdr">
        <div class="brand">
            <span class="brand__dot"></span>
            <span class="brand__name">DANK TERMINAL / <span class="muted">Manage Memes</span></span>
        </div>
        <a class="btn btn--accent" href="/dashboard">Back to Upload</a>
    </header>

    <main class="wrap">
        <section class="card">
            <div class="card__hd">
                <span>Memes</span>
                <span class="muted">Total: {{ number_format($memes->count()) }}</span>
            </div>
            <div class="card__bd" style="overflow:auto;">
                <table class="tbl">
                    <thead>
                        <tr>
                            <th style="width:80px;">ID</th>
                            <th>Description</th>
                            <th style="width:220px;">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($memes as $meme)
                            <tr>
                                <td class="id">#{{ $meme->id }}</td>
                                <td>{{ Str::limit($meme->description, 120) }}</td>
                                <td>
                                    <div class="row-actions">
                                        <button class="btn"
                                            @click="editId = {{ $meme->id }}; editDesc = '{{ addslashes($meme->description) }}'; showModal = true">
                                            Edit
                                        </button>

                                        <form method="POST" action="/memes/delete"
                                            onsubmit="return confirm('Delete this meme?')">
                                            @csrf
                                            <input type="hidden" name="id" value="{{ $meme->id }}">
                                            <button class="btn btn--danger" type="submit">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <div class="spacer"></div>
    </main>

    <!-- Modal (same functionality, restyled) -->
    <div x-show="showModal" x-transition>
        <div class="modal-back" @click="showModal=false"></div>
        <div class="modal" x-trap.noscroll="showModal">
            <div class="modal__card" @keydown.escape.window="showModal=false">
                <div class="toolbar" style="justify-content:space-between; margin-bottom:10px;">
                    <strong>Edit Description</strong>
                    <button class="btn" @click="showModal=false">Close</button>
                </div>
                <form method="POST" action="/memes/update-description">
                    @csrf
                    <input type="hidden" name="id" :value="editId">
                    <div class="field" style="margin-bottom:12px;">
                        <label class="muted" for="desc">Description</label>
                        <textarea id="desc" name="description" class="textarea" x-model="editDesc"></textarea>
                    </div>
                    <div class="toolbar" style="justify-content:flex-end;">
                        <button type="submit" class="btn btn--accent">Save</button>
                        <button type="button" class="btn" @click="showModal=false">Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
