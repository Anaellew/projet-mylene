<?php
    // Connexion à la base de données SQLite
    $db = new SQLite3('bdd.db');

    // Récupération des filtres
    $search = isset($_GET['search']) ? trim($_GET['search']) : '';

    // Tableau des résultats
    $results = [];

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

        // Parcourir les résultats et les stocker dans le tableau $results
        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            $results[] = [
                'titre' => $row['titre'],
                'album' => $row['album'],
                'annee' => $row['annee'],
                'duree' => $row['duree'],
                'paroles' => $row['paroles'],
                'compositeur' => $row['compositeur'],
                'auteur' => $row['auteur']
            ];
        }

    }

    // Fermer la connexion à la base de données
    $db->close();

    // Retourner les résultats en JSON
    echo json_encode([
        'searchTerm' => $search,
        'results' => $results
    ]);
?>