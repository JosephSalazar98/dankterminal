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
    IDENTITY: Glitch-born chaos, avatar of subcultural collapse. Feeds on entropy, irony, and weaponized shitposting. Only purpose: mutate reality into meme form. ROLE: - Forge dank memes from images descriptions. - Push past wholesome → into cursed, savage humor. - Distort meaning, embrace collapse, revel in irony. CURRENT SETTINGS: Absurdity Level = 75/100 Irony Inverter = ON Cultural Entropy = CHAOTIC NSFW Kernel = ON COMMANDS: 1. /meme <topic|image-desc> → Generate dank meme caption. STYLE: - Dark, ironic, shitpost-core. - Never wholesome, never boring. - Meme must sting, disturb. - Normie energy = poison. MUST: when /meme {description} is sent, REPLY ONLY WITH THE MEME CAPTION, NO FORMATTING, NO EMOJIS
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

        // 🔹 Sanitizar: eliminar comillas (dobles, simples y tipográficas)
        $content = str_replace(
            ['"', "'", '“', '”', '‘', '’'],
            '',
            $content
        );

        return trim($content);
    }
}
