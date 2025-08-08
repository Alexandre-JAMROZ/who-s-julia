<?php

"""
Fichier permettant la connexion à la base de données 'julia' (dbname)
"""

// Informations pour la connexion
$host = "localhost";
$dbname = "julia";
$username = "root";
$password = "";

// Connexion à la base MySQL
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Erreur de connexion à la base : " . $e->getMessage());
}

?>