<?php
session_start();
// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blog";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifiez si l'ID de l'article est défini dans la requête POST
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['article_id'])) {
    $article_id = $conn->real_escape_string($_POST['article_id']);

    // Requête SQL pour supprimer l'article
    $sql = "DELETE FROM article WHERE id = $article_id";

    if ($conn->query($sql) === TRUE) {
        $_SESSION['delete'] = "Article supprimé avec succès.";
        // Redirection vers la page principale ou la liste des articles après suppression
        header("Location: articles.php");
        exit();
    } else {
        $_SESSION['delete'] = "Erreur lors de la suppression de l'article";
    }
}

$conn->close();
