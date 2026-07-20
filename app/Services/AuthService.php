<?php

namespace App\Services;

use Bmvc\BAuth\Adapters\BMVC\BmvcAuthProvider;
use Bmvc\BAuth\Auth as BAuth;
use Bmvc\BAuth\Config as BAuthConfig;
use Bmvc\BAuth\Exceptions\AuthenticationException;
use Bmvc\BAuth\Providers\BaseAuthProvider;
use Core\Validateur;

/**
 * Service d'Authentification
 * Encapsule la logique d'authentification réutilisable
 */
class AuthService
{
    protected BAuth $auth;


    private const ACCESS_TOKEN_EXPIRES = 3600; // 1 hour
    private const REFRESH_TOKEN_EXPIRES = 604800; // 7 days

    public function __construct()
    {
        $config = new BAuthConfig([
            'jwt' => [
                'secret' => env('AUTH_JWT_SECRET', 'dev-secret-change-me'),
                'expiresIn' => self::ACCESS_TOKEN_EXPIRES,
                'algorithm' => 'HS256',
            ],
            'password' => [
                'algorithm' => PASSWORD_BCRYPT,
                'options' => ['cost' => 12],
            ],
        ]);

        $auth = new BAuth($config);
        $adapter = new BmvcAuthProvider($config, 'users');
        $auth->setAuthProvider($adapter);
        $this->auth = $auth;
    }
    /**
     * Authentifie un utilisateur via Bauth et la base de données
     */
    public function connexion(string $identifiant, string $motDePasse): array
    {
        return $this->connecterViaBauth($identifiant, $motDePasse);
    }

    public function connecterViaBauth(string $identifiant, string $motDePasse): array
    {
        $auth = $this->creerAuthBauth();
        return $auth->login($identifiant, $motDePasse);
    }

    public function deconnecterViaBauth(): void
    {
        $this->creerAuthBauth()->logout();
    }

    public function creerAuthBauth(): BAuth
    {
        $config = new BAuthConfig([
            'jwt' => [
                'secret' => env('AUTH_JWT_SECRET', 'admin-skabi-secret'),
                'expiresIn' => 3600,
            ],
            'session' => [
                'name' => 'skabi_auth',
                'lifetime' => 7200,
            ],
        ]);

        $provider = new class($config) extends BaseAuthProvider {
            public function authenticate(string $identifier, string $password): bool
            {
                $userData = \App\Modeles\users::verifierIdentifiants($identifier, $password);

                if (!$userData) {
                    throw new AuthenticationException('Identifiants incorrects');
                }

                $this->user = $this->normaliserUtilisateur($userData);
                return true;
            }

            public function getUserByIdentifier(string $identifier): ?array
            {
                return null;
            }

            public function getUserByEmail(string $email): ?array
            {
                return null;
            }

            public function getUserById(mixed $id): ?array
            {
                return null;
            }

            public function createUser(array $userData): ?array
            {
                return null;
            }

            public function updateUser(mixed $userId, array $data): bool
            {
                return false;
            }

            public function deleteUser(mixed $userId): bool
            {
                return false;
            }

            private function normaliserUtilisateur(array $userData): array
            {
                return [
                    'id' => $userData['id'] ?? $userData['user_id'] ?? $userData['uuid'] ?? null,
                    'username' => $userData['username'] ?? $userData['email'] ?? $userData['name'] ?? '',
                    'email' => $userData['email'] ?? '',
                    'name' => $userData['name'] ?? $userData['username'] ?? '',
                    'role' => $userData['role'] ?? $userData['roles'] ?? null,
                    'raw' => $userData,
                ];
            }
        };

        $auth = new BAuth($config);
        $auth->setAuthProvider($provider);

        return $auth;
    }

    /**
     * Valide les données de connexion
     */
    public function validerConnexion(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->valider($donnees);

        return $v;
    }

    /**
     * Valide les données d'inscription
     */
    public function validerInscription(array $donnees): Validateur
    {
        $v = new Validateur();
        $v->ajouter('nom', ['requis', 'min:3']);
        $v->ajouter('email', ['requis', 'email']);
        $v->ajouter('mot_de_passe', ['requis', 'min:8']);
        $v->ajouter('confirmation_mot_de_passe', ['match:mot_de_passe']);
        $v->valider($donnees);

        return $v;
    }
}
