<main>
	<div class="game-versions">
		<h1><?= __('hystory_game_versions', $translations) ?></h1>
		<table>
			<thead>
				<tr>
					<th><?= __('game_versions', $translations) ?></th>
					<th><?= __('date_versions', $translations) ?></th>
				</tr>
			</thead>
			<tbody id="version-list">
				<!-- Les lignes seront ajoutées dynamiquement par JavaScript -->
			</tbody>
		</table>
	</div>
<script src="../assets/js/game_versions.js"></script>
</main>
    <footer>