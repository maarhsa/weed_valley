<main>
	<div class="login">
		<h1><?= __('login', $translations) ?></h1>
		
		<form action="login.php" method="POST">
			<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    
			<label for="login"><?= __('login_username_label', $translations) ?></label>
			<input type="text" id="login" name="login" required>

			<label for="password"><?= __('login_password', $translations) ?></label>
			<input type="password" id="password" name="password" required>

			<button type="submit"><?= __('login_button', $translations) ?></button>
			
			<?php if (!empty($errors)): ?>
				<div class="error-messages">
					<?php foreach ($errors as $error): ?>
						<p style="color: red;"><?= htmlspecialchars($error); ?></p>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</main>
    <footer>