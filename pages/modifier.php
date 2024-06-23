
<?php
session_start();
// Connexion à la base de données
$host = 'localhost';
$dbname = 'blog';
$username = 'root';
$password = 'root';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if($_SERVER['REQUEST_METHOD'] == 'POST' && (isset($_POST['titre']) AND isset($_POST['contenu']) AND isset($_POST['id']))) {
    // Mettre à jour l'article après soumission du formulaire de modification
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];

    $sql = "UPDATE article SET 
    titre = :titre, contenu = :contenu WHERE id = :id";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['titre' => $titre, 'contenu' => $contenu, 'id' => $id]);

    $_SESSION['Error']['edit'] = "Article modifier avec succes !";
    // Rediriger vers la page de confirmation ou l'article modifié
    header("Location: articles.php");
    exit();
} else {
    $_SESSION['Error']['edit'] = "Requete invalide !";
}
?>
