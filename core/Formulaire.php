<?php

namespace Core;

/**
 * ======================================================================
 * Formulaire - Classe d'aide pour les formulaires HTML
 * ======================================================================
 * 
 * Génère des inputs avec les anciens values et erreurs
 */
class Formulaire
{
    protected array $erreurs = [];
    protected array $anciens = [];

    public function __construct(array $erreurs = [], array $anciens = [])
    {
        $this->erreurs = $erreurs;
        $this->anciens = $anciens;
    }

    /**
     * Génère un input text
     */
    public function texte(string $nom, string $label = '', array $attributs = []): string
    {
        $valeur = htmlspecialchars($this->anciens[$nom] ?? '', ENT_QUOTES, 'UTF-8');
        $classe = $this->erreurs[$nom] ? 'formulaire-erreur' : '';
        $attrs = $this->genererAttributs($attributs);

        return <<<HTML
        <div class="formulaire-groupe">
            <label for="$nom">$label</label>
            <input type="text" id="$nom" name="$nom" value="$valeur" class="$classe" $attrs>
            {$this->afficherErreur($nom)}
        </div>
        HTML;
    }

    /**
     * Génère un textarea
     */
    public function textarea(string $nom, string $label = '', array $attributs = []): string
    {
        $valeur = htmlspecialchars($this->anciens[$nom] ?? '', ENT_QUOTES, 'UTF-8');
        $classe = $this->erreurs[$nom] ? 'formulaire-erreur' : '';
        $attrs = $this->genererAttributs($attributs);

        return <<<HTML
        <div class="formulaire-groupe">
            <label for="$nom">$label</label>
            <textarea id="$nom" name="$nom" class="$classe" $attrs>$valeur</textarea>
            {$this->afficherErreur($nom)}
        </div>
        HTML;
    }

    /**
     * Génère un input email
     */
    public function email(string $nom, string $label = '', array $attributs = []): string
    {
        $valeur = htmlspecialchars($this->anciens[$nom] ?? '', ENT_QUOTES, 'UTF-8');
        $classe = $this->erreurs[$nom] ? 'formulaire-erreur' : '';
        $attrs = $this->genererAttributs($attributs);

        return <<<HTML
        <div class="formulaire-groupe">
            <label for="$nom">$label</label>
            <input type="email" id="$nom" name="$nom" value="$valeur" class="$classe" $attrs>
            {$this->afficherErreur($nom)}
        </div>
        HTML;
    }

    /**
     * Génère un bouton submit
     */
    public function soumettre(string $texte = 'Envoyer', array $attributs = []): string
    {
        $attrs = $this->genererAttributs($attributs);
        return "<button type=\"submit\" $attrs>$texte</button>";
    }

    /**
     * Affiche l'erreur d'un champ
     */
    protected function afficherErreur(string $nom): string
    {
        if (isset($this->erreurs[$nom])) {
            $erreur = implode(', ', (array) $this->erreurs[$nom]);
            return "<span class=\"formulaire-erreur-message\">$erreur</span>";
        }
        return '';
    }

    /**
     * Génère les attributs HTML
     */
    protected function genererAttributs(array $attributs): string
    {
        $html = '';
        foreach ($attributs as $cle => $valeur) {
            $html .= " $cle=\"$valeur\"";
        }
        return $html;
    }
}
