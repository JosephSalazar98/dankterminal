<?php

namespace App\Controllers;

use App\Models\Meme;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\File;
use Illuminate\Pagination\LengthAwarePaginator;

class GalleriesController extends Controller
{
    public function index()
    {
        response()->render('gallery');
    }

    public function showGallery()
    {
        $perPage = 12;
        $currentPage = request()->get('page', 1);

        $query = Meme::select(['id', 'image_path']);
        $total = $query->count();

        $images = $query
            ->orderBy('id', 'desc')
            ->skip(($currentPage - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->toArray();

        $paginator = new LengthAwarePaginator(
            $images,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => '/gallery',
                'query' => request()->query(),
            ]
        );

        return render('gallery', ['images' => $paginator]);
    }
}
