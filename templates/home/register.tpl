<main>
	<div class="register">
		<h1><?= __('register', $translations) ?></h1>
		
		<form action="register.php" method="POST">
			<input type="hidden" name="csrf_token" value="<?= htmlspecialchars(generateCsrfToken(), ENT_QUOTES, 'UTF-8') ?>">
    
			<label for="username"><?= __('username', $translations) ?> :</label>
			<input type="text" id="username" name="username" required>

			<label for="email"><?= __('email', $translations) ?> :</label>
			<input type="email" id="email" name="email" required>

			<label for="confirm_email"><?= __('confirm_email', $translations) ?> :</label>
			<input type="email" id="confirm_email" name="confirm_email" required>

			<label for="password"><?= __('password', $translations) ?> :</label>
			<input type="password" id="password" name="password" required>

			<label for="confirm_password"><?= __('confirm_password', $translations) ?> :</label>
			<input type="password" id="confirm_password" name="confirm_password" required>

			<label>
				<input type="checkbox" name="terms" required> <?= __('accept_terms', $translations) ?>
			</label></br>

			<button type="submit"><?= __('register_button', $translations) ?></button></br>
			
			<label>
				<?= __('have_account', $translations) ?> <a href="login.php"><?= __('sign In', $translations) ?></a>
			</label>
		</form>
	</div>
</main>
    <footer>