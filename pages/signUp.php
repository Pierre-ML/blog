<?php
    session_start();
    // Vérifier si l'utilisateur est authentifié
    if (isset($_SESSION['isAuthenticated']) AND $_SESSION['isAuthenticated'] === true) {
        header("Location: admin.php");
        exit();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Singn Up</title>
</head>

<body>
    <div class="form-container">
        <h1>Inscription</h1>
        <form action="processing_sign.php" method="POST">
            <label for="fullName">Nom complet:</label>
            <input type="text" id="fullName" name="fullName" value="<?php echo isset($_SESSION['fullName']) ? $_SESSION['fullName'] : ''; ?>"><br><br>
            <?php if (isset($_SESSION['errors']['fullName'])) : ?>
                <div class="error-message" style='color: red;'><?php echo $_SESSION['errors']['fullName']; ?></div><br>
            <?php endif; ?>

            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="<?php echo isset($_SESSION['login']) ? $_SESSION['login'] : ''; ?>"><br><br>
            <?php if (isset($_SESSION['errors']['login'])) : ?>
                <div class="error-message" style='color: red;'><?php echo $_SESSION['errors']['login']; ?></div><br>
            <?php endif; ?>

            <label for="password">Mot de passe:</label>
            <input type="password" id="password" name="password" value="<?php echo isset($_SESSION['password']) ? $_SESSION['password'] : ''; ?>"><br><br>
            <?php if (isset($_SESSION['errors']['password'])) : ?>
                <div class=" error-message" style='color: red;'><?php echo $_SESSION['errors']['password']; ?>
                </div><br>
            <?php endif; ?>

            <label for="confirmPassword">Confirmez le mot de passe:</label>
            <input type="password" id="confirmPassword" name="confirmPassword" value="<?php echo isset($_SESSION['confirmPassword']) ? $_SESSION['confirmPassword'] : ''; ?>"><br><br>
            <?php if (isset($_SESSION['errors']['confirmPassword'])) : ?>
                <div class="error-message" style='color: red;'><?php echo $_SESSION['errors']['confirmPassword']; ?></div><br><br>
            <?php endif; ?>

            <?php if (isset($_SESSION['errors']['database'])) : ?>
                <div class="error-message" style='color: red;'><?php echo $_SESSION['errors']['database']; ?></div><br>
            <?php endif; ?>

            <button type="submit">S' incrire</button><br><br>
        </form>
      </div>
    <div>
        <a href="logIn.php">Cliquez ici pour se connecter si vous avez deja un compte !</a>
    </div>
</body>

</html>
<?php session_destroy(); ?>