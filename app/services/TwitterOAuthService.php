<?php

namespace App\Services;

use GuzzleHttp\Client;

class TwitterOAuthService
{
    protected string $clientId;
    protected string $clientSecret;
    protected string $redirectUri;
    protected array $scopes;
    protected string $authUrl = 'https://twitter.com/i/oauth2/authorize';
    protected string $tokenUrl = 'https://api.twitter.com/2/oauth2/token';

    protected string $tokenPath; // LOCAL ONLY: path al archivo local donde se guarda el token

    public string $codeVerifier;
    public string $codeChallenge;

    public function __construct()
    {
        $this->clientId     = _env('CLIENT_ID');
        $this->clientSecret = _env('OAUTH_V2_SECRET');
        $this->redirectUri  = _env('REDIRECT_URI');
        $this->scopes       = ['tweet.read', 'users.read', 'tweet.write', 'offline.access', 'media.write'];

        // LOCAL ONLY
        $this->tokenPath = dirname(__DIR__, 2) . '/storage/twitter_token.json';

        $this->generateCodeChallenge();
    }

    protected function generateCodeChallenge()
    {
        $random = base64_encode(random_bytes(32));
        $this->codeVerifier = preg_replace('/[^a-zA-Z0-9]/', '', $random);

        $hash = hash('sha256', $this->codeVerifier, true);
        $this->codeChallenge = rtrim(strtr(base64_encode($hash), '+/', '-_'), '=');
    }

    public function getAuthorizationUrl(): string
    {
        $scope = implode(' ', $this->scopes);

        return $this->authUrl . '?' . http_build_query([
            'response_type'         => 'code',
            'client_id'             => $this->clientId,
            'redirect_uri'          => $this->redirectUri,
            'scope'                 => $scope,
            'state'                 => bin2hex(random_bytes(8)),
            'code_challenge'        => $this->codeChallenge,
            'code_challenge_method' => 'S256',
        ]);
    }

    public function exchangeCodeForToken(string $code): array
    {
        $client = new Client(['verify' => false]);
        $basicAuth = base64_encode("{$this->clientId}:{$this->clientSecret}");

        $response = $client->post($this->tokenUrl, [
            'headers' => [
                'Authorization' => 'Basic ' . $basicAuth,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type'    => 'authorization_code',
                'code'          => $code,
                'redirect_uri'  => $this->redirectUri,
                'code_verifier' => $this->codeVerifier,
            ],
        ]);

        $token = json_decode($response->getBody()->getContents(), true);

        // ====== AÑADIDO: sellar tiempos para refresh proactivo ======
        $token['created_at'] = time();
        if (isset($token['expires_in'])) {
            $token['expires_at'] = $token['created_at'] + (int)$token['expires_in'];
        }
        // ============================================================

        file_put_contents($this->tokenPath, json_encode($token));

        return $token;
    }

    public function refreshToken(string $refreshToken): array
    {
        $client = new Client(['verify' => false]);
        $basicAuth = base64_encode("{$this->clientId}:{$this->clientSecret}");

        $response = $client->post($this->tokenUrl, [
            'headers' => [
                'Authorization' => 'Basic ' . $basicAuth,
                'Content-Type'  => 'application/x-www-form-urlencoded',
            ],
            'form_params' => [
                'grant_type'    => 'refresh_token',
                'refresh_token' => $refreshToken,
            ],
        ]);

        $newToken = json_decode($response->getBody()->getContents(), true);

        // ====== AÑADIDO: sellar tiempos para refresh proactivo ======
        $newToken['created_at'] = time();
        if (isset($newToken['expires_in'])) {
            $newToken['expires_at'] = $newToken['created_at'] + (int)$newToken['expires_in'];
        }
        // ============================================================

        file_put_contents($this->tokenPath, json_encode($newToken));

        return $newToken;
    }

