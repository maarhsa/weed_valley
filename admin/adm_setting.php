<?php
// Démarrer la session et charger les dépendances
require_once '../includes/language.php'; // Charge les traductions et détecte la langue
require_once '../includes/functions.php'; // Utilitaire global
require_once '../includes/db.php'; // Connexion à la base de données

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/../templates/admin/adm_menu.tpl';
$adm_settingTemplatePath = __DIR__ . '/../templates/admin/adm_setting.tpl';
$footerTemplatePath = __DIR__ . '/../templates/footer.tpl';

// Initialiser la connexion à la base de données
$pdo = getDatabaseConnection(); // Vérifie que cette fonction est correctement définie

// Vérifier si l'utilisateur est connecté et s'il a un accès admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    // Rediriger vers le tableau de bord si l'utilisateur n'est pas admin
    header("Location: ../app/dashboard.php");
    exit;
}

// Fonction de maintenance et règlage SMTP
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $maintenance_mode = isset($_POST['maintenance_mode']) ? 1 : 0;
	$email_activation_enabled = isset($_POST['email_activation_enabled']) ? 1 : 0;
    $maintenance_message = $_POST['maintenance_message'] ?: $translations['maintenance_default_message'];
	$smtp_host = $_POST['smtp_host'] ?? 'smtp.example.com';
    $smtp_port = $_POST['smtp_port'] ?? 587;
    $smtp_username = $_POST['smtp_username'] ?? '';
    $smtp_password = $_POST['smtp_password'] ?? '';
    $smtp_secure = $_POST['smtp_secure'] ?? 'tls';
	
	// Hasher le mot de passe SMTP
	$hashed_smtp_password = password_hash($smtp_password, PASSWORD_DEFAULT);
	
    // Mettre à jour la base de données
    $stmt = $pdo->prepare("UPDATE settings 
        SET maintenance_mode = ?, 
            maintenance_message = ?, 
            email_activation_enabled = ?, 
            smtp_host = ?, 
            smtp_port = ?, 
            smtp_username = ?, 
            smtp_password = ?, 
            smtp_secure = ?");
	$stmt->execute([$maintenance_mode, $maintenance_message, $email_activation_enabled, $smtp_host, $smtp_port, $smtp_username, $hashed_smtp_password, $smtp_secure]);

    // Message de succès
    $success_message = $translations['maintenance_success_message'];
}

// Récupérer les paramètres actuels
$stmt = $pdo->query("SELECT maintenance_mode, maintenance_message, email_activation_enabled, smtp_host, smtp_port, smtp_username, smtp_password, smtp_secure FROM settings LIMIT 1");
$settings = $stmt->fetch(PDO::FETCH_ASSOC);

// Si la table est vide, initialiser des valeurs par défaut
if (!$settings) {
    $settings = [
        'maintenance_mode' => 0,
        'email_activation_enabled' => 0,
        'smtp_host' => '',
        'smtp_port' => 587,
        'smtp_username' => '',
        'smtp_password' => '',
        'smtp_secure' => 'tls',
    ];
}

// Charger le menu
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

// Charger le contenu principal
ob_start();
include $adm_settingTemplatePath;
$adm_settingContent = ob_get_clean();

// Charger le contenu footer
ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $adm_settingContent . $footerContent;
?>