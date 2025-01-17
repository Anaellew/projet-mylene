<?php
// Connexion à la base de données SQLite
$db = new SQLite3('bdd.db');
// Créer la table si elle n'existe pas
$createTableQuery = "
CREATE TABLE IF NOT EXISTS BDD (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    titre TEXT,
    album TEXT,
    annee INTEGER,
    duree REAL,
    paroles TEXT,
    compositeur TEXT,
    auteur TEXT
)";
$db->exec($createTableQuery);

// Ouvrir le fichier CSV
$file = fopen('projetmymy.csv', 'r');

// Ignorer la première ligne si elle contient les en-têtes
fgetcsv($file);

// Lire chaque ligne du fichier CSV
while (($row = fgetcsv($file, 1000, ",")) !== FALSE) {
    // Échapper les valeurs pour éviter les erreurs SQL
    $titre = SQLite3::escapeString($row[0]);
    $album = SQLite3::escapeString($row[1]);
    $annee = (int)$row[2];
    $duree = SQLite3::escapeString($row[3]);
    $paroles = SQLite3::escapeString($row[4]);
    $compositeur = SQLite3::escapeString($row[5]);
    $auteur = SQLite3::escapeString($row[6]);

    // Préparer l'insertion
    $stmt = $db->prepare("INSERT INTO BDD (titre, album, annee, duree, paroles, compositeur, auteur) 
                          VALUES (:titre, :album, :annee, :duree, :paroles, :compositeur, :auteur)");
    $stmt->bindValue(':titre', $titre, SQLITE3_TEXT);
    $stmt->bindValue(':album', $album, SQLITE3_TEXT);
    $stmt->bindValue(':annee', $annee, SQLITE3_INTEGER);
    $stmt->bindValue(':duree', $duree, SQLITE3_TEXT);
    $stmt->bindValue(':paroles', $paroles, SQLITE3_TEXT);
    $stmt->bindValue(':compositeur', $compositeur, SQLITE3_TEXT);
    $stmt->bindValue(':auteur', $auteur, SQLITE3_TEXT);

    $stmt->execute();
}

// Fermer le fichier
fclose($file);

// Fermer la connexion
$db->close();

echo "Importation terminée avec succès.";
?>
