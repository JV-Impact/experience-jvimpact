<?php
session_start();

// Connexion à la base de données SQLite
$db = new SQLite3('experiance.db');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = $_POST['mot_de_passe'];

    // Rechercher l'utilisateur par email
    $query = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
    $query->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $query->execute();
    $utilisateur = $result->fetchArray(SQLITE3_ASSOC);

    if ($utilisateur) {
        // Vérification du mot de passe
        if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
            // Connexion réussie, démarrage de la session
            $_SESSION['user_id'] = $utilisateur['id'];
            $_SESSION['user_name'] = $utilisateur['nom'];

            echo "<script>alert('Connexion réussie !'); window.location.href='profil.html';</script>";
        } else {
            // Mot de passe incorrect
            echo "<script>alert('Mot de passe incorrect.'); window.location.href='connexion.html';</script>";
        }
    } else {
        // Email non trouvé
        echo "<script>alert('Email introuvable. Veuillez vous inscrire.'); window.location.href='inscription.html';</script>";
    }
}
?>
