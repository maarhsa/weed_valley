<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <title><?= __('game_name', $translations) ?></title>
    <link rel="stylesheet" href="/assets/css/adm_styles.css">
	<link rel="stylesheet" href="/assets/css/adm_graph.css">
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
	<style>
        /* Conteneur principal pour les graphiques */
        .dashboard-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }

        /* Style pour chaque graphique */
        .chart-container {
            width: 250px;
            height: 250px;
        }

        /* Ajout d'un espacement autour des filtres */
        #filters {
            text-align: center;
            margin-bottom: 20px;
        }
    </style>
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