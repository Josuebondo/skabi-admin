<?php

namespace Core;

/**
 * ======================================================================
 * Reponse - Encapsule la réponse HTTP
 * ======================================================================
 * 
 * Responsabilités :
 * - Définir le code de statut
 * - Ajouter les en-têtes HTTP
 * - Envoyer le contenu
 * - Gérer les redirections
 * - Retourner du JSON
 */
class Reponse
{
    protected int $statut = 200;
    protected array $entetes = [];
    protected string $contenu = '';
    protected bool $envoye = false;

    /**
     * Définit le code de statut HTTP
     */
    public function statut(int $code): self
    {
        $this->statut = $code;
        return $this;
    }

    /**
     * Ajoute un en-tête HTTP
     */
    public function entete(string $nom, string $valeur): self
    {
        $this->entetes[$nom] = $valeur;
        return $this;
    }

    /**
     * Définit le contenu de la réponse
     */
    public function contenu(string $contenu): self
    {
        $this->contenu = $contenu;
        return $this;
    }

    /**
     * Ajoute du contenu
     */
    public function ajouterContenu(string $contenu): self
    {
        $this->contenu .= $contenu;
        return $this;
    }

    /**
     * Envoie une réponse JSON
     */
    public function json(array $donnees, int $statut = 200): void
    {
        $this->statut($statut);
        $this->entete('Content-Type', 'application/json; charset=utf-8');
        $this->contenu = json_encode($donnees, JSON_UNESCAPED_UNICODE);
        $this->envoyer();
    }

    /**
     * Redirige vers une URL
     */
    public function redirection(string $url, int $statut = 302): void
    {
        $this->statut($statut);
        $this->entete('Location', $url);
        $this->envoyer();
    }

    /**
     * Envoie la réponse HTTP
     */
    public function envoyer(): void
    {
        if ($this->envoye) {
            return;
        }

        // Envoyer le statut HTTP
        http_response_code($this->statut);

        // Envoyer les en-têtes
        foreach ($this->entetes as $nom => $valeur) {
            header("$nom: $valeur");
        }

        // Envoyer le contenu
        echo $this->contenu;

        $this->envoye = true;
    }

    /**
     * Obtient le statut HTTP
     */
    public function obtenirStatut(): int
    {
        return $this->statut;
    }

    /**
     * Obtient le contenu
     */
    public function obtenirContenu(): string
    {
        return $this->contenu;
    }
}
