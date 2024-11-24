<main>
	<div class="register">
		<h1><?= __('reset_password_title', $translations) ?></h1>
    
		<?php if (!empty($errors)): ?>
			<?php foreach ($errors as $error): ?>
				<p style="color: red;"><?= htmlspecialchars($error); ?></p>
			<?php endforeach; ?>
		<?php endif; ?>

		<?php if (!empty($success)): ?>
			<p style="color: green;"><?= htmlspecialchars($success); ?></p>
		<?php else: ?>
			<form method="POST" action="">
				<label for="password"><?= __('new_password_label', $translations) ?></label>
				<input type="password" id="password" name="password" required>
				<label for="confirm_password"><?= __('confirm_password_label', $translations) ?></label>
				<input type="password" id="confirm_password" name="confirm_password" required>
				<button type="submit"><?= __('reset_button', $translations) ?></button>
			</form>
		<?php endif; ?>
	</div>
</main>
    <footer>