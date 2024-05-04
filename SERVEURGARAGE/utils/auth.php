<?php

use Firebase\JWT\JWT;

$adminManager = new AdminManager();

require_once __DIR__ . '/../vendor/autoload.php';
require_once "./models/admin.manager.php";

// Récupération de la clé secrète à partir des variables d'environnement
$secretKey = getenv('SECRET_KEY');

// Fonction pour générer un jeton d'authentification
function generateAuthToken($email)
{
    global $adminManager, $secretKey;
    $userId = $adminManager->getUserIdByEmail($email);
    $userRole = $adminManager->getUserRoleByEmail($email);
    $payload = [
        'user_id' => $userId,
        'email' => $email,
        'role' => $userRole,
    ];
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    return $jwt;
}

// Fonction pour vérifier le JWT
function verifyJWT($jwt): ?stdClass
{
    global $secretKey;
    try {
        $decoded = JWT::decode($jwt, $secretKey, $secretKey('HS256'));
        // var_dump($decoded);
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}

// Fonction pour vérifier l'authentification et récupérer les données de l'utilisateur
function requireAuth()
{
    global $secretKey;
    $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    if ($token) {
        $userData = verifyJWT($token);
        return $userData;
    } else {
        return null;
    }
}
