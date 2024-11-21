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
$indexTemplatePath = __DIR__ . '/templates/home/index.tpl';
$footerTemplatePath = __DIR__ . '/templates/footer.tpl';

// Arrêter l'exécution si les fichiers ne sont pas trouvés
if (!file_exists($menuTemplatePath)) {
    die("Le fichier de template pour le menu est introuvable : $menuTemplatePath");
}

if (!file_exists($indexTemplatePath)) {
    die("Le fichier de template pour le contenu principal est introuvable : $indexTemplatePath");
}

if (!file_exists($footerTemplatePath)) {
    die("Le fichier de template pour le contenu footer est introuvable : $footerTemplatePath");
}


// Charger le menu
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

// Charger le contenu principal
ob_start();
include $indexTemplatePath;
$indexContent = ob_get_clean();

// Charger le contenu footer
ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $indexContent . $footerContent;
?>