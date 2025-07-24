<?php

namespace App\Controllers;

use App\Models\Like;

use App\Services\TelegramService;

class TelegramsController extends Controller
{
    public function webhook()
    {
        $telegram = new TelegramService();

        try {
            $update = json_decode(file_get_contents("php://input"), true);

            // Likes via inline buttons
            if (isset($update['callback_query'])) {
                $callback = $update['callback_query'];
                $data = $callback['data'];
                $chat_id = $callback['message']['chat']['id'];
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

                    exit;
                }
            }

            if (!isset($update['message']['text'])) return;

            $chat_id = $update['message']['chat']['id'];
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
            $chat_id ??= null;
            if ($chat_id) {
                $telegram->sendText($chat_id, "❌ Error:\n" . $e->getMessage());
            }
        }
    }
}
