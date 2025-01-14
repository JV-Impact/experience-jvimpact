<?php
// Connexion à la base de données SQLite (le fichier sera créé s'il n'existe pas)
$db = new SQLite3('experiance.db');

// Création de la table utilisateurs
$db->exec('
    CREATE TABLE IF NOT EXISTS utilisateurs (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nom TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        mot_de_passe TEXT NOT NULL
    )
');

echo "La base de données experiance.db a été créée avec succès !";
?>
