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

// L'identifiant de l'utilisateur pour lequel nous voulons afficher les articles
$userId = $_SESSION['user_id'];

$sql = "SELECT titre, contenu, img_couverture, date_publication, id FROM article 
        WHERE user_id = $userId ORDER BY date_publication DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vos articles</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h3>Bonjour <?php echo htmlspecialchars($_SESSION['fullname']); ?>, voici vos articles. </h3>

    <a href="index.php">Acceuil</a> |
    <a href="admin.php">Postez un article</a> |
    <a href="logOut.php">Se deconnectez</a><br>

    <?php if (isset($_SESSION['delete'])) : ?>
        <div class="error-message" style='color: red;'><?php echo $_SESSION['delete']; ?></div><br>
    <?php endif; ?>
    <?php if (isset($_SESSION['editImg'])) : ?>
        <div class="error-message" style='color: red;'><?php echo $_SESSION['editImg']; ?></div><br>
    <?php endif; ?>
    <?php if (isset($_SESSION['Error']['edit'])) : ?>
        <div class="error-message" style='color: red;'><?php echo $_SESSION['Error']['edit']; ?></div><br>
    <?php endif; ?>

    <?php
    unset($_SESSION['delete']);
    unset($_SESSION['Error']['edit']);
    unset($_SESSION['editImg']);
    if ($result->num_rows > 0) {
        // Afficher chaque article
        while ($row = $result->fetch_assoc()) {
            echo "<div class='article'>";
            echo "<div class='article-content'>";
            echo "<h2>" . htmlspecialchars_decode($row['titre']) . "</h2>";
            echo "<p class='date'>Date de votre publication : " . htmlspecialchars_decode(date("Y-m-d à H:i", strtotime($row['date_publication']))) . "</p>";
            echo "<p>" . nl2br(htmlspecialchars_decode($row['contenu'])) . "</p>";
            if (!empty($row['img_couverture'])) {
                echo "<img src='" . htmlspecialchars_decode($row['img_couverture']) . "' alt='Image de couverture'> <hr>";
            }
            // Formulaire de suppression
            echo "<form method='POST' action='delete.php'>";
            echo "<input type='hidden' name='article_id' value='" . $row['id'] . "'>";
            echo "<input type='submit' value='Supprimer cet article' onclick='return confirm(\"Voulez-vous vraiment supprimer cet article ?\");'>";
            echo "</form><hr>";

            // Formulaire de modification
            echo '<form method="post" action="preModifier.php">';
            echo '<input type="hidden" name="id" value="' . htmlspecialchars($row['id']) . '">';
            echo '<button type="submit">Modifier L\'article</button>';
            echo '</form><hr>';

            // Formulaire de modification
            echo '<form method="post" action="modifierImg.php" enctype="multipart/form-data">';
            echo '<input type="hidden" name="id" value=" ' . htmlspecialchars($row['id']) . '">';
            echo '<button type="submit">Modifier l\'image de couverture</button>';
            echo '<input type="file" name="img">';
            echo '</form>';

            echo "</div>";
            echo "</div>";
        }
    } else {
        echo "<p>Vous n'avez aucun article posté.</p>";
    }

    // Fermer la connexion
    $conn->close();

    ?>
</body>

</html>