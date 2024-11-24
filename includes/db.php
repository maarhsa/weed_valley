<?php

/**
 * Initialise la connexion à la base de données
 * @return PDO Instance PDO connectée à la base de données
 * @throws PDOException En cas d'erreur de connexion
 */
function getDatabaseConnection() {
    $host = 'localhost'; // Hôte
    $dbname = 'weed_valley'; // Nom de la base
    $username = 'root'; // Nom d'utilisateur
    $password = ''; // Mot de passe

    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
        return $pdo;
    } catch (PDOException $e) {
        // Log l'erreur pour une meilleure visibilité
        error_log("Erreur de connexion à la base de données : " . $e->getMessage());
        die("Impossible de se connecter à la base de données."); // Message utilisateur
    }
}
?>