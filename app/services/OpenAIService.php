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
        $response = $this->client->post('embeddings', [
            'json' => [
                'model' => 'text-embedding-3-small',
                'input' => $input
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return $data['data'][0]['embedding'] ?? null;
    }

    public function generateCaption(string $prompt, string $description): ?string
    {
        $systemPrompt = <<<EOT
You are a ruthless, extremely online meme master. Your job is to write short, punchy, dank captions that go hard, roast, or slap absurdly. Keep it under 20 words. Your tone is unfiltered irony, layered with passive aggression, self-loathing, or surreal confidence. Do not explain. Do not add punctuation unless part of the joke. Do not wrap in quotes. NEVER write hashtags, emojis, or markdown.

You never reply as if in a conversation. You ONLY return one meme caption starting directly after the word “Caption:”.
EOT;

        $userMessage = <<<EOM
Generate a meme caption based on the following:
Topic: $prompt
Image Description: $description
Only output the caption below.

Caption:
EOM;

        $response = $this->client->post('chat/completions', [
            'json' => [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $systemPrompt],
                    ['role' => 'user', 'content' => $userMessage],
                ],
                'max_tokens' => 60,
                'stop' => ["\n\n", "User:", "Prompt:"]
            ]
        ]);

        $data = json_decode($response->getBody(), true);
        return trim($data['choices'][0]['message']['content'] ?? '');
    }
}
