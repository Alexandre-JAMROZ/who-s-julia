<?php
// Fichier connexion d'un compte utilisateur

// Inclure la base de données
require_once "db.php"; 

// Fonction pour se connecter
function login($lpseudo, $lpassword, &$msg) {
    $msg = null;

    // Vérification que tous les champs sont remplis
    if (empty($lpseudo) || empty($lpassword)) {
        $msg = "Veuillez remplir tous les champs.";
        return false;
    }

    // Vérification que le pseudo existe
    global $pdo;
    $stmt = $pdo->prepare("SELECT pseudo FROM users WHERE pseudo = :lpseudo");
    $stmt->execute(['lpseudo' => $lpseudo]);
    if (!$stmt->fetch()) {
        $msg = "Ce pseudo est inexistant.";
        return false;
    }

    // Vérification mot de passe
    $stmt = $pdo->prepare("SELECT password_hash FROM users WHERE pseudo = :lpseudo");
    $stmt->execute(['lpseudo' => $lpseudo]);
    $password_hash = $stmt->fetchColumn();
    
    // Mauvais mot de passe
    if (!password_verify($lpassword, $password_hash)) {
        $msg = "Le mot de passe est incorrect.";
        return false;
    } else {
        // Bon mot de passe, démarrer session
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Stocker le pseudo
        $_SESSION['user'] = $lpseudo;

        $msg = "Connexion réussie !";
        
        // Gérer la redirection après connexion si nécessaire
        if (isset($_SESSION['redirect_after_login'])) {
            $redirect_url = $_SESSION['redirect_after_login'];
            unset($_SESSION['redirect_after_login']);
            header('Location: ' . $redirect_url);
            exit();
        }
        
        return true;
    }
}
?>