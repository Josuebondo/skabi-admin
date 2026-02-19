<?php

namespace App\Services;

/**
 * Service de Notifications
 * Gère l'envoi de notifications (email, flash messages)
 */
class NotificationService
{
    /**
     * Envoie un email simple
     */
    public function envoyerEmail(string $destinataire, string $sujet, string $corps, string $htmlCorps = ''): bool
    {
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type: " . (!empty($htmlCorps) ? "text/html" : "text/plain") . "; charset=UTF-8" . "\r\n";
        $headers .= "From: " . env('ADRESSE_EMAIL_EXPEDITEUR', 'noreply@bmvc.local') . "\r\n";

        $corps_envoyer = !empty($htmlCorps) ? $htmlCorps : $corps;

        return mail($destinataire, $sujet, $corps_envoyer, $headers);
    }

    /**
     * Envoie un email de bienvenue
     */
    public function bienvenue(string $email, string $nom): bool
    {
        $sujet = "Bienvenue sur BMVC!";

        $corps = "Bonjour $nom,\n\n";
        $corps .= "Votre compte a été créé avec succès!\n\n";
        $corps .= "Cordialement,\nL'équipe BMVC";

        return $this->envoyerEmail($email, $sujet, $corps);
    }

    /**
     * Envoie un email de réinitialisation
     */
    public function reinitialiserMotDePasse(string $email, string $token): bool
    {
        $sujet = "Réinitialiser votre mot de passe";

        $lien = env('URL_APPLICATION', 'http://localhost') . env('URL_REINITIALISATION_MDP', '/reinitialiser') . "?token=" . $token;

        $corps = "Cliquez sur ce lien pour réinitialiser votre mot de passe:\n\n";
        $corps .= $lien . "\n\n";
        $corps .= "Ce lien expire dans " . env('EXPIRATION_REINITIALISATION_MDP', 86400) / 3600 . " heures.\n\n";
        $corps .= "Si vous n'avez pas demandé ceci, ignorez cet email.\n";

        return $this->envoyerEmail($email, $sujet, $corps);
    }

    /**
     * Ajoute un message flash à la session
     */
    public function succes(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['succes'] = $message;
    }

    /**
     * Ajoute un message d'erreur
     */
    public function erreur(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['erreur'] = $message;
    }

    /**
     * Ajoute un message d'avertissement
     */
    public function warning(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['avertissement'] = $message;
    }

    /**
     * Ajoute un message d'info
     */
    public function info(string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }
        $_SESSION['flash']['info'] = $message;
    }
}
