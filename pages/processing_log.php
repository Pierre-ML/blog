<?php
session_start();

try {
    $dsn = "mysql:host=localhost;dbname=blog";
    $username = "root";
    $password = "root";
    $options = [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                 PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,];
    $bdd = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //$_SESSION['errors'] = [];
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);
    $user_id = $_SESSION['user_id'];

    if (empty($_POST['username'])) {
        $_SESSION['errors']['username'] = 'Le nom complet est obligatoire';
    }
    if(empty($_POST['password'])){
        $_SESSION['errors']['password'] = 'Le mot de passe est obligatoire';
    }

    if (!empty($username) && !empty($password)) {
        $stmt = $bdd->prepare('SELECT id, user, password, fullname FROM user WHERE user = :user');
        $stmt->execute([':user' => $username]);
        $user = $stmt->fetch();

        if (isset($user) && ($password === $user['password'])) {
            $_SESSION['isAuthenticated'] = true;
            $_SESSION['fullname'] =  $user['fullname'];
            $_SESSION['user_id'] = $user['id'];
            header('Location: admin.php');
            exit();
        } else {
            $_SESSION['username'] = $username;
            $_SESSION['password'] = $password;
            $_SESSION['isAuthenticated'] = false;
            header('Location: logIn.php');
            exit();
        }
    } else {
        $_SESSION['usermane'] = $_POST['username'];
        $_SESSION['password'] = $_POST['password'];        
        header('Location: logIn.php');
        exit();
    }
} else {
    $_SESSION['usermane'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    header('Location: logIn.php');
    exit();
}


