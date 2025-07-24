<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TelegramService
{
    private string $apiUrl;
    private Client $client;
    private Client $appClient;

    public function __construct()
    {
        $token = _env('TELEGRAM_TOKEN');

        $this->apiUrl = "https://api.telegram.org/bot{$token}/";
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 10,
            'http_errors' => false,
            'verify' => false,

        ]);

        $appUrl = rtrim(_env('APP_URL'), '/') . '/';
        $this->appClient = new Client([
            'base_uri' => $appUrl,
            'timeout' => 10,
            'http_errors' => false,
            'verify' => false,
        ]);
    }

    public function sendText(int $chatId, string $message): void
    {
        file_put_contents(__DIR__ . '/../../storage/logs/tg.log', "[sendText] chat_id: $chatId, message: $message\n", FILE_APPEND);

        $response = $this->client->get('sendMessage', [
            'query' => [
                'chat_id' => $chatId,
                'text' => $message
            ]
        ]);

        file_put_contents(__DIR__ . '/../../storage/logs/tg.log', "[sendText] response: " . $response->getBody() . "\n", FILE_APPEND);
    }

    public function sendPhoto(int $chatId, string $imageUrl, string $caption, int $memeId): void
    {
        file_put_contents(__DIR__ . '/../../storage/logs/tg.log', "[sendPhoto] chat_id: $chatId, photo: $imageUrl\ncaption: $caption\n", FILE_APPEND);

        $response = $this->client->post('sendPhoto', [
            'json' => [
                'chat_id' => $chatId,
                'photo' => $imageUrl,
                'caption' => $caption,
                'reply_markup' => [
                    'inline_keyboard' => [[
                        [
                            'text' => "👍 0",
                            'callback_data' => "like:$memeId"
                        ]
                    ]]
                ]
            ]
        ]);


        file_put_contents(__DIR__ . '/../../storage/logs/tg.log', "[sendPhoto] response: " . $response->getBody() . "\n", FILE_APPEND);
    }


    public function updateLikeButton(int $chatId, int $messageId, int $memeId, int $likeCount): void
    {
        $this->client->get('editMessageReplyMarkup', [
            'query' => [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        [
                            'text' => "👍 $likeCount",
                            'callback_data' => "like:$memeId"
                        ]
                    ]]
                ])
            ]
        ]);
    }

    public function callGenerateEndpoint(string $prompt): ?array
    {
        try {
            $response = $this->appClient->post('memes/generate', [
                'form_params' => ['prompt' => $prompt],
            ]);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => 'Request failed'];
        }
    }

    public function callCreativeEndpoint(?string $imageId = null): ?array
    {
        try {
            $payload = $imageId ? ['form_params' => ['image_id' => $imageId]] : [];

            $response = $this->appClient->post('memes/creative', $payload);

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => 'Request failed'];
        }
    }
}
