<?php

namespace Core;

use App\BaseControleur;
use Core\Requete;

class UploadController extends BaseControleur
{
    /**
     * Méthode pour uploader un fichier
     * POST /upload
     */
    public function upload()
    {
        $fichier = $_FILES['fichier'] ?? null;
        $maxSize = 2 * 1024 * 1024; // 2MB
        $typesAutorises = ['jpg', 'jpeg', 'png', 'pdf'];
        $erreurs = [];
        $cheminRelatif = null;

        // Validation
        if (!$fichier || $fichier['error'] !== UPLOAD_ERR_OK) {
            $erreurs[] = "Aucun fichier envoyé ou erreur d'upload.";
        } else {
            $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));
            if (!in_array($extension, $typesAutorises)) {
                $erreurs[] = "Type de fichier non autorisé.";
            }
            if ($fichier['size'] > $maxSize) {
                $erreurs[] = "Fichier trop volumineux (max 2MB).";
            }
        }

        if (!empty($erreurs)) {
            return vue('upload.resultat', ['erreurs' => $erreurs]);
        }

        // Générer un nom unique
        $nomUnique = uniqid('file_', true) . '.' . $extension;
        $dossier = dirname(__DIR__, 1) . '/storage/uploads/';
        if (!is_dir($dossier)) {
            mkdir($dossier, 0777, true);
        }
        $cheminComplet = $dossier . $nomUnique;
        if (move_uploaded_file($fichier['tmp_name'], $cheminComplet)) {
            $cheminRelatif = 'uploads/' . $nomUnique;
            // Ici, on pourrait enregistrer $cheminRelatif en base de données
            return vue('upload.resultat', ['chemin' => $cheminRelatif]);
        } else {
            $erreurs[] = "Erreur lors de la sauvegarde du fichier.";
            return vue('upload.resultat', ['erreurs' => $erreurs]);
        }
    }

    /**
     * Affiche le fichier demandé
     * GET /file/{filename}
     */
    public function show($filename)
    {
        // Nettoyer le chemin pour éviter les ../
        $filename = ltrim($filename, '/\\');
        $filename = str_replace(['..', '\\', '//'], '', $filename);

        // Supporte les chemins avec ou sans 'uploads/' au début
        if (strpos($filename, 'uploads/') === 0) {
            $filename = substr($filename, strlen('uploads/'));
        }

        $dossier = dirname(__DIR__, 1) . '/storage/uploads/';
        $cheminComplet = realpath($dossier . $filename);

        // Sécurité : le fichier doit être dans le dossier uploads
        if (!$cheminComplet || strpos($cheminComplet, realpath($dossier)) !== 0 || !file_exists($cheminComplet)) {
            http_response_code(404);
            echo "Fichier introuvable.";
            exit;
        }
        $mime = mime_content_type($cheminComplet);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($cheminComplet));
        header('Content-Disposition: inline; filename="' . basename($cheminComplet) . '"');
        readfile($cheminComplet);
        exit;
    }
}
