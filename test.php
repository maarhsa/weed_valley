<?php
require_once 'includes/db.php';

try {
    $pdo = getDatabaseConnection();
    echo "Connexion réussie à la base de données.";
} catch (Exception $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
