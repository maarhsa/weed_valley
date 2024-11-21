<?php
require_once 'includes/db.php';

$pdo = getDatabaseConnection();

if (isset($_GET['code'])) {
    $activation_code = $_GET['code'];

    $stmt = $pdo->prepare("SELECT id FROM users WHERE activation_code = ? AND active = 0");
    $stmt->execute([$activation_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        $stmt = $pdo->prepare("UPDATE users SET active = 1, activation_code = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        echo "Votre compte a été activé avec succès !";
    } else {
        echo "Lien d'activation invalide ou compte déjà activé.";
    }
}
?>