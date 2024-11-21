<?php
session_start();
require_once __DIR__ . '/functions.php';

// Définir les langues disponibles
$availableLanguages = ['fr', 'en', 'es', 'de', 'it', 'nl', 'pt'];

// Détecter ou charger la langue
if (!empty($_GET['lang']) && in_array($_GET['lang'], $availableLanguages)) {
    $_SESSION['lang'] = $_GET['lang'];
} elseif (empty($_SESSION['lang'])) {
    $_SESSION['lang'] = detectLanguage($availableLanguages);
}

// Charger les traductions
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);
?>