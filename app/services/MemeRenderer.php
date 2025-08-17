<?php

namespace App\Services;

use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Utils\TextUtils;

class MemeRenderer
{
    public function render(string $imagePath, string $caption): string
    {
        $manager = new ImageManager(new Driver());
        $original = $manager->read($imagePath);

        $fontPath = __DIR__ . '/../fonts/Anton-Regular.ttf';
        $fontSize = max(20, min(intval($original->height() * 0.06), 60));
        $maxWidth = intval($original->width());

        $lines = TextUtils::wrapText($caption, $fontPath, $fontSize, $maxWidth);
        $lineHeight = $fontSize + 10;
        $paddingTop = 20;
        $paddingBottom = 20;
        $textAreaHeight = count($lines) * $lineHeight + $paddingTop + $paddingBottom;

        $canvas = $manager->create($original->width(), $original->height() + $textAreaHeight);
        $canvas->fill('#ffffff');
        $canvas->place($original, 'top-left', 0, $textAreaHeight);

        foreach ($lines as $i => $line) {
            $y = $paddingTop + $i * $lineHeight;

            $canvas->text($line, $canvas->width() / 2, $y, function ($font) use ($fontPath, $fontSize) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        $relativePath = '/generated/final_' . uniqid() . '.png';

        $fullPath = __DIR__ . '/../../public' . $relativePath;
        $canvas->save($fullPath);

        return $relativePath;
    }

    public function renderAndPost(string $imagePath, string $caption): string
    {
        $manager = new ImageManager(new Driver());
        $original = $manager->read($imagePath);

        $fontPath = __DIR__ . '/../fonts/Anton-Regular.ttf';
        $fontSize = max(20, min(intval($original->height() * 0.06), 60));
        $maxWidth = intval($original->width());

        $lines = TextUtils::wrapText($caption, $fontPath, $fontSize, $maxWidth);
        $lineHeight = $fontSize + 10;
        $paddingTop = 20;
        $paddingBottom = 20;
        $textAreaHeight = count($lines) * $lineHeight + $paddingTop + $paddingBottom;

        $canvas = $manager->create($original->width(), $original->height() + $textAreaHeight);
        $canvas->fill('#ffffff');
        $canvas->place($original, 'top-left', 0, $textAreaHeight);

        foreach ($lines as $i => $line) {
            $y = $paddingTop + $i * $lineHeight;

            $canvas->text($line, $canvas->width() / 2, $y, function ($font) use ($fontPath, $fontSize) {
                $font->file($fontPath);
                $font->size($fontSize);
                $font->color('#000000');
                $font->align('center');
                $font->valign('top');
            });
        }

        // Guardar imagen final
        $relativePath = '/generated/final_' . uniqid() . '.png';
        $fullPath     = __DIR__ . '/../../public' . $relativePath;
        $canvas->save($fullPath);

        // === Postear a X (Twitter) ===
        $twitter = new \App\Services\TwitterOAuthService();
        $result  = $twitter->postTweetWithMedia($caption, $fullPath);

        // Puedes loguear o devolver el resultado para debug
        // file_put_contents(__DIR__.'/../../storage/last_tweet.json', json_encode($result, JSON_PRETTY_PRINT));

        return $relativePath;
    }
}
