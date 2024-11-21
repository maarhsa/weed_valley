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
$adm_dashboardTemplatePath = __DIR__ . '/../templates/admin/adm_dashboard.tpl';
$footerTemplatePath = __DIR__ . '/../templates/footer.tpl';

// Vérifier si l'utilisateur est connecté et s'il a un accès admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_id'] != 1) {
    // Rediriger vers le tableau de bord si l'utilisateur n'est pas admin
    header("Location: ../app/dashboard.php");
    exit;
}


// Charger le menu
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

// Charger le contenu principal
ob_start();
include $adm_dashboardTemplatePath;
$adm_dashboardContent = ob_get_clean();

// Charger le contenu footer
ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $adm_dashboardContent . $footerContent;
?>