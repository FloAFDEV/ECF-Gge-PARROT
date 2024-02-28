

<?php
$config['development'] = [
    'DB_HOST' => 'localhost',
    'DB_DATABASE' => 'DBGarageParrot',
    'DB_USERNAME' => 'root',
    'DB_PASSWORD' => 'root'
];

$config['production'] = [
    $dns => getenv('DATABASE_DNS'),
    $dbname => getenv('DATABASE_NAME'),
    $user => getenv('DATABASE_USER'),
    $password => getenv('DATABASE_PASSWORD')


];
// Déclaration de ma classe abstraite (càd jamais instenciable)
abstract class Model
{
    // je veux créer une instance de PDO unique
    private static $pdo;

    private static function setBdd()
    {
        // Connexion à la base de données
        self::$pdo = new PDO("mysql:host=localhost;dbname=DBGarageParrot;charset=utf8", "root", "root");
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
