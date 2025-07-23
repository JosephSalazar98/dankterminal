<?php

namespace App\Controllers;

use App\Models\Like;
use App\Models\Meme;
use Leaf\Http\Request;

class TelegramsController
{
    public function webhook()
    {
        $TOKEN = '7551876871:AAHViKpth_PIpcD3xVBB5AcLwNXOt5Lckm8';
        $API_URL = "https://api.telegram.org/bot$TOKEN";

        $update = json_decode(file_get_contents("php://input"), true);

        // Manejar votos
        if (isset($update['callback_query'])) {
            $callback = $update['callback_query'];
            $data = $callback['data'];
            $chat_id = $callback['message']['chat']['id'];
            $message_id = $callback['message']['message_id'];

            if (str_starts_with($data, 'like:')) {
                $memeId = intval(explode(':', $data)[1]);

                $existing = Like::where('telegram_user_id', $chat_id)
                    ->where('meme_id', $memeId)
                    ->first();

                if ($existing) {
                    $existing->delete();
                } else {
                    Like::create([
                        'telegram_user_id' => $chat_id,
                        'meme_id' => $memeId
                    ]);
                }

                $newCount = Like::where('meme_id', $memeId)->count();

                file_get_contents($API_URL . '/editMessageReplyMarkup?' . http_build_query([
                    'chat_id' => $chat_id,
                    'message_id' => $message_id,
                    'reply_markup' => json_encode([
                        'inline_keyboard' => [[
                            [
                                'text' => "👍 $newCount",
                                'callback_data' => "like:$memeId"
                            ]
                        ]]
                    ])
                ]));

                exit;
            }
        }

        // Comando
        if (!isset($update['message']['text'])) return;

        $chat_id = $update['message']['chat']['id'];
        $text = trim($update['message']['text']);

        // /generate
        if (stripos($text, '/generate') === 0) {
            $prompt = trim(substr($text, strlen('/generate')));

            if ($prompt === '') {
                file_get_contents("$API_URL/sendMessage?" . http_build_query([
                    'chat_id' => $chat_id,
                    'text' => "Usage: /generate your meme prompt"
                ]));
                return;
            }

            $response = $this->callGenerateEndpoint($prompt);

            if (!$response || !isset($response['image_url'], $response['caption'], $response['meme_id'])) {
                file_get_contents("$API_URL/sendMessage?" . http_build_query([
                    'chat_id' => $chat_id,
                    'text' => "Error generating meme."
                ]));
                return;
            }

            file_get_contents($API_URL . '/sendPhoto?' . http_build_query([
                'chat_id' => $chat_id,
                'photo' => 'https://dankterminal.xyz' . $response['image_url'],
                'caption' => $response['caption'],
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        [
                            'text' => "👍 0",
                            'callback_data' => "like:" . $response['meme_id']
                        ]
                    ]]
                ])
            ]));

            $localPath = __DIR__ . '/../../public' . $response['image_url'];
            if (file_exists($localPath)) {
                unlink($localPath);
            }

            return;
        }

        // /creative
        if (stripos($text, '/creative') === 0) {
            $response = $this->callCreativeEndpoint();

            if (!$response || !isset($response['image_url'], $response['caption'], $response['meme_id'])) {
                file_get_contents("$API_URL/sendMessage?" . http_build_query([
                    'chat_id' => $chat_id,
                    'text' => "Error generating creative meme."
                ]));
                return;
            }

            file_get_contents($API_URL . '/sendPhoto?' . http_build_query([
                'chat_id' => $chat_id,
                'photo' => 'https://dankterminal.xyz' . $response['image_url'],
                'caption' => $response['caption'],
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        [
                            'text' => "👍 0",
                            'callback_data' => "like:" . $response['meme_id']
                        ]
                    ]]
                ])
            ]));

            $localPath = __DIR__ . '/../../public' . $response['image_url'];
            if (file_exists($localPath)) {
                unlink($localPath);
            }

            return;
        }
    }

    private function callGenerateEndpoint($prompt)
    {
        $ch = curl_init('https://dankterminal.xyz/memes/generate');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['prompt' => $prompt]));
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }

    private function callCreativeEndpoint()
    {
        $ch = curl_init('https://dankterminal.xyz/memes/creative');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        $result = curl_exec($ch);
        curl_close($ch);

        return json_decode($result, true);
    }
}
