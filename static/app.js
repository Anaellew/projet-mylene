document.getElementById('searchForm').addEventListener('submit', function (event) {
    event.preventDefault(); // Empêche le rechargement de la page

    const query = document.getElementById('searchQuery').value;

    fetch(`http://127.0.0.1:5000/search?search=${encodeURIComponent(query)}`)
        .then(response => response.json())
        .then(data => {
            displayResults(data.results);
        })
        .catch(error => {
            console.error('Erreur lors de la recherche :', error);
        });
});

function displayResults(results) {
    const resultsTable = document.getElementById('resultsTable').getElementsByTagName('tbody')[0];
    resultsTable.innerHTML = ''; // Efface les anciens résultats

    results.forEach(result => {
        const row = resultsTable.insertRow();

        Object.values(result).forEach(text => {
            const cell = row.insertCell();
            cell.textContent = text;
        });
    });
}
