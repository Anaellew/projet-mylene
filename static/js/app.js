// Ajout d'un écouteur d'événement pour intercepter la soumission du formulaire
document.getElementById('searchForm').addEventListener('submit', async function (event) {
    event.preventDefault(); // Empêche le rechargement de la page

    // Récupère la valeur saisie dans le champ de recherche
    const query = document.getElementById('searchQuery').value;

    try {
        // Envoie une requête GET à l'API Flask
        const response = await fetch(`/search?search=${encodeURIComponent(query)}`);
        
        // Vérifie si la requête a réussi
        if (!response.ok) {
            throw new Error(`Erreur serveur : ${response.status}`);
        }

        // Récupère les données en JSON
        const data = await response.json();

        // Vérifie si des résultats sont retournés
        if (data.results && data.results.length > 0) {
            updateResultsMessage(`Résultats trouvés : ${data.count}`); // Affiche le nombre de résultats
            updateResultsTable(data.results); // Met à jour le tableau avec les résultats
        } else {
            updateResultsMessage("Aucun résultat trouvé"); // Affiche un message si aucun résultat
            clearResultsTable(); // Vide le tableau si aucun résultat
        }
    } catch (error) {
        console.error("Erreur lors de la requête : ", error);
        updateResultsMessage("Une erreur est survenue lors de la recherche. Veuillez réessayer.");
    }
});

// Fonction pour mettre à jour le tableau des résultats
function updateResultsTable(results) {
    const tbody = document.querySelector('#resultsTable tbody');
    tbody.innerHTML = ''; // Vide le tableau avant d'ajouter de nouveaux résultats

    results.forEach(result => {
        // Crée une nouvelle ligne pour chaque résultat
        const row = document.createElement('tr');

        // Ajoute les colonnes avec les données du résultat
        row.innerHTML = `
            <td>${result.titre}</td>
            <td>${result.album}</td>
            <td>${result.annee}</td>
            <td>${result.duree}</td>
            <td>${result.paroles}</td>
            <td>${result.compositeur}</td>
            <td>${result.auteur}</td>
        `;

        // Ajoute la ligne au tableau
        tbody.appendChild(row);
    });
}

// Fonction pour vider le tableau des résultats
function clearResultsTable() {
    const tbody = document.querySelector('#resultsTable tbody');
    tbody.innerHTML = ''; // Efface toutes les lignes du tableau
}

// Fonction pour afficher un message au-dessus du tableau des résultats
function updateResultsMessage(message) {
    const resultsMessage = document.getElementById('resultsMessage');
    resultsMessage.textContent = message; // Met à jour le texte du message
}
