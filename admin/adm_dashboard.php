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

// Connexion à la base de données
$pdo = getDatabaseConnection();

// Obtenir la période à partir des paramètres GET (par défaut : jour)
$period = $_GET['period'] ?? 'day';

// Définir les intervalles en fonction de la période
switch ($period) {
    case 'week':
        $dateFormat = "YEARWEEK(created_at, 1)";
        $interval = "1 MONTH";
        break;
    case 'month':
        $dateFormat = "DATE_FORMAT(created_at, '%Y-%m')";
        $interval = "1 YEAR";
        break;
    case 'year':
        $dateFormat = "YEAR(created_at)";
        $interval = "10 YEAR";
        break;
    case 'day':
    default:
        $dateFormat = "DATE(created_at)";
        $interval = "7 DAY";
        break;
}

// Récupérer les données pour les graphiques
$stats = [];

// Nombres d'inscriptions
$stats['registrations'] = $pdo->query("
    SELECT $dateFormat AS period, COUNT(*) AS count 
    FROM users 
    WHERE created_at >= CURDATE() - INTERVAL $interval
    GROUP BY period
    ORDER BY period
")->fetchAll(PDO::FETCH_ASSOC);

// Nombres de joueurs actifs
$stats['active_users'] = $pdo->query("
    SELECT $dateFormat AS period, COUNT(*) AS count
    FROM users 
    WHERE last_login >= CURDATE() - INTERVAL $interval
    GROUP BY period
    ORDER BY period
")->fetchAll(PDO::FETCH_ASSOC);

// Nombres de comptes bannis
$stats['banned_users'] = $pdo->query("
    SELECT $dateFormat AS period, COUNT(*) AS count
    FROM users
    WHERE banned = 1 AND created_at >= CURDATE() - INTERVAL $interval
    GROUP BY period
    ORDER BY period
")->fetchAll(PDO::FETCH_ASSOC);

// Nombres total d'utilisateurs
$stats['total_users'] = $pdo->query("
    SELECT $dateFormat AS period, COUNT(*) AS count
    FROM users
    WHERE created_at >= CURDATE() - INTERVAL $interval
    GROUP BY period
    ORDER BY period
")->fetchAll(PDO::FETCH_ASSOC);

// Renvoyer les données JSON si demandé
if (isset($_GET['json']) && $_GET['json'] === '1') {
    header('Content-Type: application/json');
    echo json_encode($stats);
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