<?php

namespace App\Utils;

class TextUtils
{
    public static function wrapText(string $text, string $fontPath, int $fontSize, int $maxWidth): array
    {
        $words = explode(' ', $text);
        $lines = [];
        $currentLine = '';

        foreach ($words as $word) {
            $testLine = trim($currentLine . ' ' . $word);
            $box = imagettfbbox($fontSize, 0, $fontPath, $testLine);
            $lineWidth = abs($box[2] - $box[0]);

            if ($lineWidth < $maxWidth) {
                $currentLine = $testLine;
            } else {
                if ($currentLine) $lines[] = $currentLine;
                $currentLine = $word;
            }
        }

        if ($currentLine) $lines[] = $currentLine;
        return $lines;
    }
}
