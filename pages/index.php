<?php
// Paramètres de connexion à la base de données
session_start();
$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "blog";

// Créer une connexion
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifier la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

//
$articlesParPage = 3; // Nombre d'articles par page
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Page actuelle, par défaut 1
$offset = ($page - 1) * $articlesParPage; // Calcul de l'offset

// requete pour les nombres total d'articles
$totalArticlesQuery = "SELECT COUNT(*) as total FROM article";
$result = $conn->query($totalArticlesQuery);
$totalArticles = $result->fetch_assoc()['total'];
$totalPages = ceil($totalArticles / $articlesParPage); // Nombre total de pages

/// Recuperation d'articles
$sql = "SELECT a.titre, a.contenu, a.img_couverture, a.date_publication, u.fullname
        FROM article a
        INNER JOIN user u ON a.user_id = u.id
        ORDER BY a.date_publication DESC LIMIT $articlesParPage OFFSET $offset";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceuil</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h2>Bienvenue <?php if (isset($_SESSION['fullname'])) : ?>
            <?php echo htmlspecialchars($_SESSION['fullname']); ?>
            <?php endif; ?> sur SafiriContent</h2>
    <a href="signUp.php">Créer un compte</a> |
    <a href="logIn.php">Connectez-vous</a> |
    <a href="logIn.php">Postez un Article</a><br><br>
    <!-- Formulaire de recherche -->
    <form method="POST" action="search.php">
        <input type="text" name="query" placeholder="Rechercher..." required>
        <input type="submit" value="Rechercher">
    </form>

    <?php
    if ($result->num_rows > 0) {
        // Afficher chaque article
        while ($row = $result->fetch_assoc()) {
            echo "<div class='article'>";
            echo "<div class='article-content'>";
            // Le titre de l'article
            echo "<h2>" . htmlspecialchars_decode($row['titre']) . "</h2>";
            // Nom de celui qui l'a poste
            echo "<p class='author'>Posté par " . htmlspecialchars_decode($row['fullname']) . "</p>";
            // Date de publication
            echo "<p class='date'>Date de publication : " . htmlspecialchars_decode(date("Y-m-d à H:i", strtotime($row['date_publication']))) . "</p>";
            // Contenu de l'article
            echo "<p>" . nl2br(htmlspecialchars_decode($row['contenu'])) . "</p>";
            // Image de couverture
            echo "</div>";
            if (!empty($row['img_couverture'])) {
                echo "<img src='" . htmlspecialchars_decode($row['img_couverture']) . "' alt='Image de couverture'>";
            }
            //echo "<p>" . "Poste par" . $_SESSION['fullname'] ; "</p>";
            echo "</div>";
        }
    } else {
        echo "<p>Vous n'avez aucun article posté.</p>";
    }

    ?>
    <?php
    echo "<div class='pagination'>";
    for ($i = 1; $i <= $totalPages; $i++) {
        if ($i == $page) {
            echo "<span>$i</span> "; // Page actuelle sans lien
        } else {
            echo "<a href='?page=$i'>$i</a> ";
        }
    }
    echo "</div>";

    // Fermer la connexion
    $conn->close();
    ?>
</body>

</html>