<?php
// Fichier arrêter session utilisateur, déconnexion

session_start();
session_unset();
session_destroy();
header('Location: index.php');

?>