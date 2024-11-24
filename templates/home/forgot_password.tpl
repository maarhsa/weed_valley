<main>
	<div class="register">
		<h1><?= __('forgot_password_title', $translations) ?></h1>
		
		<?php if (!empty($errors)): ?>
			<?php foreach ($errors as $error): ?>
				<p style="color: red;"><?= htmlspecialchars($error); ?></p>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if (!empty($success)): ?>
			<p style="color: green;"><?= htmlspecialchars($success); ?></p>
		<?php else: ?>
			<form method="POST" action="">
				<label for="email"><?= __('email_label', $translations) ?></label>
				<input type="email" id="email" name="email" required>
				<button type="submit"><?= __('submit_button', $translations) ?></button>
			</form>
		<?php endif; ?>
	</div>
</main>
    <footer>