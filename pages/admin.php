<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h3>Bienvenu sur la page administrateur</h3>
    <h4>Postez un article :</h4>
    <form method="post" action="processing_admin.php" enctype="multipart/form-data">
        <label for="titre">Postez un article :</label>
        <input type="text" name="titre" id="titre">
        <hr><br>

        <label for="contenu">Contenu de l'article :</label>
        <textarea name="contenu" id="contenu" rows="10" cols="50"></textarea>
        <hr>

        <label for="image">Choisissez une image de couverture pour votre article :</label>
        <input type="file" name="img" id="image">
        <hr>

        <label for="date">Date de publication :</label>
        <input type="datetime-local" name="date" id="date">
        <hr><br>

        <input type="submit" value="Postez votre article">
    </form>
</body>

</html>
<?php session_destroy(); ?>