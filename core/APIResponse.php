<?php

/**
 * ======================================================================
 * API Response - Réponses JSON avec statuts HTTP
 * ======================================================================
 */

namespace Core;

class APIResponse
{
    protected int $statut = 200;
    protected array $donnees = [];
    protected string $message = '';

    /**
     * Créer une réponse de succès
     */
    public static function succes(array $donnees = [], string $message = 'Succès', int $statut = 200): self
    {
        $response = new self();
        $response->statut = $statut;
        $response->donnees = $donnees;
        $response->message = $message;

        return $response;
    }

    /**
     * Créer une réponse d'erreur
     */
    public static function erreur(string $message, array $donnees = [], int $statut = 400): self
    {
        $response = new self();
        $response->statut = $statut;
        $response->message = $message;
        $response->donnees = $donnees;

        return $response;
    }

    /**
     * Erreur 401 - Non authentifié
     */
    public static function nonAuthentifie(string $message = 'Non authentifié'): self
    {
        return self::erreur($message, [], 401);
    }

    /**
     * Erreur 403 - Accès refusé
     */
    public static function acceRefuse(string $message = 'Accès refusé'): self
    {
        return self::erreur($message, [], 403);
    }

    /**
     * Erreur 404 - Non trouvé
     */
    public static function nonTrouve(string $message = 'Ressource non trouvée'): self
    {
        return self::erreur($message, [], 404);
    }

    /**
     * Erreur 500 - Erreur serveur
     */
    public static function erreurServeur(string $message = 'Erreur serveur'): self
    {
        return self::erreur($message, [], 500);
    }

    /**
     * Ajouter des données
     */
    public function avec(array $donnees): self
    {
        $this->donnees = array_merge($this->donnees, $donnees);
        return $this;
    }

    /**
     * Ajouter un message
     */
    public function message(string $message): self
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Définir le statut HTTP
     */
    public function statut(int $statut): self
    {
        $this->statut = $statut;
        return $this;
    }

    /**
     * Retourner la réponse JSON
     */
    public function json(): array
    {
        return [
            'statut' => $this->statut,
            'succes' => $this->statut >= 200 && $this->statut < 300,
            'message' => $this->message,
            'donnees' => $this->donnees,
        ];
    }

    /**
     * Envoyer la réponse et quitter
     */
    public function envoyer(): void
    {
        http_response_code($this->statut);
        header('Content-Type: application/json; charset=utf-8');

        echo json_encode($this->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Retourner la réponse JSON en chaîne
     */
    public function __toString(): string
    {
        return json_encode($this->json(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
