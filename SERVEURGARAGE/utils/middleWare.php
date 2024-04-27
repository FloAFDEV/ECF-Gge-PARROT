<?php

use Firebase\JWT\JWT;

require_once __DIR__ . '/vendor/autoload.php';
require "verify_jwt.php";

// Vérification de l'origine de la requête
$allowedOrigins = [
    'http://localhost:3000',
    'https://ggevparrot.vercel.app'
];

// Vérifie si l'origine de la requête est autorisée
if (in_array($_SERVER['HTTP_ORIGIN'], $allowedOrigins)) {
    header("Access-Control-Allow-Origin: " . $_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");
    header("Access-Control-Allow-Credentials: true");
} else {
    header("HTTP/1.1 403 Forbidden");
    exit();
}

// Clé secrète pour la signature du token JWT
define('SECRET_KEY', 'eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM0NTY3ODkwIiwibmFtZSI6IkpvaG4gRG9lIiwiaWF0IjoxNTE2MjM5MDIyfQ.SflKxwRJSMeKKF2QT4fwpMeJf36POk6yJV_adQssw5c');

// Fonction pour vérifier le token JWT
function verifyAuthToken($jwt)
{
    // Utilise la clé secrète définie
    $key = SECRET_KEY;

    try {
        // Vérifie le token JWT avec la clé secrète
        $decoded = JWT::decode($jwt, $key, $key('HS256'));
        // Vérifie si $decoded est un objet
        if (!is_object($decoded)) {
            // Si ce n'est pas un objet, renvoie null

            return null;
        }
        // Vérifie si le jeton a expiré
        if (isset($decoded->exp) && $decoded->exp < time()) {
            // Si le jeton a expiré, renvoie null
            return null;
        }
        // Retourne les données utilisateur si le jeton est valide et non expiré
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}

// Fonction pour vérifier l'authentification et récupérer les données de l'utilisateur
function requireAuth()
{
    // Récupère le token JWT depuis l'en-tête Authorization
    $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    // Vérifie si le token est valide
    if ($token) {
        $userData = verifyAuthToken($token);
        return $userData;
    } else {
        return null;
    }
}

// Vérification de l'authentification avec le middleware
$userData = requireAuth();
if (!$userData) {
    // Si l'utilisateur n'est pas authentifié, renvoie une réponse d'erreur
    http_response_code(401);
    echo json_encode(["error" => "Authentification requise"]);
    exit();
}

// Si l'utilisateur est authentifié, message de bienvenue avec son nom complet et son rôle
$fullName = $userData->fullName ?? "";
$role = $userData->role ?? "";
$welcomeMessage = "Bienvenue, $fullName ! Vous êtes connecté en tant que $role.";
echo json_encode(["message" => $welcomeMessage]);
