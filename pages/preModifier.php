<?php
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

// Vérifier si un identifiant d'article est passé en paramètre POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id'])) {
    $id = $_POST['id'];

    // Récupérer l'article
    $sql = "SELECT * FROM article WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$article) {
        die("Article introuvable");
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Modifier un article</title>
</head>

<body>
    <h1>Modifier l'article</h1>
    <form method="post" action="modifier.php">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
        <div>
            <label for="titre">Titre :</label>
            <input type="text" id="titre" name="titre" value="<?php echo htmlspecialchars($article['titre']); ?>" required>
            <hr>
            <br>
        </div>
        <div>
            <label for="contenu">Contenu :</label>
            <textarea id="contenu" name="contenu" rows="10" cols="50" required><?php echo htmlspecialchars($article['contenu']); ?></textarea>
            <hr>
            <br>
        </div>
        <div>
            <button type="submit">Modifier</button>
        </div>
    </form>
</body>

</html>