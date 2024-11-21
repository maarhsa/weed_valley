<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= __('game_name', $translations) ?></title>
    <link rel="stylesheet" href="/assets/css/styles.css">
</head>
<body>
   <nav class="container">
      <ul>
         <li class="logo"><img src="assets/images/logo/Weed_Valley_Logo.png" alt="Weed Valley Logo"></li>
         <li class="items"><a href="index.php"><?= __('home', $translations) ?></a></li>
         <li class="items"><a href="#"><?= __('rules', $translations) ?></a></li>
		 <li class="items"><a href="login.php"><?= __('login', $translations) ?></a></li>
		 <li class="items"><a href="register.php"><?= __('register', $translations) ?></a></li>
         <li class="items"><a href="https://discord.com/"><?= __('contact', $translations) ?></a></li>
         <li class="items"><a href="game_versions.php"><?= __('menu_game_versions', $translations) ?></a></li>
         <li class="btn"><a href="#"><i class="fas fa-bars"></i></a></li>
		 <li class="language-menu">
            <form method="get" action="index.php" class="language-form">
                <select name="lang" onchange="this.form.submit()" class="language-selector">
                    <option value="fr" {selected_fr}>Français</option>
                    <option value="en" {selected_en}>English</option>
                    <option value="es" {selected_es}>Español</option>
                    <option value="de" {selected_de}>Deutsch</option>
                    <option value="it" {selected_it}>Italiano</option>
                    <option value="nl" {selected_nl}>Nederlands</option>
                    <option value="pt" {selected_pt}>Português</option>
                </select>
            </form>
         </li>
      </ul>
   </nav>
</body>
</html>