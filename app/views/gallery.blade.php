<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dank Terminal</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/alpinejs" defer></script>
    <link rel="icon" type="image/png" href="/fav.png">

</head>

<body class="bg-black text-green-400 font-mono min-h-screen p-2 md:p-12">
    <div class="max-w-full md:max-w-3xl mx-auto items-center content-center">
        @include('header')


        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-6">
            @foreach ($images as $image)
                <a href="/meme/{{ $image['id'] }}">
                    <img src="{{ $image['image_path'] }}" alt="Image {{ $image['id'] }}"
                        class="w-full h-auto rounded shadow">
                </a>
            @endforeach
        </div>

        <div class="flex justify-center items-center gap-2 py-6">
            @if ($images->onFirstPage())
                <span class="text-gray-400 cursor-not-allowed px-4 py-2">Previous</span>
            @else
                <a href="{{ $images->previousPageUrl() }}" class="text-gray-600 hover:text-black px-4 py-2">Previous</a>
            @endif

            @for ($page = 1; $page <= $images->lastPage(); $page++)
                @if ($page == $images->currentPage())
                    <span class="font-bold px-3 py-2 bg-gray-200 rounded">{{ $page }}</span>
                @else
                    <a href="{{ $images->url($page) }}" class="px-3 py-2 hover:underline">{{ $page }}</a>
                @endif
            @endfor

            @if ($images->hasMorePages())
                <a href="{{ $images->nextPageUrl() }}" class="text-gray-600 hover:text-black px-4 py-2">Next</a>
            @else
                <span class="text-gray-400 cursor-not-allowed px-4 py-2">Next</span>
            @endif
        </div>





</body>

</html>
