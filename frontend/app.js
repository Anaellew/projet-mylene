document.getElementById('searchForm').addEventListener('submit', function(event) {
    event.preventDefault();
    
    // Récupérer la valeur du champ de recherche
    const searchQuery = document.getElementById('searchQuery').value;

    // Afficher la valeur de la requête pour vérifier
    console.log('Requête de recherche :', searchQuery);

    // Envoyer la requête à l'API PHP via Fetch API
    fetch(`../backend/recherche.php?search=${encodeURIComponent(searchQuery)}`)
        .then(response => {
            if (!response.ok) {
                // Si la réponse n'est pas OK, afficher un message d'erreur
                throw new Error('Erreur de réponse du serveur');
            }
            return response.json();
        })
        .then(data => {
            console.log('Données reçues :', data);  // Affiche les données reçues

            const resultsContainer = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
            resultsContainer.innerHTML = ''; // Réinitialiser les résultats précédents

            // Vérifier si des résultats ont été trouvés
            if (data.results.length > 0) {
                data.results.forEach(item => {
                    // Créer une nouvelle ligne de tableau
                    const row = document.createElement('tr');

                    // Ajouter des cellules à la ligne pour chaque colonne
                    row.innerHTML = `
                        <td>${item.titre}</td>
                        <td>${item.album}</td>
                        <td>${item.annee}</td>
                        <td>${item.duree}</td>
                        <td>${item.paroles}</td>
                        <td>${item.compositeur}</td>
                        <td>${item.auteur}</td>
                    `;

                    // Ajouter la ligne au tableau
                    resultsContainer.appendChild(row);
                });
            } else {
                // Si aucun résultat n'est trouvé
                const row = document.createElement('tr');
                row.innerHTML = '<td colspan="7">Aucun résultat trouvé.</td>';
                resultsContainer.appendChild(row);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);  // Afficher l'erreur dans la console
            const resultsContainer = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
            resultsContainer.innerHTML = '<tr><td colspan="7">Une erreur est survenue.</td></tr>';
        });
});
