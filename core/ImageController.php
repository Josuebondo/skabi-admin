<?php

namespace Core;

class ImageController
{
    /**
     * Affiche une image stockée dans le dossier public/images/menu ou autre sous-dossier
     * Usage: /image?path=menu/nom_image.jpg
     */
    public static function afficher()
    {
        $path = $_GET['path'] ?? '';
        $baseDir = realpath(__DIR__ . '/../../public/images');
        $file = realpath($baseDir . '/' . $path);

        // Sécurité : le fichier doit être dans le dossier images
        if (!$file || strpos($file, $baseDir) !== 0 || !file_exists($file)) {
            http_response_code(404);
            echo 'Image non trouvée';
            exit;
        }

        $mime = mime_content_type($file);
        header('Content-Type: ' . $mime);
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
}
