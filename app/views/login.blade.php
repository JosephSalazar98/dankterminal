<!DOCTYPE html>
<html lang="en" x-data="memeGenerator()">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dank Terminal</title>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="icon" type="image/png" href="/fav.png">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/dark.css">
</head>

<body>


    <form method="POST" action="/login">
        <input name="email" type="text" placeholder="email" /><br>
        <input name="password" type="password" placeholder="password" /><br>
        <button type="submit">Login</button>
    </form>

</body>

</html>
