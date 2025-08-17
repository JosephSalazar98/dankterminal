<?php

namespace App\Controllers;

use App\Models\Meme;

class DashboardController extends Controller
{
    public function show()
    {
        // Trae los memes con sus captions
        $memes = Meme::with('captions')->get();

        // Construir una lista "aplanada" de pares (meme + caption)
        $items = [];
        foreach ($memes as $meme) {
            foreach ($meme->captions as $caption) {
                $items[] = [
                    'image'   => $meme->image_path,
                    'caption' => $caption->caption,
                ];
            }
        }

        // Mezclar todo para que no se repitan seguidos
        shuffle($items);

        response()->render('index', ['items' => $items]);
    }

    public function upload()
    {
        response()->render('dashboard');
    }

    public function manage()
    {
        $memes = \App\Models\Meme::orderBy('id', 'desc')->get();
        return render('manage', ['memes' => $memes]);
    }

    public function updateDescription()
    {
        $id = request()->get('id');
        $desc = request()->get('description');

        $meme = \App\Models\Meme::find($id);
        if ($meme) {
            $meme->description = $desc;
            $meme->save();
        }

        response()->redirect('/manage-memes');
    }

    public function delete()
    {
        $id = request()->get('id');
        $meme = \App\Models\Meme::find($id);

        if ($meme) {
            $imagePath = __DIR__ . '/../../public' . $meme->image_path;
            if (file_exists($imagePath)) unlink($imagePath);
            $meme->delete();
        }

        response()->redirect('/manage-memes');
    }
}
