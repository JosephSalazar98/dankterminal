<?php

namespace App\Controllers;

use App\Models\Meme;
use GuzzleHttp\Client;
use App\Models\Caption;
use App\Utils\VectorUtils;
use App\Services\OpenAIService;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Services\MemeMatcher;
use App\Services\MemeRenderer;


class EmbedsController extends Controller
{
    protected string $baseUrl;

    public function __construct()
    {
        parent::__construct();
        $this->baseUrl = rtrim(_env('APP_URL'), '/');
    }


    public function generateEmbeddings()
    {
        $service = new OpenAIService();
        $memes = Meme::whereNull('embedding')->get();

        foreach ($memes as $meme) {
            $embedding = $service->getEmbedding($meme->description);
            if (!$embedding) continue;

            $meme->embedding = json_encode($embedding);
            $meme->save();
        }

        response()->json([
            'message' => 'Embeddings generated',
            'count' => count($memes)
        ]);
    }


    public function matchPrompt()
    {
        $prompt = request()->get('prompt');
        if (!$prompt) {
            response()->json(['error' => 'No prompt provided'], 400);
            return;
        }

        $openAI = new OpenAIService();
        $promptEmbedding = $openAI->getEmbedding($prompt);
        if (!$promptEmbedding) {
            response()->json(['error' => 'Failed to generate prompt embedding'], 500);
            return;
        }

        $matcher = new MemeMatcher();
        $match = $matcher->findBestMatch($promptEmbedding);
        if (!$match) {
            response()->json(['error' => 'No memes with embeddings found'], 404);
            return;
        }
        $bestMeme = $match['meme'];
        $bestScore = $match['score'];


        $caption = $openAI->generateCaption($prompt, $bestMeme->description);

        response()->json([
            'message' => 'Best matching meme found',
            'meme' => $bestMeme,
            'caption' => $caption,
            'score' => $bestScore
        ]);
    }

    public function generateFinalMeme()
    {
        $prompt = request()->get('prompt');

        if (!$prompt) {
            response()->json(['error' => 'No prompt provided'], 400);
            return;
        }

        $openAI = new OpenAIService();
        $embedding = $openAI->getEmbedding($prompt);

        $matcher = new MemeMatcher();
        $match = $matcher->findBestMatch($embedding);

        if (!$match) {
            response()->json(['error' => 'No matching meme'], 404);
            return;
        }

        $bestMeme = $match['meme'];
        $bestScore = $match['score'];


        $caption = $openAI->generateCaption($prompt, $bestMeme->description);

        $renderer = new MemeRenderer();
        $outputPath = $renderer->render(__DIR__ . '/../../public' . $bestMeme->image_path, $caption);

        Caption::create([
            'meme_id' => $bestMeme->id,
            'caption' => $caption
        ]);


        response()->json([
            'image_url' => $this->baseUrl . '/generated/' . basename($outputPath),
            'caption' => $caption,
            'meme_id' => $bestMeme->id,
            'score' => $bestScore
        ]);
    }

    public function generateFromRandom()
    {
        $meme = Meme::inRandomOrder()->whereNotNull('embedding')->first();

        if (!$meme) {
            response()->json(['error' => 'No memes with embeddings'], 404);
            return;
        }

        $openAI = new OpenAIService();
        $renderer = new MemeRenderer();

        $caption = $openAI->generateCaption($meme->description, $meme->description);
        $outputPath = $renderer->render(__DIR__ . '/../../public' . $meme->image_path, $caption);

        Caption::create([
            'meme_id' => $meme->id,
            'caption' => $caption
        ]);

        $baseUrl = rtrim(_env('APP_URL'), '/');

        response()->json([

            'image_url' => $this->baseUrl . '/generated/' . basename($outputPath),
            'caption' => $caption,
            'meme_id' => $meme->id
        ]);
    }

    public function showMeme($id)
    {
        $meme = Meme::findOrFail($id);

        $perPage = 12;
        $currentPage = request()->get('page', 1);

        $allCaptions = $meme->captions()->get()->toArray();
        $total = count($allCaptions);
        $offset = ($currentPage - 1) * $perPage;
        $items = array_slice($allCaptions, $offset, $perPage);

        $paginator = new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $currentPage,
            [
                'path' => "/meme/$id",
                'query' => request()->query(),
            ]
        );

        return render('meme', [
            'meme' => $meme,
            'captions' => $paginator,
        ]);
    }




    public function generateCreative()
    {
        $imageId = request()->input('image_id');

        if (is_array($imageId)) {
            $imageId = array_filter($imageId);
            $imageId = reset($imageId);
        }

        if (!empty($imageId)) {
            return $this->generateFromImageId($imageId);
        } else {
            return $this->generateFromRandom();
        }
    }


    public function generateFromImageId($imageId)
    {
        $meme = Meme::find($imageId);

        if (!$meme || !$meme->embedding) {
            response()->json(['error' => 'Meme not found or missing embedding'], 404);
            return;
        }

        $openAI = new OpenAIService();
        $renderer = new MemeRenderer();

        $caption = $openAI->generateCaption($meme->description, $meme->description);
        $outputPath = $renderer->render(__DIR__ . '/../../public' . $meme->image_path, $caption);

        Caption::create([
            'meme_id' => $meme->id,
            'caption' => $caption
        ]);

        response()->json([
            'image_url' => $this->baseUrl . '/generated/' . basename($outputPath),
            'caption' => $caption,
            'meme_id' => $meme->id
        ]);
    }

    public function uploadMeme()
    {
        $file = request()->files('image');
        $desc = request()->get('description');

        if (!$file || $file['error'] !== 0) {
            echo "There was an error uploading the file.";
            return;
        }

        $filename = uniqid() . '.' . pathinfo($file['name'], PATHINFO_EXTENSION);
        $path = __DIR__ . '/../../public/assets/img/' . $filename;
        move_uploaded_file($file['tmp_name'], $path);

        $meme = new Meme();
        $meme->title = $filename;
        $meme->category = "";
        $meme->image_path = "/assets/img/$filename";
        $meme->description = $desc;
        $meme->save();

        $openAI = new OpenAIService();
        $embedding = $openAI->getEmbedding($desc);
        $meme->embedding = json_encode($embedding);
        $meme->save();

        echo "Uploaded successfully. <a href='/dashboard'>Return to dashboard</a>";
    }
}
