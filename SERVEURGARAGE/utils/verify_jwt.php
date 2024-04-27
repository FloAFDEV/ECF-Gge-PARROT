<?php

use Firebase\JWT\JWT;

require_once "./models/admin.manager.php";
require_once __DIR__ . "/../../firebase/php-jwt/JWT.php";


// Crée une instance de AdminManager
$adminManager = new AdminManager();

// Fonction pour générer une clé secrète
function generateSecretKey($length = 32)
{
    return base64_encode(random_bytes($length));
}

// Clé secrète pour la signature du token JWT
$key = generateSecretKey();

// Je définis une fonction pour générer un jeton d'authentification
function generateAuthToken($email, $key)
{
    global $adminManager;
    // Récupère l'ID et le rôle de l'utilisateur à partir de l'email
    $userId = $adminManager->getUserIdByEmail($email); // Utilise la méthode getUserIdByEmail
    $userRole = $adminManager->getUserRoleByEmail($email); // Utilise la méthode getUserRoleByEmail
    // Je crée le payload avec les données de l'utilisateur, y compris le rôle
    $payload = [
        'user_id' => $userId,
        'email' => $email,
        'role' => $userRole,
    ];
    // Je signe le jeton avec la clé secrète
    $jwt = JWT::encode($payload, $key, 'HS256');
    return $jwt;
}

// Fonction pour vérifier le JWT
function verifyJWT($jwt): ?stdClass
{
    global $key;
    try {
        $decoded = JWT::decode($jwt, $key, $key('HS256'));
        echo "Contenu décodé du JWT : ";
        // var_dump($decoded);
        return $decoded;
    } catch (Exception $e) {
        return null;
    }
}
