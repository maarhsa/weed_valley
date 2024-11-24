<?php
// Inclure les dépendances nécessaires
require_once 'includes/db.php';
require_once 'includes/language.php'; // Assurez-vous que ce fichier contient la fonction loadTranslations()

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Connexion à la base de données
$pdo = getDatabaseConnection();

// Vérifier si le code d'activation est fourni
if (isset($_GET['code'])) {
    $activation_code = htmlspecialchars($_GET['code'], ENT_QUOTES, 'UTF-8');

    // Rechercher un utilisateur correspondant au code d'activation
    $stmt = $pdo->prepare("SELECT id FROM users WHERE activation_code = ? AND active = 0");
    $stmt->execute([$activation_code]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Activer l'utilisateur et supprimer le code d'activation
        $stmt = $pdo->prepare("UPDATE users SET active = 1, activation_code = NULL WHERE id = ?");
        $stmt->execute([$user['id']]);
        
        // Afficher le message de succès
        echo $translations['account_activated'];

        // Redirection automatique après 3 secondes
        header("Refresh: 3; url=index.php");
        exit;
    } else {
        // Message d'erreur si le code est invalide ou déjà utilisé
        echo $translations['link_activation_error'];
        
        // Redirection automatique après 3 secondes
        header("Refresh: 3; url=index.php");
        exit;
    }
} else {
    // Message d'erreur si aucun code n'est fourni
    echo $translations['missing_activation_code'];
    
    // Redirection automatique après 3 secondes
    header("Refresh: 3; url=index.php");
    exit;
}
?>