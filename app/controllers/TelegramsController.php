<?php

namespace App\Controllers;

use App\Models\Like;

use App\Services\TelegramService;

class TelegramsController extends Controller
{
    public function webhook()
    {
        $telegram = new TelegramService();
        $chat_id = null;

        // Try to extract chat_id early in case of later error
        $input = file_get_contents("php://input");
        $update = json_decode($input, true);

        if (isset($update['message']['chat']['id'])) {
            $chat_id = $update['message']['chat']['id'];
        } elseif (isset($update['callback_query']['message']['chat']['id'])) {
            $chat_id = $update['callback_query']['message']['chat']['id'];
        }

        try {
            // Likes via inline buttons
            if (isset($update['callback_query'])) {
                $callback = $update['callback_query'];
                $data = $callback['data'];
                $user_id = $callback['from']['id'];
                $message_id = $callback['message']['message_id'];

                if (str_starts_with($data, 'like:')) {
                    $memeId = intval(explode(':', $data)[1]);

                    $existing = Like::where('telegram_user_id', $user_id)
                        ->where('meme_id', $memeId)
                        ->first();

                    if ($existing) {
                        $existing->delete();
                    } else {
                        Like::create([
                            'telegram_user_id' => $user_id,
                            'meme_id' => $memeId
                        ]);
                    }

                    $newCount = Like::where('meme_id', $memeId)->count();
                    $telegram->updateLikeButton($chat_id, $message_id, $memeId, $newCount);
                    return;
                }
            }

            if (!isset($update['message']['text'])) return;

            $text = trim($update['message']['text']);

            if (stripos($text, '/generate') === 0) {
                $prompt = trim(substr($text, strlen('/generate')));

                if ($prompt === '') {
                    $telegram->sendText($chat_id, "Usage: /generate your meme prompt");
                    return;
                }

                $response = $telegram->callGenerateEndpoint($prompt);

                if (!$response || !isset($response['image_url'], $response['caption'], $response['meme_id'])) {
                    $telegram->sendText($chat_id, "Error generating meme.");
                    return;
                }

                $telegram->sendPhoto(
                    $chat_id,
                    'https://dankterminal.xyz' . $response['image_url'],
                    $response['caption'],
                    $response['meme_id']
                );

                $localPath = __DIR__ . '/../../public' . $response['image_url'];
                if (file_exists($localPath)) {
                    unlink($localPath);
                }

                return;
            }

            if (stripos($text, '/creative') === 0) {
                $parts = explode(' ', $text);
                $imageId = $parts[1] ?? null;

                $response = $telegram->callCreativeEndpoint($imageId);

                if (!$response || !isset($response['image_url'], $response['caption'], $response['meme_id'])) {
                    $telegram->sendText($chat_id, "Couldn't create meme at the moment. Try again later.");
                    return;
                }

                $telegram->sendPhoto(
                    $chat_id,
                    'https://dankterminal.xyz' . $response['image_url'],
                    $response['caption'],
                    $response['meme_id']
                );

                $localPath = __DIR__ . '/../../public' . $response['image_url'];
                if (file_exists($localPath)) {
                    unlink($localPath);
                }

                return;
            }
        } catch (\Throwable $e) {
            if ($chat_id) {
                $telegram->sendText($chat_id, "❌ Error:\n" . $e->getMessage());
            } else {
                file_put_contents(__DIR__ . '/../../logs/telegram-errors.log', $e . "\n", FILE_APPEND);
            }
        }
    }
}
