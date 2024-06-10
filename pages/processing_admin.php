<?php
session_start();

$dsn = "mysql:host=localhost;dbname=blog";
$username = "root";
$password = "root";
try {
    $bdd = new PDO($dsn, $username, $password);
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

if (isset($_POST['titre'], $_POST['contenu'], $_FILES['img'], $_POST['date'])) {

    $titre = trim($_POST['titre']);
    $contenu = trim($_POST['contenu']);
    $date = $_POST['date'];

    // Gestion de l'image de couverture
    $target_dir = "uploads/";
    $target_file = $target_dir . uniqid() . basename($_FILES["img"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $check = getimagesize($_FILES["img"]["tmp_name"]);

    if ($check !== false) {
        // Validation de la taille du fichier
        if ($_FILES["img"]["size"] > 500000) {
            die("Désolé, votre fichier est trop volumineux.");
        }
        // Limitation des types de fichiers
        if (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
            die("Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.");
        }

        // Déplacement du fichier téléchargé
        if (move_uploaded_file($_FILES["img"]["tmp_name"], $target_file)) {
            try {
                $sql = "INSERT INTO article (titre, contenu, img_couverture, date_publication) VALUES (:titre, :contenu, :img, :date)";
                $stmt = $bdd->prepare($sql);
                $stmt->execute([
                    ':titre' => $titre,
                    ':contenu' => $contenu,
                    ':img' => $target_file,
                    ':date' => $date
                ]);

                echo "Nouvel article créé avec succès.";
                header('Location: admin.php');
                exit();
            } catch (PDOException $e) {
                die("Erreur: " . $e->getMessage());
            }
        } else {
            die("Désolé, une erreur est survenue lors du téléchargement de votre fichier.");
        }
    } else {
        die("Le fichier sélectionné n'est pas une image.");
    }
} else {
    die("Veuillez vérifier que toutes les informations sont fournies.");
}
