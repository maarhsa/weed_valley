<?php
require_once 'includes/language.php';
require_once 'includes/functions.php';
require_once 'includes/db.php';

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/templates/home/menu.tpl';
$reset_passwordTemplatePath = __DIR__ . '/templates/home/reset_password.tpl';
$footerTemplatePath = __DIR__ . '/templates/footer.tpl';

// Connexion à la base de données 
$pdo = getDatabaseConnection();

$errors = [];
$success = '';

if (isset($_GET['token'])) {
    $resetToken = htmlspecialchars($_GET['token'], ENT_QUOTES, 'UTF-8');

    // Vérifier si le token est valide
    $stmt = $pdo->prepare("SELECT id FROM users WHERE reset_token = ?");
    $stmt->execute([$resetToken]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        $errors[] = $translations['invalid_token'];
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirm_password']);

        if ($password !== $confirmPassword) {
            $errors[] = $translations['password_mismatch'];
		} elseif (strlen($password) < 8) {
            $errors[] = $translations['password_too_short'];
        } else {
            // Mettre à jour le mot de passe et supprimer le token
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ?, reset_token = NULL WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);

			// Message de succès
            $success = $translations['password_reset_success'];
			
			// Ajouter une redirection après 3 secondes
            echo "<p>{$success}</p>";
            echo "<p>" . ($translations['redirect_message']) . "</p>";
            header("Refresh: 3; url=index.php");
            exit;
        }
    }
} else {
    $errors[] = $translations['no_token'];
}

// Charger les templates
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

ob_start();
include $reset_passwordTemplatePath;
$reset_passwordContent = ob_get_clean();

ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $reset_passwordContent . $footerContent;
?>