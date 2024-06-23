<?php
    session_start();
    // Vérifier si l'utilisateur est authentifié
    if (!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] !== true) {
        header("Location: logIn.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrateur</title>
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <h3>Bienvenue <?php if (isset($_SESSION['fullname'])) : ?>
                        <?php echo htmlspecialchars($_SESSION['fullname']); ?>
                  <?php endif; ?>
    </h3>
    <?php if (isset($_SESSION['message']['true'])) : ?>
        <p style='color: red;'><?php echo $_SESSION['message']['true']; ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['message']['false'])) : ?>
        <p style='color: red;'><?php echo $_SESSION['message']['false']; ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['Errors']['database'])) : ?>
        <p style='color: red;'><?php echo $_SESSION['Errors']['database']; ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['Errors']['upload'])) : ?>
        <p style='color: red;'><?php echo $_SESSION['Errors']['upload']; ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['Errors']['empty'])) : ?>
        <p style='color: red;'><?php echo $_SESSION['Errors']['emptyr']; ?></p>
    <?php endif; ?>

    <a href="index.php">Acceuil</a>  |
    <a href="logOut.php">Déconnexion</a>   |
    <a href="articles.php">Voir vos articles</a>  |
    <h4>Postez un article :</h4>
    <form method="post" action="processing_admin.php" enctype="multipart/form-data">

        <label for="titre">Titre de l'article :</label>
        <input type="text" name="titre" id="titre" value="<?php echo isset($_SESSION['titre']) ? htmlspecialchars($_SESSION['titre']) : ''; ?>">
        <?php if (isset($_SESSION['Errors']['titre'])) : ?>
            <div class="error-message" style='color: red;'><?php echo $_SESSION['Errors']['titre']; ?></div><br>
        <?php endif; ?>
        <hr><br>


        <label for="contenu">Contenu de l'article :</label>
        <textarea name="contenu" id="contenu" rows="10" cols="50"><?php echo isset($_SESSION['contenu']) ? htmlspecialchars($_SESSION['contenu']) : ''; ?></textarea>
        <?php if (isset($_SESSION['Errors']['contenu'])) : ?>
            <div class="error-message" style='color: red;'><?php echo $_SESSION['Errors']['contenu']; ?></div><br>
        <?php endif; ?>
        <hr>

        <label for="image">Choisissez une image de couverture pour votre article :</label>
        <input type="file" name="img" id="image">
        <?php if (isset($_SESSION['Errors']['img'])) : ?>
            <div class="error-message" style='color: red;'><?php echo $_SESSION['Errors']['img']; ?></div><br>
        <?php endif; ?>
        <hr>

        <input type="hidden" name="date" id="date" value="<?php echo date('Y-m-d H:i:s'); ?>">

        <input type="submit" value="Postez votre article">
    </form>
</body>

</html>

<?php
    //unset($_SESSION['fullname']);
    unset($_SESSION['message']);
    unset($_SESSION['Errors']);
    unset($_SESSION['titre']);
    unset($_SESSION['contenu']);
?>