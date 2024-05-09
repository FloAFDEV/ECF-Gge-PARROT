<?php

use Firebase\JWT\JWT;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\BeforeValidException;
use Firebase\JWT\SignatureInvalidException;

$adminManager = new AdminManager();

require_once __DIR__ . '/../vendor/autoload.php';
require_once "./models/admin.manager.php";

// Récupération de la clé secrète à partir des variables d'environnement
$secretKey = getenv('SECRET_KEY');
if ($secretKey === false) {
    // Cas où la variable d'environnement n'est pas définie
    exit("La clé secrète n'est pas définie dans les variables d'environnement.");
}
// Fonction pour générer un jeton d'authentification
function generateAuthToken($email)
{
    global $adminManager, $secretKey;
    $userId = $adminManager->getUserIdByEmail($email);
    $userRole = $adminManager->getUserRoleByEmail($email);
    var_dump($userRole);
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
        // Vérifier si le jeton JWT est vide
        if (!$jwt) {
            return null;
        }
        // Décoder le jeton JWT
        $decoded = JWT::decode($jwt, $secretKey, $secretKey('HS256'));
        var_dump($decoded);
        // Vérifier si le jeton JWT a expiré
        if (isset($decoded->exp) && $decoded->exp < time()) {
            throw new Exception("Le jeton JWT a expiré");
        }
        // Retourner le jeton décodé
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
            echo json_encode(["error" => "Le jeton JWT est invalide ou expiré"]);
            exit();
        }
    } else {
        http_response_code(401);
        echo json_encode(["error" => "Aucun jeton JWT trouvé dans l'en-tête Authorization"]);
        exit();
    }
}
