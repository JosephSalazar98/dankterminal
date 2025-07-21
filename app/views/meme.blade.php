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



        <div class="p-6">
            <img src="{{ $meme['image_path'] }}" alt="Meme" class="w-full max-w-md rounded shadow mb-6">
        </div>

        @if ($captions->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 p-6">
                @foreach ($captions as $caption)
                    <div class="bg-gray-100 p-4 rounded shadow">
                        <p class="text-sm text-gray-800">{{ $caption['caption'] }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center items-center gap-2 py-6">
                @if ($captions->onFirstPage())
                    <span class="text-gray-400 cursor-not-allowed px-4 py-2">Previous</span>
                @else
                    <a href="{{ $captions->previousPageUrl() }}"
                        class="text-gray-600 hover:text-black px-4 py-2">Previous</a>
                @endif

                @for ($page = 1; $page <= $captions->lastPage(); $page++)
                    @if ($page == $captions->currentPage())
                        <span class="font-bold px-3 py-2 bg-gray-200 rounded">{{ $page }}</span>
                    @else
                        <a href="{{ $captions->url($page) }}" class="px-3 py-2 hover:underline">{{ $page }}</a>
                    @endif
                @endfor

                @if ($captions->hasMorePages())
                    <a href="{{ $captions->nextPageUrl() }}" class="text-gray-600 hover:text-black px-4 py-2">Next</a>
                @else
                    <span class="text-gray-400 cursor-not-allowed px-4 py-2">Next</span>
                @endif
            </div>
        @else
            <div class="p-6 text-center text-gray-500 text-lg italic">No captions yet.</div>
        @endif



</body>

</html>
