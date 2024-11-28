<main>
	<div class="container">
	<h1><?= __('adm_dashboard_title', $translations) ?></h1>

    <!-- Filtres pour les périodes -->
    <div id="filters">
        <label for="period"><?= __('adm_display_graph', $translations) ?></label>
        <select id="period">
            <option value="day"><?= __('adm_dashboard_day', $translations) ?></option>
            <option value="week"><?= __('adm_dashboard_week', $translations) ?></option>
            <option value="month"><?= __('adm_dashboard_month', $translations) ?></option>
            <option value="year"<?= __('adm_dashboard_year', $translations) ?>></option>
        </select>
    </div>

    <!-- Conteneur des graphiques -->
    <div class="dashboard-container">
        <div class="chart-container">
            <canvas id="registrationsChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="activeUsersChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="bannedUsersChart"></canvas>
        </div>
        <div class="chart-container">
            <canvas id="totalUsersChart"></canvas>
        </div>
    </div>

    <script>
        // Fonction pour récupérer et mettre à jour les graphiques dynamiquement
        const periodSelector = document.getElementById('period');
        const fetchData = (period = 'day') => {
            fetch(`adm_dashboard.php?json=1&period=${period}`)
                .then(response => response.json())
                .then(stats => {
                    updateChart(registrationsChart, stats.registrations);
                    updateChart(activeUsersChart, stats.active_users);
                    updateChart(bannedUsersChart, stats.banned_users);
                    updateChart(totalUsersChart, stats.total_users);
                });
        };

        // Mettre à jour les graphiques avec de nouvelles données
        const updateChart = (chart, data) => {
            chart.data.labels = data.map(item => item.period);
            chart.data.datasets[0].data = data.map(item => item.count);
            chart.update();
        };

        // Initialisation des graphiques
        const createChart = (ctx, label, color) => {
            return new Chart(ctx, {
                type: 'line', // ou 'bar' selon vos préférences
                data: {
                    labels: [],
                    datasets: [{
                        label: label,
                        data: [],
                        borderColor: color,
                        backgroundColor: color + '33',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false, // Permet de contrôler la taille via CSS
                    plugins: {
                        legend: { position: 'top' },
                        tooltip: { mode: 'index', intersect: false }
                    },
                    scales: {
                        x: { title: { display: true, text: 'Period' } },
                        y: { title: { display: true, text: 'Number' }, beginAtZero: true }
                    }
                }
            });
        };

        // Créer les graphiques
        const registrationsChart = createChart(
            document.getElementById('registrationsChart').getContext('2d'),
            'Inscriptions',
            'rgba(75, 192, 192, 1)'
        );
        const activeUsersChart = createChart(
            document.getElementById('activeUsersChart').getContext('2d'),
            'Joueurs actifs',
            'rgba(255, 159, 64, 1)'
        );
        const bannedUsersChart = createChart(
            document.getElementById('bannedUsersChart').getContext('2d'),
            'Comptes bannis',
            'rgba(255, 99, 132, 1)'
        );
        const totalUsersChart = createChart(
            document.getElementById('totalUsersChart').getContext('2d'),
            'Total des utilisateurs',
            'rgba(54, 162, 235, 1)'
        );

        // Charger les données initiales et écouter les changements de filtre
        fetchData();
        periodSelector.addEventListener('change', () => fetchData(periodSelector.value));
    </script>
</main>
	<footer>