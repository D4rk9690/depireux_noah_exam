<?php
header("Content-Type: text/html; charset=UTF-8");
session_start();

// Détruire la session de l'utilisateur connecté
session_destroy();

// Rediriger vers la page de connexion
header("Location: index.html");
exit;
?>
