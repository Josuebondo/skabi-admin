<?php

namespace Core;

/**
 * Classe Validateur - Validation réutilisable avec règles et messages personnalisés
 * 
 * Utilisation:
 *   $v = new Validateur();
 *   $v->ajouter('email', ['requis', 'email']);
 *   $v->ajouter('mot_de_passe', ['requis', 'min:8']);
 *   
 *   if ($v->valider($_POST)) {
 *       // Valide
 *   } else {
 *       $erreurs = $v->erreurs();
 *   }
 */
class Validateur
{
    private array $regles = [];
    private array $messages = [];
    private array $donnees = [];
    private array $erreurs = [];

    // Messages par défaut
    private static array $messagesParDefaut = [
        'requis' => 'Le champ {champ} est requis',
        'email' => 'Le champ {champ} doit être une adresse email valide',
        'min' => 'Le champ {champ} doit contenir au minimum {param} caractères',
        'max' => 'Le champ {champ} peut contenir au maximum {param} caractères',
        'regex' => 'Le champ {champ} a un format invalide',
        'unique' => 'La valeur du champ {champ} existe déjà',
        'match' => 'Le champ {champ} ne correspond pas à {param}',
        'nombre' => 'Le champ {champ} doit être un nombre',
        'entier' => 'Le champ {champ} doit être un entier',
        'url' => 'Le champ {champ} doit être une URL valide',
    ];

    /**
     * Ajoute une règle de validation pour un champ
     * 
     * @param string $champ Nom du champ
     * @param array $regles Tableau de règles ['requis', 'email', 'min:8', etc.]
     * @param array $messages Messages personnalisés pour chaque règle
     */
    public function ajouter(string $champ, array $regles, array $messages = []): self
    {
        $this->regles[$champ] = $regles;
        if (!empty($messages)) {
            $this->messages[$champ] = $messages;
        }
        return $this;
    }

    /**
     * Valide les données contre les règles
     */
    public function valider(array $donnees): bool
    {
        $this->donnees = $donnees;
        $this->erreurs = [];

        foreach ($this->regles as $champ => $regles) {
            $valeur = $donnees[$champ] ?? null;

            foreach ($regles as $regle) {
                // Parse rule like "min:8" into ["min", "8"]
                $parts = explode(':', $regle, 2);
                $nom_regle = $parts[0];
                $param = $parts[1] ?? null;

                if (!$this->validerRegle($champ, $valeur, $nom_regle, $param)) {
                    $this->ajouterErreur($champ, $nom_regle, $param);
                    break; // Une seule erreur par champ
                }
            }
        }

        return empty($this->erreurs);
    }

    /**
     * Valide une seule règle
     */
    private function validerRegle(string $champ, mixed $valeur, string $regle, ?string $param): bool
    {
        return match ($regle) {
            'requis' => !empty($valeur),
            'email' => empty($valeur) || filter_var($valeur, FILTER_VALIDATE_EMAIL),
            'min' => empty($valeur) || strlen((string)$valeur) >= (int)$param,
            'max' => empty($valeur) || strlen((string)$valeur) <= (int)$param,
            'regex' => empty($valeur) || preg_match($param, (string)$valeur),
            'match' => empty($valeur) || $valeur === ($this->donnees[$param] ?? null),
            'nombre' => empty($valeur) || is_numeric($valeur),
            'entier' => empty($valeur) || is_int($valeur) || ctype_digit((string)$valeur),
            'url' => empty($valeur) || filter_var($valeur, FILTER_VALIDATE_URL),
            default => true,
        };
    }

    /**
     * Ajoute une erreur pour un champ
     */
    private function ajouterErreur(string $champ, string $regle, ?string $param): void
    {
        $message = $this->obtenirMessage($champ, $regle);

        // Remplace les placeholders
        $message = str_replace('{champ}', $this->formatNomChamp($champ), $message);
        if ($param) {
            $message = str_replace('{param}', $param, $message);
        }

        if (!isset($this->erreurs[$champ])) {
            $this->erreurs[$champ] = [];
        }

        $this->erreurs[$champ][] = $message;
    }

    /**
     * Obtient le message pour une règle
     */
    private function obtenirMessage(string $champ, string $regle): string
    {
        // Message personnalisé
        if (isset($this->messages[$champ][$regle])) {
            return $this->messages[$champ][$regle];
        }

        // Message par défaut
        return self::$messagesParDefaut[$regle] ?? "Le champ {champ} est invalide";
    }

    /**
     * Formate le nom d'un champ pour l'affichage
     */
    private function formatNomChamp(string $champ): string
    {
        return ucfirst(str_replace('_', ' ', $champ));
    }

    /**
     * Retourne tous les erreurs
     */
    public function erreurs(): array
    {
        return $this->erreurs;
    }

    /**
     * Retourne les erreurs d'un champ spécifique
     */
    public function erreursChamp(string $champ): array
    {
        return $this->erreurs[$champ] ?? [];
    }

    /**
     * Vérifie si un champ a des erreurs
     */
    public function aErreur(string $champ): bool
    {
        return isset($this->erreurs[$champ]);
    }

    /**
     * Obtient la première erreur d'un champ
     */
    public function premiereErreur(string $champ): ?string
    {
        return $this->erreurs[$champ][0] ?? null;
    }
}
