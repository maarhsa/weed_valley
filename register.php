<?php
// Démarrer la session et charger les dépendances
require_once 'includes/language.php'; // Charge les traductions et détecte la langue
require_once 'includes/functions.php'; // Utilitaire global
require_once 'includes/db.php'; // Connexion à la base de données
require_once 'plugins/PHPMailer/PHPMailer.php'; // Inclure PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Passer les traductions à la vue
$lang = $_SESSION['lang'];
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/templates/home/menu.tpl';
$registerTemplatePath = __DIR__ . '/templates/home/register.tpl';
$footerTemplatePath = __DIR__ . '/templates/footer.tpl';

// Connexion à la base de données 
$pdo = getDatabaseConnection();

// Maintenance mode
$message = is_maintenance_mode();
if ($message) {
    die("<div style='text-align: center; margin-top: 20%; font-family: Arial;'>
            <h1>{$translations['maintenance_title']}</h1>
            <p>{$translations['maintenance_message']}</p>
        </div>");
}

// Début du système d'enregistrement
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérification du token CSRF
    if (!validateCsrfToken($_POST['csrf_token'])) {
        die($translations['csrf_error'] ?? 'Erreur CSRF inconnue'); // Utilise une valeur par défaut en cas de problème avec les traductions
    }

    // Récupération et validation des données
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $confirm_email = htmlspecialchars(trim($_POST['confirm_email']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms_accepted = isset($_POST['terms']);

    // Tableau pour les messages d'erreur
    $errors = [];

    // Validation de la correspondance des emails
    if ($email !== $confirm_email) {
        $errors[] = $translations['error_email_mismatch'];
    }

    // Validation de la correspondance des mots de passe
    if ($password !== $confirm_password) {
        $errors[] = $translations['error_password_mismatch'];
    }

    // Vérification de l'acceptation des conditions
    if (!$terms_accepted) {
        $errors[] = $translations['error_terms_not_accepted'];
    }

    // Si des erreurs sont présentes, on les affiche
    if (!empty($errors)) {
        foreach ($errors as $error) {
            echo "<p style='color:red;'>$error</p>";
        }
    } else {
        // Hachage sécurisé du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Génération du code d'activation
        $activation_code = bin2hex(random_bytes(16));

        // Vérifier si l'activation par email est activée
        $stmt = $pdo->query("SELECT email_activation_enabled FROM settings");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        $email_activation_enabled = $settings['email_activation_enabled'];

        // L'utilisateur sera actif uniquement si l'activation par email est désactivée
        $is_active = $email_activation_enabled ? 0 : 1;

        // Insertion sécurisée des données dans la base
        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, activation_code, active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword, $activation_code, $is_active]);

            // Si l'activation par email est activée, envoyer un email
            if ($email_activation_enabled) {
                try {
                    // Récupérer les paramètres SMTP depuis la base de données
					$stmt = $pdo->query("SELECT smtp_host, smtp_port, smtp_username, smtp_password, smtp_secure FROM settings");
					$smtp_settings = $stmt->fetch(PDO::FETCH_ASSOC);

					$mail = new PHPMailer(true);
					$mail->isSMTP();
					$mail->Host = $smtp_settings['smtp_host'];
					$mail->SMTPAuth = true;
					$mail->Username = $smtp_settings['smtp_username'];
					$mail->Password = $smtp_settings['smtp_password'];
					$mail->SMTPSecure = $smtp_settings['smtp_secure'];
					$mail->Port = $smtp_settings['smtp_port'];

					$mail->setFrom($smtp_settings['smtp_username'], 'Weed Valley');
					$mail->addAddress($email, $username);

					// Contenu de l'email
					$mail->isHTML(true);
					$mail->Subject = 'Activation de votre compte Weed Valley';
					$mail->Body = "Bonjour $username,<br><br>Cliquez sur le lien suivant pour activer votre compte :<br>
						<a href='https://yourdomain.com/activate.php?code=$activation_code'>Activer mon compte</a><br><br>Merci de nous rejoindre !";

					$mail->send();
					echo $translations['registration_success_email'];
				} catch (Exception $e) {
					echo $translations['registration_email_error'];
				}
			}
            } else {
                echo $translations['registration_success'];
            }
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') { // Violation de contrainte d'unicité
                echo $translations['registration_error_username_taken'];
            } else {
                echo $translations['registration_error'];
            }
        }
    }
}

// Charger les templates
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

ob_start();
include $registerTemplatePath;
$registerContent = ob_get_clean();

ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $registerContent . $footerContent;
?>