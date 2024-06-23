<?php
    session_start();

    session_destroy();

    // Rediriger vers la page de connexion ou la page souhaitée
    header("Location: index.php");
    exit();