    // ====== AÑADIDO: devuelve access token válido (refresca si está por expirar) ======
    public function getValidAccessToken(): ?string
    {
        $token = $this->getToken();
        if (!$token) return null;

        // Si faltan <=60s para expirar, refrescar
        if (isset($token['expires_at']) && time() >= ($token['expires_at'] - 60)) {
            if (!empty($token['refresh_token'])) {
                $token = $this->refreshToken($token['refresh_token']);
            }
        }

        return $token['access_token'] ?? null;
    }
    // ===================================================================================

    public function postTweet(string $text, string $accessToken, ?string $replyTo = null): array
    {
        $body = ['text' => $text];

        if ($replyTo) {
            $body['reply'] = ['in_reply_to_tweet_id' => $replyTo];
        }

        return $this->makeRequest('https://api.twitter.com/2/tweets', 'POST', $accessToken, $body);
    }

    public function postSimpleTweet(string $text): array
    {
        // ====== CAMBIO: usar token válido antes de disparar ======
        $accessToken = $this->getValidAccessToken();
        if (!$accessToken) return ['error' => 'No token'];
        $result = $this->postTweet($text, $accessToken);
        // ========================================================

        // Fallback: si aun así responde Unauthorized, intentar refresh reactivo
        if (isset($result['title']) && $result['title'] === 'Unauthorized') {
            $token = $this->getToken();
            if ($token && !empty($token['refresh_token'])) {
                $token = $this->refreshToken($token['refresh_token']);
                $result = $this->postTweet($text, $token['access_token']);
            }
        }

        return $result;
    }

    public function postReplyToTweet(string $text, string $inReplyTo): array
    {
        // ====== CAMBIO: usar token válido antes de disparar ======
        $accessToken = $this->getValidAccessToken();
        if (!$accessToken) return ['error' => 'No token'];
        $result = $this->postTweet($text, $accessToken, $inReplyTo);
        // ========================================================

        // Fallback: si aun así responde Unauthorized, intentar refresh reactivo
        if (isset($result['title']) && $result['title'] === 'Unauthorized') {
            $token = $this->getToken();
            if ($token && !empty($token['refresh_token'])) {
                $token = $this->refreshToken($token['refresh_token']);
                $result = $this->postTweet($text, $token['access_token'], $inReplyTo);
            }
        }

        return $result;
    }

    public function getToken(): ?array
    {
        if (!file_exists($this->tokenPath)) return null;
        return json_decode(file_get_contents($this->tokenPath), true);
    }

    public function makeRequest(string $url, string $method, string $accessToken, array $payload = []): array
    {
        $client = new Client(['verify' => false]);

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
                'Content-Type'  => 'application/json',
            ],
            'json' => $payload,
        ];

        $response = $client->request($method, $url, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    /* Post Tweet With Media */

    public function uploadMedia(string $filePath, string $accessToken): ?string
    {
        $client = new Client(['verify' => false]);

        $response = $client->post('https://api.twitter.com/2/media/upload', [
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken,
            ],
            'multipart' => [
                [
                    'name'     => 'media',
                    'contents' => fopen($filePath, 'r'),
                ],
                [
                    'name'     => 'media_category',
                    'contents' => 'tweet_image', // para imágenes estáticas
                ]
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);

        // en v2 devuelve data.id
        return $data['data']['id'] ?? null;
    }


    public function postTweetWithMedia(string $text, string $filePath, ?string $replyTo = null): array
    {
        $accessToken = $this->getValidAccessToken();
        if (!$accessToken) return ['error' => 'No token'];

        $mediaId = $this->uploadMedia($filePath, $accessToken);
        if (!$mediaId) return ['error' => 'Error al subir la media'];

        $body = [
            'text' => $text,
            'media' => [
                'media_ids' => [$mediaId],
            ]
        ];
        if ($replyTo) {
            $body['reply'] = ['in_reply_to_tweet_id' => $replyTo];
        }

        return $this->makeRequest('https://api.twitter.com/2/tweets', 'POST', $accessToken, $body);
    }
}
