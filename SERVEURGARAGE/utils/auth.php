<?php

use Firebase\JWT\JWT;

require_once "./models/admin.manager.php";

// Crée une instance de AdminManager
$adminManager = new AdminManager();

// Je définis une fonction pour générer un jeton d'authentification
function generateAuthToken($userId, $email)
{
    // Je crée le payload avec les données de l'utilisateur
    $payload = [
        'user_id' => $userId,
        'email' => $email,
        // J'ajoute d'autres informations utilisateur si nécessaire
    ];
    // Je génère une clé secrète aléatoire
    $randomBytes = random_bytes(32); // Génère 32 octets de données aléatoires
    $secretKey = base64_encode($randomBytes); // Encode les octets aléatoires en base64 pour obtenir une chaîne utilisable comme clé secrète
    // Je signe le jeton avec la clé secrète aléatoire
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    return $jwt;
}
// Récupère les informations d'identification soumises par l'utilisateur (par exemple, depuis un formulaire de connexion)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $login = $_POST['email'];
    $password = $_POST['password'];
    // Je vérifie si les informations d'identification sont valides
    if ($adminManager->isConnexionValid($login, $password)) {
        // Les informations d'identification sont valides, je continue à authentifier l'utilisateur
        // Par exemple, je peux récupérer le rôle de l'utilisateur à partir de la base de données
        $userRole = $adminManager->getUserRoleFromDatabase($login);
        // Générer le token JWT
        $jwt = generateAuthToken($userId, $email); // Assurez-vous de remplacer $userId et $email par les valeurs appropriées
        // Renvoyer le token JWT dans une réponse JSON
        header('Content-Type: application/json');
        echo json_encode(['token' => $jwt]);
        exit(); // Arrête l'exécution du script après l'envoi de la réponse JSON
    } else {
        // Les informations d'identification ne sont pas valides, je peux renvoyer une réponse JSON avec un message d'erreur
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Identifiants incorrects']);
        exit(); // Arrête l'exécution du script après l'envoi de la réponse JSON
    }
} else {
    // Les clés 'email' et/ou 'password' ne sont pas définies dans $_POST, je peux renvoyer une réponse JSON avec un message d'erreur
    header('Content-Type: application/json');
    echo json_encode(['error' => 'Adresse e-mail ou mot de passe manquant']);
    exit(); // Arrête l'exécution du script après l'envoi de la réponse JSON
}
