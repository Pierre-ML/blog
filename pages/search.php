<?php
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = "root";
    $dbname = "blog";

    $conn = new mysqli($servername, $username, $password, $dbname);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Recherche</title>
</head>

<body>
    <h2>Bienvenue sur SafiriContent</h2>
    <a href="index.php">Acceuil</a>  |
    <a href="signUp.php">Créer un compte</a> |
    <a href="logIn.php">Connectez-vous</a> |
    <a href="logIn.php">Postez un Article</a>

    <?php
    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // Vérifiez si le formulaire de recherche a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['query'])) {
        $query = $conn->real_escape_string($_POST['query']);

        // Requête de recherche
        $sql = "SELECT a.id, a.titre, a.contenu, a.img_couverture, a.date_publication, u.fullname 
                    FROM article a
                    INNER JOIN user u ON a.user_id = u.id
                    WHERE a.titre LIKE '%$query%' OR a.contenu LIKE '%$query%'
                    ORDER BY a.date_publication DESC";

        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            // Afficher les résultats de recherche
            echo "<h2>Résultats de recherche pour: " . htmlspecialchars($query) . "</h2>";
            while ($row = $result->fetch_assoc()) {
                echo "<div class='article'>";
                echo "<div class='article-content'>";
                echo "<h2>" . htmlspecialchars_decode($row['titre']) . "</h2>";
                echo "<p class='author'>Posté par " . htmlspecialchars_decode($row['fullname']) . "</p>";
                echo "<p class='date'>Date de votre poste : Le " . htmlspecialchars_decode($row['date_publication']) . "</p>";
                echo "<p>" . nl2br(htmlspecialchars_decode($row['contenu'])) . "</p>";
                echo "</div>";
                if (!empty($row['img_couverture'])) {
                    echo "<img src='" . htmlspecialchars_decode($row['img_couverture']) . "' alt='Image de couverture'>";
                }
                echo "</div>";
            }
        } else {
            echo "<p>Aucun résultat trouvé pour : " . htmlspecialchars($query) . "</p>";
        }
    }

    ?>

</body>
</html>

<?php $conn->close(); ?>