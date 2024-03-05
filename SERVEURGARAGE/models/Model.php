<?php


$config['development'] = [
    'DB_HOST' => 'localhost',
    'DB_DATABASE' => 'DBGarageParrot',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'root'
];

$config['production'] = [
    'DB_HOST' => getenv('DATABASE_DNS'),
    'DB_DATABASE' => getenv('DATABASE_NAME'),
    'DB_USERNAME' => getenv('DATABASE_USER'),
    'DB_PASSWORD' => getenv('DATABASE_PASSWORD')
];


$env = getenv('APP_ENV');
$dbConfig = ($env === 'prod') ? $config['production'] : $config['development'];


// Déclaration de ma classe abstraite (càd jamais instenciable)
abstract class Model
{
    // je veux créer une instance de PDO unique
    private static $pdo;

    private static function setBdd()
    {
        global $dbConfig;
        $dns = 'mysql:host=' . $dbConfig['DB_HOST'] . ';dbname=' . $dbConfig['DB_DATABASE'] . ';charset=utf8';
        $user = $dbConfig['DB_USERNAME'];
        $password = $dbConfig['DB_PASSWORD'];

        // Connexion à la base de données
        self::$pdo = new PDO($dns, $user, $password);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_WARNING);
    }

    protected function getBdd()
    {
        // On vérifie si l'instance de PDO existe
        if (self::$pdo === null) {
            self::setBdd();
        }
        return self::$pdo;
    }
    // fonction statique appelable de n'importe où à partir de la classe "model" qui converti les informations en format JSON
    public static function sendJSON($info)
    {
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=UTF-8");
        echo json_encode($info);
    }
}
