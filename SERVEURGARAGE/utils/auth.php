<?php

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

$adminManager = new AdminManager();

require_once __DIR__ . '/../vendor/autoload.php';
require_once "./models/admin.manager.php";

// Récupére la clé secrète à partir des variables d'environnement
$secretKey = getenv('SECRET_KEY');
if ($secretKey === false) {
    // Cas où la variable d'environnement n'est pas définie
    exit("La clé secrète n'est pas définie dans les variables d'environnement.");
}
https: //afdevflo.alwaysdata.net/
// Définition des en-têtes CORS
header("Access-Control-Allow-Origin: https://ggevparrot.vercel.app");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

// Pour gérer les requêtes pré-vol (OPTIONS)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

// Fonction pour générer un jeton d'authentification
function generateAuthToken($email)
{
    global $adminManager, $secretKey;
    $userId = $adminManager->getUserIdByEmail($email);
    $userRole = $adminManager->getUserRoleByEmail($email);
    $userName = $adminManager->getUserNameByEmail($email);
    // var_dump("ID de l'utilisateur: ", $userId);
    // var_dump("Role de l'utilisateur: ", $userRole);
    // var_dump("Nome de l'utilisateur: ", $userName);
    // var_dump($userRole);
    $payload = [
        'user_id' => $userId,
        'email' => $email,
        'name' => $userName,
        'userRole' => $userRole,
        'iat' => time(),
        'exp' => time() + (60 * 60)
    ];
    $jwt = JWT::encode($payload, $secretKey, 'HS256');
    return $jwt;
}

// Fonction pour vérifier le JWT
function verifyJWT($jwt): ?stdClass
{
    global $secretKey;
    try {
        // Vérifie si le jeton JWT est vide
        if (!$jwt) {
            return null;
        }
        // Décode le jeton JWT
        $decoded = JWT::decode($jwt, $secretKey, $secretKey('HS256'));
        // var_dump($decoded);
        // Vérifier si le jeton JWT a expiré
        if (isset($decoded->exp) && $decoded->exp < time()) {
            throw new Exception("Le jeton JWT a expiré");
        }
        // Retourne le jeton décodé
        return $decoded;
    } catch (ExpiredException $e) {
        throw new Exception("Le jeton JWT a expiré : " . $e->getMessage());
    } catch (BeforeValidException $e) {
        throw new Exception("Le jeton JWT n'est pas encore valide : " . $e->getMessage());
    } catch (SignatureInvalidException $e) {
        throw new Exception("Signature du jeton JWT invalide : " . $e->getMessage());
    } catch (UnexpectedValueException $e) {
        throw new Exception("Valeur inattendue dans le jeton JWT : " . $e->getMessage());
    } catch (Exception $e) {
        throw new Exception("Erreur de validation du jeton JWT : " . $e->getMessage());
    }
}

// Fonction pour vérifier l'authentification et récupérer les données de l'utilisateur
function requireAuth()
{
    $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? $_SERVER['HTTP_AUTHORIZATION'] : null;
    if ($token) {
        $userData = verifyJWT($token);
        if ($userData) {
            return $userData;
        } else {
            http_response_code(401);
            Model::sendJSON(["error" => "Le jeton JWT est invalide ou expiré"]);
            exit();
        }
    } else {
        http_response_code(401);
        Model::sendJSON(["error" => "Aucun jeton JWT trouvé dans l'en-tête Authorization"]);
        exit();
    }
}
