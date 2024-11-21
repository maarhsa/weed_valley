<?php

/**
 * Initialise la connexion à la base de données
 * @return PDO Instance PDO connectée à la base de données
 * @throws PDOException En cas d'erreur de connexion
 */
function getDatabaseConnection() {
    $host = 'localhost'; // Remplacez par votre hôte
    $dbname = 'weed_valley'; // Remplacez par le nom de votre base de données
    $username = 'root'; // Remplacez par votre utilisateur
    $password = ''; // Remplacez par votre mot de passe

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données : " . $e->getMessage());
    }
}
?>