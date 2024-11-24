<?php
require_once 'includes/language.php'; // Charge les traductions
require_once 'includes/functions.php'; // Fonctions utilitaires
require_once 'includes/db.php'; // Connexion à la base de données
require_once 'plugins/PHPMailer/PHPMailer.php'; // PHPMailer

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require_once 'plugins/PHPMailer/SMTP.php';
require_once 'plugins/PHPMailer/Exception.php';

// Passer les traductions à la vue
$lang = $_SESSION['lang'] ?? 'en'; // Langue par défaut si non définie
$translations = loadTranslations($lang);

// Définir les chemins des templates
$menuTemplatePath = __DIR__ . '/templates/home/menu.tpl';
$forgot_passwordTemplatePath = __DIR__ . '/templates/home/forgot_password.tpl';
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

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = htmlspecialchars(trim($_POST['email']), ENT_QUOTES, 'UTF-8');

    // Vérifier si l'email est valide
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = $translations['invalid_email'];
    } else {
        // Vérifier si l'email existe dans la base
        $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            $errors[] = $translations['email_not_found'];
        } else {
            // Générer un token de réinitialisation
            $resetToken = bin2hex(random_bytes(16));

            // Enregistrer le token dans la base
            $stmt = $pdo->prepare("UPDATE users SET reset_token = ? WHERE id = ?");
            $stmt->execute([$resetToken, $user['id']]);

            // Vérifier si l'envoi d'email est activé
            $stmt = $pdo->query("SELECT email_activation_enabled, smtp_host, smtp_port, smtp_username, smtp_password, smtp_secure FROM settings");
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($settings['email_activation_enabled']) {
                try {
                    // Préparer le sujet et le corps de l'email
                    $subject = $translations['reset_email_subject'] ?? 'Réinitialisation de votre mot de passe';
                    $resetLink = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . '/reset_password.php?token=' . urlencode($resetToken);
                    $body = str_replace(
                        ['{reset_link}'],
                        [$resetLink],
                        $translations['reset_email_body'] ?? 'Bonjour, <br><br>Cliquez sur le lien suivant pour réinitialiser votre mot de passe : <a href="{reset_link}">Réinitialiser mon mot de passe</a><br><br>Merci.'
                    );

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
                    $mail->addAddress($email);

                    $mail->isHTML(true);
                    $mail->Subject = $subject;
                    $mail->Body = $body;

                    $mail->send();
                    $success = $translations['reset_email_sent'];
                } catch (Exception $e) {
                    $errors[] = $translations['reset_email_error'];
                }
            } else {
                $success = $translations['reset_email_disabled'];
            }
        }
    }
}

// Charger les templates
ob_start();
include $menuTemplatePath;
$menuContent = ob_get_clean();

ob_start();
include $forgot_passwordTemplatePath;
$forgot_passwordContent = ob_get_clean();

ob_start();
include $footerTemplatePath;
$footerContent = ob_get_clean();

// Afficher la page complète
echo $menuContent . $forgot_passwordContent . $footerContent;
?>