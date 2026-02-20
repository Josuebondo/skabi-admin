<?php

namespace App\Controleurs;

use App\BaseControleur;
use Core\Requete;
use Core\Reponse;
use Core\Session;

/**
 * AuthControleur Contrôleur
 */
class AuthControleur extends BaseControleur
{
    /**
     * Exemple d'action
     */
    public function index(Requete $requete, Reponse $response): string
    {
        return vue('auth.index');
    }
    function login(Requete $requete, Reponse $response)
    {
        if (Session::estActive()) {
            Session::vider();
            Session::detruire();
        }
        // session_start(); // toujours démarrer la session si tu utilises $_SESSION
        $data = $requete->tousCorps();
        $username = $data['username'] ?? '';
        $password = $data['password'] ?? '';
        $url = "https://stock.skabi.shop/users/loginapi"; // Projet A

        $payload = json_encode([
            "username" => $username,
            "password" => $password
        ]);
        // dd($payload);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Content-Type: application/json",
            "X-API-KEY: ADMIN_SECRET_2026"
        ]);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        $apiResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        header('Content-Type: application/json');

        if ($httpCode !== 200) {
            echo json_encode([
                "success" => false,
                "message" => "Erreur API  ($httpCode)",
                'response' => $apiResponse



            ]);
            return;
        }

        $data = json_decode($apiResponse, true);

        if (!$data) {
            echo json_encode([
                "success" => false,
                "message" => "Réponse API invalide",
                'response' => $apiResponse
            ]);
            return;
        }
        // $response->json($data);
        if (!empty($data['status']) && $data['status'] === true) {
            session::demarrer();
            session::enregistrer('user', $data['data']);


            echo json_encode([
                "success" => true,
                "message" => "connexion réussie",
                "data" => session::obtenir('user')

            ]);
            return;
        } else {
            echo json_encode([
                "success" => false,
                "message" => $data['message'] ?? "Identifiants incorrects"
            ]);
            return;
        }
    }
    public function logout(Requete $requete, Reponse $response)
    {
        Session::demarrer();
        Session::vider();
        Session::detruire();
        header('Location: /login');
        exit;
    }
}
