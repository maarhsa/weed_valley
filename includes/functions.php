<?php

/**
 * Détecte la langue préférée du client à partir des langues disponibles
 * @param array $availableLanguages Langues disponibles (ex : ['en', 'fr', 'es'])
 * @param string $default Langue par défaut si aucune n'est détectée
 * @return string Langue détectée ou par défaut
 */
function detectLanguage(array $availableLanguages, $default = 'en') {
    if (!empty($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        $langs = explode(',', $_SERVER['HTTP_ACCEPT_LANGUAGE']);
        foreach ($langs as $lang) {
            $lang = substr($lang, 0, 2);
            if (in_array($lang, $availableLanguages)) {
                return $lang;
            }
        }
    }
    return $default;
}

/**
 * Charge les traductions en fonction de la langue spécifiée
 * @param string $lang Langue à charger (ex : 'en', 'fr')
 * @return array Tableau associatif des traductions
 */
function loadTranslations($lang) {
    $filePath = __DIR__ . "/../lang/{$lang}.php";
    if (file_exists($filePath)) {
        return include $filePath;
    }
    // Fallback to English si la langue demandée n'existe pas
    return include __DIR__ . '/../lang/en.php';
}

/**
 * Fonction générique pour afficher une variable traduite
 * @param string $key Clé de la traduction
 * @param array $translations Tableau des traductions
 * @return string Traduction correspondante ou clé brute si introuvable
 */
function __($key, $translations) {
    return $translations[$key] ?? $key;
}

// Protection CSRF
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Fonction de maintenance du site
function is_maintenance_mode() {
    global $pdo; // Assurez-vous que la connexion PDO est accessible.
    
    $stmt = $pdo->query("SELECT maintenance_mode, maintenance_message FROM settings LIMIT 1");
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($result && $result['maintenance_mode'] == 1) {
        return $result['maintenance_message'];
    }
    return false;
}
?>