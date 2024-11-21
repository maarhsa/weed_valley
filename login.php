<?php
// Démarrer la session et charger les dépendances
require_once 'includes/language.php'; // Charge les traductions et détecte la langue
require_once 'includes/functions.php'; // Utilitaire global
require_once 'includes/db.php'; // Connexion à la base de données

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/templates/home/menu.tpl';
$loginTemplatePath = __DIR__ . '/templates/home/login.tpl';
$footerTemplatePath = __DIR__ . '/templates/footer.tpl';

// Connexion à la base de données
$pdo = getDatabaseConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification CSRF
    if (!validateCsrfToken($_POST['csrf_token'])) {
        $errors[] = $translations['csrf_error'];
    }

    // Validation des données
    $login = htmlspecialchars(trim($_POST['login']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);

    if (empty($login) || empty($password)) {
        $errors[] = $translations['login_error_empty'];
    }

    // Recherche utilisateur (email ou username)
    if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    }
    $stmt->execute([$login]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification du mot de passe
    if ($user && password_verify($password, $user['password'])) {
        // Vérifier si le site est en maintenance
        $stmt = $pdo->query("SELECT maintenance_mode FROM settings LIMIT 1");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($settings['maintenance_mode'] && $user['id'] != 1) {
            // Si maintenance et utilisateur non administrateur
            $errors[] = $translations['maintenance_login_blocked'];
        } else {
            // Connexion réussie
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            // Redirection
            header("Location: " . ($user['id'] == 1 ? "admin/adm_dashboard.php" : "app/app_dashboard.php"));
            exit();
        }
    } else {
        $errors[] = $translations['login_failed'];
    }
}

// Charger les templates
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

ob_start();
include $loginTemplatePath;
$loginContent = ob_get_clean();

ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $loginContent . $footerContent;
?>