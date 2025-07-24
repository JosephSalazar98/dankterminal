<?php

namespace App\Utils;

class VectorUtils
{
    public static function cosineSimilarity(array $a, array $b): float
    {
        $dot = $normA = $normB = 0;
        for ($i = 0; $i < count($a); $i++) {
            $dot += $a[$i] * $b[$i];
            $normA += $a[$i] ** 2;
            $normB += $b[$i] ** 2;
        }
        return $dot / (sqrt($normA) * sqrt($normB));
    }
}
