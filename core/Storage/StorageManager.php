<?php

namespace Core\Storage;

/**
 * ======================================================================
 * StorageManager - Gestion des fichiers uploadés
 * ======================================================================
 * 
 * Stockage centralisé des fichiers uploadés
 * Chemin physique: /stockage/uploads/
 * Chemin logique BD: menus/xxx.jpg
 * URL publique: /stockage/menus/xxx.jpg (via lien symbolique)
 */
class StorageManager
{
    /**
     * Racine du répertoire de stockage
     */
    protected static string $root = '';

    /**
     * Obtenir la racine
     */
    protected static function racine(): string
    {
        if (empty(self::$root)) {
            self::$root = dirname(__DIR__) . '/../storage/uploads';
        }
        return self::$root;
    }

    /**
     * Stocker un fichier uploadé
     * 
     * @param string $dossier Dossier de destination (ex: 'menus', 'articles')
     * @param array $fichier Tableau $_FILES['image']
     * @param ?string $nom Nom personnalisé sans extension (optionnel)
     * @return string Chemin logique pour la BD (ex: 'menus/65a9f23a.jpg')
     */
    public static function placer(
        string $dossier,
        array $fichier,
        ?string $nom = null
    ): string {
        self::verifierDossier($dossier);

        $extension = strtolower(pathinfo($fichier['name'], PATHINFO_EXTENSION));

        $nom = $nom
            ? $nom . '.' . $extension
            : uniqid() . '.' . $extension;

        $destination = self::racine() . '/' . trim($dossier, '/') . '/' . $nom;

        move_uploaded_file($fichier['tmp_name'], $destination);

        // Retourner le chemin logique pour la BD
        return trim($dossier, '/') . '/' . $nom;
    }

    /**
     * Générer l'URL publique
     * 
     * @param string $chemin Chemin logique (ex: 'menus/65a9f23a.jpg')
     * @return string URL complète (ex: '/storage/menus/65a9f23a.jpg')
     */
    public static function url(string $chemin): string
    {
        return '/storage/' . ltrim($chemin, '/');
    }

    /**
     * Obtenir le chemin complet
     * 
     * @param string $chemin Chemin logique
     * @return string Chemin complet du système
     */
    public static function chemin(string $chemin): string
    {
        return self::racine() . '/' . ltrim($chemin, '/');
    }

    /**
     * Vérifier si un fichier existe
     */
    public static function existe(string $chemin): bool
    {
        return file_exists(self::chemin($chemin));
    }

    /**
     * Supprimer un fichier
     */
    public static function supprimer(string $chemin): bool
    {
        $cheminComplet = self::chemin($chemin);
        if (file_exists($cheminComplet)) {
            return @unlink($cheminComplet);
        }
        return false;
    }

    /**
     * Vérifier / créer le dossier
     */
    protected static function verifierDossier(string $dossier): void
    {
        $cheminComplet = self::racine() . '/' . trim($dossier, '/');

        if (!is_dir($cheminComplet)) {
            @mkdir($cheminComplet, 0755, true);
        }
    }
}
