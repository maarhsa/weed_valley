document.addEventListener('DOMContentLoaded', () => {
    const versionList = document.getElementById('version-list');

    // Générer dynamiquement les lignes du tableau
    versions.forEach((version, index) => {
        // Créer la ligne principale (Version + Date)
        const versionRow = document.createElement('tr');
        versionRow.classList.add('version-row');
        versionRow.dataset.index = index; // Associer un index pour identifier la version
        versionRow.innerHTML = `
            <td>${version.version_number}</td>
            <td>${version.release_date}</td>
        `;
        versionList.appendChild(versionRow);

        // Créer la ligne des modifications (masquée par défaut)
        const modificationsRow = document.createElement('tr');
        modificationsRow.classList.add('modifications-row');
        modificationsRow.style.display = 'none'; // Masqué initialement
        modificationsRow.innerHTML = `
            <td colspan="2">
                <ul>
                    ${version.modifications.map(mod => `<li>${mod}</li>`).join('')}
                </ul>
            </td>
        `;
        versionList.appendChild(modificationsRow);

        // Ajouter l'événement de clic pour afficher/masquer les modifications
        versionRow.addEventListener('click', () => {
            const isVisible = modificationsRow.style.display === 'table-row';
            modificationsRow.style.display = isVisible ? 'none' : 'table-row';
        });
    });
});
