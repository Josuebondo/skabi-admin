<?php

namespace App\Controleurs;

use App\BaseControleur;
use App\Services\AuthService;
use Bmvc\BAuth\Exceptions\AuthenticationException;
use Core\Requete;
use Core\Reponse;
use Core\Session;

/**
 * Gère les connexions et déconnexions des administrateurs.
 */
class AuthControleur extends BaseControleur
{
    public function index(Requete $requete, Reponse $response): string
    {
        return vue('auth.index');
    }

    /**
     * Authentifie l'utilisateur au moyen du service d'authentification.
     */
    public function login(Requete $requete, Reponse $response): void
    {
        if (Session::estActive()) {
            Session::vider();
            Session::detruire();
        }

        $data = $requete->tousCorps();
        $identifiant = trim((string) ($data['username'] ?? ''));
        $motDePasse = (string) ($data['password'] ?? '');

        if ($identifiant === '' || $motDePasse === '') {
            $response->json([
                'success' => false,
                'message' => 'Veuillez renseigner votre identifiant et votre mot de passe.',
            ], 422);
            return;
        }

        try {
            $resultat = (new AuthService())->connexion($identifiant, $motDePasse);
            $utilisateur = $resultat['user'] ?? null;

            if (!$utilisateur) {
                throw new AuthenticationException('Identifiants incorrects');
            }

            Session::demarrer();
            Session::enregistrer('user', $utilisateur);

            $response->json([
                'success' => true,
                'message' => 'Connexion réussie',
                'data' => $utilisateur,
            ]);
        } catch (AuthenticationException) {
            $response->json([
                'success' => false,
                'message' => 'Identifiants incorrects',
            ], 401);
        }
    }

    public function logout(Requete $requete, Reponse $response): void
    {
        (new AuthService())->deconnecterViaBauth();
        Session::demarrer();
        Session::vider();
        Session::detruire();
        $response->redirection('/login');
    }
}
