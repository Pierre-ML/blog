<?php
// Connexion à la base de données
$host = 'localhost';
$dbname = 'nom_de_la_base_de_donnees';
$username = 'nom_utilisateur';
$password = 'mot_de_passe';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];

    // Mettre à jour l'article
    $sql = "UPDATE article SET titre = :titre, contenu = :contenu WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'id' => $id]);

    // Rediriger vers la page de l'article ou une autre page de confirmation
    header("Location: article.php?id=" . $id);
    exit();
}
