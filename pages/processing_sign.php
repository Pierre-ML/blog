<?php
session_start();

/*if (!isset($_SESSION['isAuthenticated']) || $_SESSION['isAuthenticated'] !== true) {
    header("Location: admin.php");
    exit();
}*/

try {
    $dsn = "mysql:host=localhost;dbname=blog";
    $username = "root";
    $password = "root";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ];
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}


// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['errors'] = [];
    $_SESSION['fullName'] = $_POST['fullName'];
    $_SESSION['login'] = $_POST['login'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['confirmPassword'] = $_POST['confirmPassword'];


    // Vérification des champs vides
    if (empty($_POST['fullName'])) {
        $_SESSION['errors']['fullName'] = 'Le nom complet est obligatoire.';
    }

    if (empty($_POST['login'])) {
        $_SESSION['errors']['login'] = 'Le login est obligatoire.';
    }

    if (empty($_POST['password'])) {
        $_SESSION['errors']['password'] = 'Le mot de passe est obligatoire.';
    }

    if (empty($_POST['confirmPassword'])) {
        $_SESSION['errors']['confirmPassword'] = 'La confirmation du mot de passe est obligatoire.';
    }

    // Si aucun champ n'est vide, procéder à l'inscription
    if (empty($_SESSION['errors'])) {
        $fullName = trim($_POST['fullName']);
        $login = trim($_POST['login']);
        $password = trim($_POST['password']);
        $confirmPassword = trim($_POST['confirmPassword']);

        // Vérification si les mots de passe correspondent
        if ($password !== $confirmPassword) {
            $_SESSION['errors']['confirmPassword'] = 'Les mots de passe ne correspondent pas.';
        } else {
            // Connexion à la base de données et vérification si le nom complet ou le login existe déjà
            try {
                $dsn = "mysql:host=localhost;dbname=blog";
                $username = "root";
                $password = "root";
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ];
                $conn = new PDO($dsn, $username, $password, $options);
            } catch (PDOException $e) {
                die("Erreur de connexion à la base de données : " . $e->getMessage());
            }

            // Si aucun champ n'est vide, procéder à l'inscription
            if (empty($_SESSION['errors'])) {
                $fullName = trim($_POST['fullName']);
                $login = trim($_POST['login']);
                $password = trim($_POST['password']);
                $confirmPassword = trim($_POST['confirmPassword']);

                // Vérification si les mots de passe correspondent
                if ($password !== $confirmPassword) {
                    $_SESSION['errors']['confirmPassword'] = 'Les mots de passe ne correspondent pas.';
                } else {
                    // Vérification si le nom complet ou le login existe déjà
                    $stmt = $pdo->prepare("SELECT * FROM user WHERE fullname = :fullname OR user = :user");
                    $stmt->execute([':fullname' => $fullName, ':user' => $login]);
                    $result = $stmt->fetch();

                    if ($result) {
                        $_SESSION['errors']['database'] = 'Le nom complet ou le login existe déjà.';
                    } else {
                        // Insertion des données
                        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                        $stmt = $pdo->prepare("INSERT INTO user (fullname, user, password) VALUES (:fullname, :user, :password)");
                        $stmt->execute(['fullname' => $fullName, 'user' => $login, 'password' => $password]);
                        $_SESSION['isAuthenticated'] = true;
                        $_SESSION['fullname'] =  $fullName;
                        $_SESSION['user_id'] = $user['id'];
                        header('Location: admin.php');
                        exit();
                    }
                }
            }
        }
    }else{
        // Redirection vers le formulaire d'inscription en cas d'erreur
        $_SESSION['fullName'] = $_POST['fullName'];
        $_SESSION['login'] = $_POST['login'];
        $_SESSION['password'] = $_POST['password'];
        $_SESSION['confirmPassword'] = $_POST['confirmPassword'];
        header('Location: signUp.php');
        exit();

    }
    
}