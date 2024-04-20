<?php

use Firebase\JWT\JWT;

require_once "./models/admin.manager.php";

// Crée une instance de AdminManager
$adminManager = new AdminManager();

// Récupère les informations d'identification soumises par l'utilisateur (par exemple, depuis un formulaire de connexion)
$login = $_POST['email'];
$password = $_POST['password'];

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

// Je vérifie si les informations d'identification sont valides
if ($adminManager->isConnexionValid($login, $password)) {
    // Les informations d'identification sont valides, je continue à authentifier l'utilisateur
    // Par exemple, je peux récupérer le rôle de l'utilisateur à partir de la base de données
    $userRole = $adminManager->getUserRoleFromDatabase($login);

    // En fonction du rôle de l'utilisateur, je peux rediriger l'utilisateur vers différentes pages ou lui accorder différentes autorisations
    if ($adminManager->isSuperAdmin($login)) {
        // L'utilisateur est un superadmin, je lui donne accès à des fonctionnalités spécifiques
        // Je le redirige vers le tableau de bord du superadmin, par exemple
        header("Location: dashboard_superadmin.php");
        throw new Exception("Méthode non autorisée");
    } elseif ($adminManager->isAdmin($login)) {
        // L'utilisateur est un admin, je lui donne accès à des fonctionnalités spécifiques pour les administrateurs
        // Je le redirige vers le tableau de bord de l'admin, par exemple
        header("Location: dashboard_admin.php");
        throw new Exception("Méthode non autorisée");
    } elseif ($adminManager->isEmploye($login)) {
        // L'utilisateur est un employé, je lui donne accès à des fonctionnalités spécifiques pour les employés
        // Je le redirige vers le tableau de bord de l'employé, par exemple
        header("Location: dashboard_employe.php");
        throw new Exception("Méthode non autorisée");
    } else {
        // L'utilisateur n'a pas de rôle valide, je peux rediriger vers une page d'erreur ou afficher un message d'erreur
        echo "Erreur : Rôle utilisateur invalide.";
    }
} else {
    // Les informations d'identification ne sont pas valides, je peux rediriger l'utilisateur vers une page de connexion avec un message d'erreur
    echo "Erreur : Identifiants incorrects.";
}
