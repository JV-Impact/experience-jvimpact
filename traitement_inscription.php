<?php
// Connexion à la base de données SQLite
$db = new SQLite3('experiance.db');

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupération des données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT);

    // Vérifier si l'email existe déjà
    $query = $db->prepare('SELECT * FROM utilisateurs WHERE email = :email');
    $query->bindValue(':email', $email, SQLITE3_TEXT);
    $result = $query->execute();

    if ($result->fetchArray()) {
        echo "<script>alert('Cet email est déjà utilisé.'); window.location.href='inscription.html';</script>";
    } else {
        // Insérer l'utilisateur dans la base de données
        $stmt = $db->prepare('INSERT INTO utilisateurs (nom, email, mot_de_passe) VALUES (:nom, :email, :mot_de_passe)');
        $stmt->bindValue(':nom', $nom, SQLITE3_TEXT);
        $stmt->bindValue(':email', $email, SQLITE3_TEXT);
        $stmt->bindValue(':mot_de_passe', $mot_de_passe, SQLITE3_TEXT);
        $stmt->execute();

        echo "<script>alert('Inscription réussie ! Vous pouvez maintenant vous connecter.'); window.location.href='connexion.html';</script>";
    }
}
?>
