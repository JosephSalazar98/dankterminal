<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class TelegramService
{
    private string $apiUrl;
    private Client $client;

    public function __construct()
    {
        $token = getenv('TELEGRAM_TOKEN');
        $this->apiUrl = "https://api.telegram.org/bot{$token}";
        $this->client = new Client([
            'base_uri' => $this->apiUrl,
            'timeout'  => 10,
            'http_errors' => false,
        ]);
    }

    public function sendText(int $chatId, string $message): void
    {
        $this->client->get('/sendMessage', [
            'query' => [
                'chat_id' => $chatId,
                'text' => $message
            ]
        ]);
    }

    public function sendPhoto(int $chatId, string $imageUrl, string $caption, int $memeId): void
    {
        $this->client->get('/sendPhoto', [
            'query' => [
                'chat_id' => $chatId,
                'photo' => $imageUrl,
                'caption' => $caption,
                'reply_markup' => json_encode([
                    'inline_keyboard' => [[
                        [
                            'text' => "👍 0",
                            'callback_data' => "like:$memeId"
                        ]
                    ]]
                ])
            ]
        ]);
    }

    public function updateLikeButton(int $chatId, int $messageId, int $memeId, int $likeCount): void
    {
        $this->client->get('/editMessageReplyMarkup', [
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
            $response = $this->client->post('https://dankterminal.xyz/memes/generate', [
                'form_params' => ['prompt' => $prompt],
                'timeout' => 10,
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

            $response = $this->client->post('https://dankterminal.xyz/memes/creative', array_merge($payload, [
                'timeout' => 10,
            ]));

            return json_decode($response->getBody(), true);
        } catch (RequestException $e) {
            return ['error' => 'Request failed'];
        }
    }
}
