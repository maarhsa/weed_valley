<?php
// Démarrer la session et charger les dépendances
require_once 'includes/language.php'; 
require_once 'includes/functions.php'; 
require_once 'includes/db.php'; 
require_once 'plugins/PHPMailer/PHPMailer.php'; 

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once 'plugins/PHPMailer/SMTP.php';
require_once 'plugins/PHPMailer/Exception.php';

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
        die($translations['csrf_error'] ?? 'Erreur CSRF inconnue');
    }

    // Récupération et validation des données
    $username = htmlspecialchars(trim($_POST['username']), ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');
    $confirm_email = htmlspecialchars(trim($_POST['confirm_email']), ENT_QUOTES, 'UTF-8');
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $terms_accepted = isset($_POST['terms']);

    $errors = [];

    // Validation des données
    if ($email !== $confirm_email) {
        $errors[] = $translations['error_email_mismatch'];
    }
    if ($password !== $confirm_password) {
        $errors[] = $translations['error_password_mismatch'];
    }
    if (!$terms_accepted) {
        $errors[] = $translations['error_terms_not_accepted'];
    }

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
        $stmt = $pdo->query("SELECT email_activation_enabled, smtp_host, smtp_port, smtp_username, smtp_password, smtp_secure FROM settings");
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        $email_activation_enabled = $settings['email_activation_enabled'];

        $is_active = $email_activation_enabled ? 0 : 1;

        try {
            // Insertion dans la base de données
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, activation_code, active) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$username, $email, $hashedPassword, $activation_code, $is_active]);

            if ($email_activation_enabled) {
                try {
                    // Construire l'URL d'activation dynamique
                    $base_url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'];
                    $activation_link = $base_url . '/activate.php?code=' . urlencode($activation_code);

                    // Configurer PHPMailer
                    $mail = new PHPMailer(true);
                    $mail->isSMTP();
                    $mail->Host = $settings['smtp_host'];
                    $mail->SMTPAuth = true;
                    $mail->Username = $settings['smtp_username'];
                    $mail->Password = $settings['smtp_password'];
                    $mail->SMTPSecure = $settings['smtp_secure'];
                    $mail->Port = $settings['smtp_port'];

                    $mail->setFrom($settings['smtp_username'], 'Weed Valley');
                    $mail->addAddress($email, $username);

                    // Préparer le contenu de l'email
                    $subject = $translations['activation_email_subject'] ?? 'Activating your Weed Valley account';
                    $body = str_replace(
                        ['{username}', '{activation_link}'],
                        [$username, $activation_link],
                        $translations['activation_email_body'] ?? 'Hello {username},<br><br>Click on the following link to activate your account:<br><a href="{activation_link}">Activate my account</a><br><br>Thank you for us join !'
                    );

                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;

                    $mail->send();
                    echo $translations['registration_success_email'];
                } catch (Exception $e) {
                    error_log("Erreur PHPMailer : " . $e->getMessage());
                    echo $translations['registration_email_error'];
                }
            } else {
                echo $translations['registration_success'];
				echo "<p>" . ($translations['redirect_message']) . "</p>";
                header("Refresh: 3; url=index.php");
                exit;
            }
        } catch (PDOException $e) {
            error_log("Erreur SQL : " . $e->getMessage());
            if ($e->getCode() === '23000') {
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

echo $menuContent . $registerContent . $footerContent;
?>