<?php

/**
 * ======================================================================
 * API Token - Authentification par token (JWT-like)
 * ======================================================================
 */

namespace Core;

class APIToken
{
    protected string $secret;
    protected int $expiration = 86400; // 24 heures

    public function __construct(string $secret = null)
    {
        $this->secret = $secret ?? env('CLE_SECRETE', 'secret-key');
    }

    /**
     * Générer un token
     */
    public function generer(array $donnees): string
    {
        $header = base64_encode(json_encode(['alg' => 'HS256', 'typ' => 'JWT']));
        $payload = base64_encode(json_encode([
            'donnees' => $donnees,
            'exp' => time() + $this->expiration,
            'iat' => time(),
        ]));

        $signature = hash_hmac('sha256', "{$header}.{$payload}", $this->secret, true);
        $signature = base64_encode($signature);

        return "{$header}.{$payload}.{$signature}";
    }

    /**
     * Vérifier un token
     */
    public function verifier(string $token): array|bool
    {
        $parties = explode('.', $token);

        if (count($parties) !== 3) {
            return false;
        }

        [$header, $payload, $signature] = $parties;

        // Vérifier la signature
        $signatureCalculee = hash_hmac('sha256', "{$header}.{$payload}", $this->secret, true);
        $signatureCalculee = base64_encode($signatureCalculee);

        if (!hash_equals($signature, $signatureCalculee)) {
            return false;
        }

        // Décoder le payload
        $payload = json_decode(base64_decode($payload), true);

        // Vérifier l'expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }

        return $payload['donnees'] ?? false;
    }

    /**
     * Définir l'expiration
     */
    public function setExpiration(int $secondes): self
    {
        $this->expiration = $secondes;
        return $this;
    }
}
