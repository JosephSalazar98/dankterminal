<?php

namespace App\Services;

use App\Models\Meme;
use App\Utils\VectorUtils;

class MemeMatcher
{
    protected float $lastScore = -1;

    public function findBestMatch(array $promptEmbedding): ?array
    {
        $bestScore = -1;
        $bestMeme = null;

        foreach (Meme::all() as $meme) {
            if (!$meme->embedding) continue;

            $memeEmbedding = json_decode($meme->embedding, true);
            $score = VectorUtils::cosineSimilarity($promptEmbedding, $memeEmbedding);

            if ($score > $bestScore) {
                $bestScore = $score;
                $bestMeme = $meme;
            }
        }

        if ($bestMeme) {
            $this->lastScore = $bestScore;
            return ['meme' => $bestMeme, 'score' => $bestScore];
        }

        return null;
    }

    public function getLastScore(): float
    {
        return $this->lastScore;
    }
}
