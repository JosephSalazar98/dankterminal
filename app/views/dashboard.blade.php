<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dank Terminal</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="icon" type="image/png" href="/fav.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
</head>

<body>
    <h2>Upload new meme</h2>
    <form method="POST" action="/upload" enctype="multipart/form-data">
        <input type="file" name="image" required /><br>
        <textarea name="description" placeholder="description" required></textarea><br>
        <button>Upload</button>
    </form>
    <a href="/logout">Log out</a>
</body>

</html>
