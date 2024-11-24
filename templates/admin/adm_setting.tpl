<main>
	<div class="container">
        <h1><?= __('adm_maintenance', $translations) ?></h1>
        
        <?php if (isset($success_message)) : ?>
            <p class="success-message"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <form method="POST" action="">
			<!-- Mode Maintenance -->
            <div class="form-group">
                <label for="maintenance_mode"><?= __('adm_activ_maintenance', $translations) ?></label>
                <input type="checkbox" id="maintenance_mode" name="maintenance_mode" 
					<?= !empty($settings) && $settings['maintenance_mode'] ? 'checked' : '' ?>>
            </div>
            <div class="form-group">
                <label for="maintenance_message"><?= __('adm_maintenance_message', $translations) ?></label>
                <textarea id="maintenance_message" name="maintenance_message" rows="4"><?= htmlspecialchars($current_settings['maintenance_message'] ?? '') ?></textarea>
            </div>
			
			<!-- Paramètres SMTP -->
            <h2><?= __('adm_setting_smtp', $translations) ?></h2>
			<!-- Activation PHPMailer -->
			<div class="form-group">
                <label for="email_activation_enabled"><?= __('adm_activate_phpmailer', $translations) ?></label>
                <input type="checkbox" id="email_activation_enabled" name="email_activation_enabled" <?= !empty($settings) && $settings['email_activation_enabled'] ? 'checked' : '' ?>>
            </div>
            <div class="form-group">
                <label for="smtp_host"><?= __('adm_host_smtp', $translations) ?></label>
                <input type="text" id="smtp_host" name="smtp_host" value="<?= !empty($settings) ? htmlspecialchars($settings['smtp_host']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="smtp_port"><?= __('adm_port_smtp', $translations) ?></label>
                <input type="number" id="smtp_port" name="smtp_port" value="<?= !empty($settings) ? htmlspecialchars($settings['smtp_port']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="smtp_username"><?= __('adm_user_smtp', $translations) ?></label>
                <input type="text" id="smtp_username" name="smtp_username" value="<?= !empty($settings) ? htmlspecialchars($settings['smtp_username']) : ''; ?>">
            </div>
            <div class="form-group">
                <label for="smtp_password"><?= __('adm_password_smtp', $translations) ?></label>
                <input type="password" id="smtp_password" name="smtp_password" value="">
            </div>
            <div class="form-group">
                <label for="smtp_secure"><?= __('adm_type_smtp', $translations) ?></label>
                <select id="smtp_secure" name="smtp_secure">
                    <option value="tls" <?= !empty($settings) && $settings['smtp_secure'] === 'tls' ? 'selected' : ''; ?>><?= __('adm_smtp_TLS', $translations) ?></option>
					<option value="ssl" <?= !empty($settings) && $settings['smtp_secure'] === 'ssl' ? 'selected' : ''; ?>><?= __('adm_smtp_SSL', $translations) ?></option>
                </select>
            </div>
			
            <button type="submit"><?= __('adm_update', $translations) ?></button>
        </form>
		<?php if (isset($success_message)): ?>
			<p style="color: green;"><?php echo htmlspecialchars($success_message); ?></p>
		<?php endif; ?>
    </div>
</main>
<footer>