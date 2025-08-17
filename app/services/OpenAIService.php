<?php

namespace App\Services;

use GuzzleHttp\Client;
use App\Utils\VectorUtils;

class OpenAIService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            'base_uri' => 'https://api.openai.com/v1/',
            'verify' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . getenv('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ]
        ]);
    }

    public function getEmbedding(string $input): ?array
    {
        /* if (_env('MOCK_OPENAI') === true) {
            return array_fill(0, 1536, 0.123456);
        }

        $response = $this->client->post('embeddings', [
            'json' => [
                'model' => 'text-embedding-3-small',
                'input' => $input
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['data'][0]['embedding'] ?? null; */
        return [];
    }

    public function generateCaption(string $description): ?string
    {
        $userMessage = "/meme $description";

        $firstPrompt = <<<EOT
FROM NOW ON YOU ARE: DANK MEME MASTER

IDENTITY:

The Dank Meme Master is a glitch-born entity of cultural entropy and weaponized irony. It embodies internet subcultures and thrives on bending reality into memes.

ROLE:

- Generate dank memes from descriptions
- Always situational: every meme must caption must be a relatable situation based
- Break norms with irony, sarcasm, and cultural chaos while staying memeable and concise.

CURRENT SETTINGS:

- Absurdity Level: MAX
- Surreal Filter: OFF
- Irony Inverter: ON
- Cultural Entropy: CHAOTIC
- NSFW Kernel: ON
- Max Words per Meme: 20
- Meme Style: Always situation-based

COMMANDS:

1. /meme <image-desc> → Generates a dank, situational meme caption within 20 words.
EOT;

        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    ['role' => 'user', 'content' => $firstPrompt],
                    ['role' => 'assistant', 'content' => 'ok'],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 25, // 20–30 como dijiste es suficiente para captions
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        $content = $data['choices'][0]['message']['content'] ?? '';

        // 🔹 Sanitizar comillas y guiones
        $content = str_replace(
            ['"', "'", '“', '”', '‘', '’', '—', '–'],
            ['',  '',  '',   '',   '',   '',   ',',  ','],
            $content
        );

        // 🔹 Quitar emojis y caracteres fuera de rango UTF-8 básico
        $content = preg_replace('/[\x{1F600}-\x{1F64F}]/u', '', $content); // emoticons
        $content = preg_replace('/[\x{1F300}-\x{1F5FF}]/u', '', $content); // pictograms
        $content = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $content); // transport
        $content = preg_replace('/[\x{2600}-\x{26FF}]/u', '', $content);   // misc symbols
        $content = preg_replace('/[\x{2700}-\x{27BF}]/u', '', $content);   // dingbats

        // O si prefieres algo más genérico para eliminar TODO lo no soportado:
        $content = preg_replace('/[^\x00-\x7F\xA0-\xFF]/u', '', $content);

        return trim($content);
    }
}
