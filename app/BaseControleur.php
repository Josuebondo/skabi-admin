<?php

namespace App;

use Core\Vue;
use Core\Requete;
use Core\Reponse;

/**
 * ======================================================================
 * BaseControleur - Classe de base pour tous les contrôleurs
 * ======================================================================
 * 
 * Fournit des méthodes utilitaires :
 * - rendre() - Rendre une vue
 * - rediriger() - Redirection HTTP
 * - json() - Réponse JSON
 * - reponse() - Accès direct à la réponse
 */
class BaseControleur
{
    protected Vue $vue;
    protected Reponse $reponse;
    protected static $requeteActuelle;

    public function __construct()
    {
        // Le chemin correct vers app/Vues
        $this->vue = new Vue(__DIR__ . '/Vues');
        $this->reponse = new Reponse();
    }

    /**
     * Définit la requête actuelle pour l'accès statique
     */
    public static function definirRequete(Requete $requete): void
    {
        self::$requeteActuelle = $requete;
    }

    /**
     * Obtient la requête actuelle
     */
    protected function requete(): Requete
    {
        return self::$requeteActuelle ?? new Requete($_SERVER, $_GET, $_POST, $_FILES ?? []);
    }

    /**
     * Rend une vue avec des données
     */
    protected function rendre(string $vue, array $donnees = []): string
    {
        return $this->vue->rendre($vue, $donnees);
    }

    /**
     * Affiche une vue avec des données
     */
    protected function afficher(string $vue, array $donnees = []): void
    {
        // Récupérer les erreurs stockées en session
        if (isset($_SESSION['erreurs'])) {
            $donnees['erreurs'] = $_SESSION['erreurs'];
            unset($_SESSION['erreurs']);
        }

        $this->vue->afficher($vue, $donnees);
    }

    /**
     * Redirige vers une URL
     */
    protected function rediriger(string $url, int $code = 302): void
    {
        header("Location: $url", true, $code);
        exit();
    }

    /**
     * Retourne une réponse JSON
     */
    protected function json(array $donnees, int $code = 200): void
    {
        header('Content-Type: application/json; charset=utf-8');
        http_response_code($code);
        echo json_encode($donnees, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit();
    }

    /**
     * Valide les données reçues
     */
    protected function valider(array $donnees, array $regles): array
    {
        $erreurs = [];

        foreach ($regles as $champ => $regleListe) {
            $valeur = $donnees[$champ] ?? null;
            $regles = is_string($regleListe) ? explode('|', $regleListe) : $regleListe;

            foreach ($regles as $regle) {
                $resultat = $this->validerRegle($champ, $valeur, $regle);
                if ($resultat !== true) {
                    $erreurs[$champ][] = $resultat;
                }
            }
        }

        return $erreurs;
    }

    /**
     * Valide une règle spécifique
     */
    protected function validerRegle(string $champ, $valeur, string $regle): ?string
    {
        if ($regle === 'requis' && empty($valeur)) {
            return "$champ est requis";
        }

        if (strpos($regle, 'min:') === 0) {
            $min = (int) substr($regle, 4);
            if (strlen($valeur) < $min) {
                return "$champ doit contenir au moins $min caractères";
            }
        }

        if (strpos($regle, 'max:') === 0) {
            $max = (int) substr($regle, 4);
            if (strlen($valeur) > $max) {
                return "$champ ne doit pas dépasser $max caractères";
            }
        }

        if ($regle === 'email' && !filter_var($valeur, FILTER_VALIDATE_EMAIL)) {
            return "$champ n'est pas une adresse email valide";
        }

        if ($regle === 'numero' && !is_numeric($valeur)) {
            return "$champ doit être un nombre";
        }

        return null;
    }

    /**
     * Récupère une session
     */
    protected function session(string $cle = null, $defaut = null)
    {
        if ($cle === null) {
            return $_SESSION;
        }

        return $_SESSION[$cle] ?? $defaut;
    }

    /**
     * Définit une session
     */
    protected function definirSession(string $cle, $valeur): void
    {
        $_SESSION[$cle] = $valeur;
    }

    /**
     * Sauvegarde les anciens inputs pour le formulaire
     */
    protected function sauvegarderAncienInputs(): void
    {
        $_SESSION['anciens_inputs'] = $_POST;
    }

    /**
     * Ajoute un message flash (temporaire)
     */
    protected function flash(string $type, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash'][$type] = $message;
    }

    /**
     * Récupère un ancien input ou une valeur par défaut
     */
    protected function ancien(string $champ, $defaut = ''): string
    {
        $anciens = $_SESSION['anciens_inputs'] ?? [];
        return $anciens[$champ] ?? $defaut;
    }

    /**
     * Stocke les erreurs de validation en session
     */
    protected function sauvegarderErreurs(array $erreurs): void
    {
        $_SESSION['erreurs'] = $erreurs;
    }
}
