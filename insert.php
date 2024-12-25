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
    $duree = number_format((float)$row[3], 2, '.', '');
    $paroles = SQLite3::escapeString($row[4]);
    $compositeur = SQLite3::escapeString($row[5]);
    $auteur = SQLite3::escapeString($row[6]);

    // Insérer les données dans la table BDD
    $db->exec("INSERT INTO BDD (titre, album, annee, duree, paroles, compositeur, auteur) 
               VALUES ('$titre', '$album', '$annee', '$duree', '$paroles', '$compositeur', '$auteur')");
}

// Fermer le fichier
fclose($file);

// Fermer la connexion
$db->close();

echo "Importation terminée avec succès.";
?>