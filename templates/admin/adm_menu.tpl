<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= __('game_name', $translations) ?></title>
    <link rel="stylesheet" href="/assets/css/adm_styles.css">
</head>
<body>
   <nav class="container">
      <ul>
         <li class="items"><a href="adm_dashboard.php"><?= __('adm_dashboard', $translations) ?></a></li>
         <li class="items"><a href="adm_manage_users.php"><?= __('adm_manage_users', $translations) ?></a></li>
		 <li class="items"><a href="adm_setting.php"><?= __('adm_setting', $translations) ?></a></li>
         <li class="items"><a href=""><?= __('', $translations) ?></a></li>
         <li class="items"><a href="../logout.php"><?= __('logout', $translations) ?></a></li>
      </ul>
   </nav>
</body>
</html>