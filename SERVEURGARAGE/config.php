<?php

// Charge les variables d'environnement à partir du fichier .env
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envVars = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envVars as $envVar) {
        // Ignorer les lignes commençant par #
        if (strpos($envVar, '#') === 0) {
            continue;
        }
        // Sépare la variable et sa valeur
        list($name, $value) = explode('=', $envVar, 2);
        // Définir la variable d'environnement
        putenv("$name=$value");
    }
}

// Configuration de la base de données
$developmentConfig = [
    'DB_HOST' => getenv('DATABASE_DNS'),
    'DB_DATABASE' => getenv('DATABASE_NAME'),
    'DB_USERNAME' => getenv('DATABASE_USER'),
    'DB_PASSWORD' => getenv('DATABASE_PASSWORD')
];

$productionConfig = [
    'DB_HOST' => getenv('DATABASE_DNS'),
    'DB_DATABASE' => getenv('DATABASE_NAME'),
    'DB_USERNAME' => getenv('DATABASE_USER'),
    'DB_PASSWORD' => getenv('DATABASE_PASSWORD')
];

$env = getenv('APP_ENV');
$dbConfig = ($env === 'development') ? $developmentConfig : $productionConfig;
