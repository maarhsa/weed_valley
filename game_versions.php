<?php
// Démarrer la session et charger les dépendances
require_once 'includes/language.php'; // Charge les traductions et détecte la langue

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/templates/home/menu.tpl';
$gameversionsTemplatePath = __DIR__ . '/templates/home/game_versions.tpl';
$footerTemplatePath = __DIR__ . '/templates/footer.tpl';

// Données des versions du jeu
$versions = [
    [
        'version_number' => '0.2',
        'modifications' => [
			'00035: ',
			'00034: ',
			'00033: ',
			'00032: ',
			'00031: ',
			'00030: ',
			'00029: ',
			'00028: ',
			'00027: ',
			'00026: ',
            '00025: ',
            '00024: Creating an account recovery system.'
        ],
        'release_date' => 'Unknown'
    ],
    [
        'version_number' => '0.1',
        'modifications' => [
			'00023: Fix SMTP password hash.',
			'00022: Setting up PHPMailer directly from the administration panel and saving it to the database.',
			'00021: Activating PHPMailer from the admin panel.',
			'00020: PHPMailer integration for account verification email.',
			'00019: Personalized message from the administration panel for maintenance mode and recording in the database.',
			'00018: Enabling maintenance mode from the administrator panel.',
			'00017: Fix system login for administrator if maintenance mode is active.',
			'00016: Connection blocked if maintenance mode is active except for the administrator.',
			'00015: Blocking registrations if maintenance mode is active.',
			'00014: Creation of a maintenance system.',
			'00013: Creating content for index.',
			'00012: Creation of an administration panel with restricted access.',
			'00011: Creation of the secure account registration system (sql injections, csrf, xss, hashed password).',
			'00010: Adding social media logo with links in the footer.',
			'00009: Added game logo to menu.',
			'00008: Creation of the database as well as the configuration file.',
			'00007: Correcting the display of game versions with json script.',
			'00006: Creating a page for game releases.',
			'00005: Added language choice.',
            '00004: Design for development.',
            '00003: Language detection system and default setting in English.',
            '00002: Design of the index page with menu and separate footer.',
            '00001: Project start-up.'
        ],
        'release_date' => '2024-11-18'
    ]
];

// Convertir les données en JSON pour les utiliser avec JavaScript
$jsonVersions = json_encode($versions);

// Charger le menu
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

// Charger le contenu principal
ob_start();
include $gameversionsTemplatePath;
$gameversionsContent = ob_get_clean();

// Charger le contenu footer
ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $gameversionsContent . $footerContent;
?>
<script>
    const versions = <?= $jsonVersions ?>;
</script>