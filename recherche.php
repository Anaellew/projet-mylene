<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>La Bibliothèque</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <h1>La Bibliothèque de Mylène</h1>
    </header>
    <main>
    <form action="recherche.php" method="GET">
        <input type="text" name="search" id="search" placeholder="Rechercher un mot..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <button type="submit">Rechercher</button>
    </form>

            <?php
            // Connexion à la base de données SQLite
            $db = new SQLite3('bdd.db');

            // Récupération des filtres
            $search = isset($_GET['search']) ? trim($_GET['search']) : '';

            // Si un terme de recherche est fourni
            if (!empty($search)) {

                // Construction de la requête
                $query = "SELECT * FROM BDD WHERE 1=1";
                $params = [];

                if (!empty($search)) {
                    $query .= " AND (titre LIKE :searchTerm OR auteur LIKE :searchTerm OR paroles LIKE :searchTerm OR compositeur LIKE :searchterm OR annee LIKE :searchterm OR duree LIKE :searchterm OR album LIKE :searchterm)";
                    $params[':searchTerm'] = '%' . $search . '%';
                } 

                // Préparer et exécuter la requête
                $stmt = $db->prepare($query);
                foreach ($params as $key => $value) {
                    $stmt->bindValue($key, $value, SQLITE3_TEXT);
                }
                $result = $stmt->execute();

                // Afficher les résultats dans une table HTML
                echo "<h2>Résultats de la recherche pour: " . htmlspecialchars($search) . "</h1>";
                echo "<table class='styled-table'>";
                echo "<thead>";
                echo "<tr><th>Titre</th><th>Album</th><th>Année</th><th>Durée</th><th>Paroles</th><th>Compositeur</th><th>Auteur</th></tr>";
                echo "</thead>";
                echo "<tbody>";

                // Parcourir les résultats et les afficher dans la table
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {

                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['titre']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['album']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['annee']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['duree']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['paroles']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['compositeur']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['auteur']) . "</td>";

                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                // Si le champ de recherche est vide, afficher ce message
                echo "Veuillez entrer un terme de recherche.";
            }

            // Fermer la connexion à la base de données
            $db->close();
            ?>
        <div class="moyenspace"></div>
    </main>
    <footer> <p>&copy; 2024 La Bibliothèque</p> </footer>
</body>
</html>
