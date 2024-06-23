<?php
session_start();

// Vérifier si la méthode de requête est POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['Errors'] = [];

    // Nettoyer et récupérer les données du formulaire
    $_SESSION['titre'] = htmlspecialchars($_POST['titre']);
    $_SESSION['contenu'] = htmlspecialchars($_POST['contenu']);
    $_SESSION['date'] = htmlspecialchars($_POST['date']);
    //$user_id = $_SESSION['user_id'];

    // Valider les champs requis
    if (empty($_POST['titre'])) {
        $_SESSION['Errors']['titre'] = 'Le titre est obligatoire';
    }
    if (empty($_POST['contenu'])) {
        $_SESSION['Errors']['contenu'] = 'Le contenu est obligatoire';
    }
    if (empty($_FILES['img']['name'])) {
        $_SESSION['Errors']['img'] = 'L\'image de couverture est obligatoire';
    }

    // Si aucune erreur n'est présente, procéder à l'enregistrement de l'article
    if (empty($_SESSION['Errors'])) {
        // Vérifier et créer le répertoire de téléchargement s'il n'existe pas
        $upload_dir = 'uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Renommer l'image téléchargée avec un nom unique
        $file_extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
        $unique_filename = 'IMG_' . uniqid() . '.' . $file_extension;
        $uploaded_file = $upload_dir . $unique_filename;

        if (move_uploaded_file($_FILES['img']['tmp_name'], $uploaded_file)) {
            // Connexion à la base de données
            $conn = new mysqli('localhost', 'root', 'root', 'blog');

            // Vérifier la connexion
            if ($conn->connect_error) {
                $_SESSION['Errors']['database'] = "Échec de la connexion à la base de données : " . $conn->connect_error;
                header("Location: admin.php");
            } else {
                $user_id = $_SESSION['user_id'];

                // Préparer et exécuter la requête d'insertion
                $stmt = $conn->prepare("INSERT INTO article (titre, contenu, img_couverture, date_publication, user_id) VALUES (?, ?, ?, ?, ?)");
                $stmt->bind_param('ssssi', $_SESSION['titre'], $_SESSION['contenu'], $uploaded_file, $_SESSION['date'], $user_id);
                if ($stmt->execute()) {
                    // Destruction de certaines sessions
                    unset($_SESSION['message']);
                    unset($_SESSION['Errors']);
                    unset($_SESSION['titre']);
                    unset($_SESSION['contenu']);

                    $_SESSION['message']['true'] = "Article publié avec succès !";
                    header("Location: admin.php");
                    exit();
                } else {
                    $_SESSION['message']['false'] = "Erreur lors de la publication de l'article !";
                    header("Location: admin.php");
                    exit();
                }
                $stmt->close();
            }
            $conn->close();
        } else {
            $_SESSION['Errors']['upload'] = "Désolé, une erreur est survenue lors du téléchargement de votre fichier.";
            header("Location: admin.php");
        }
    } else {
        // Rediriger si la méthode de requête n'est pas POST
        $_SESSION['titre'] = $_POST['titre'];
        $_SESSION['continu'] = $_POST['contenu'];
        header("Location: admin.php");
        exit();
    }
}
