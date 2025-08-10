<?php
// Fichier création d'un compte utilisateur et envoi des données dans la base de données 'julia'

// Inclure la base de données
require_once "db.php";

// Fonction pour enregistrer l'utilisateur
function register($pseudo, $email, $password, $confirm_password, &$msg) {
    $msg = null;

    // Vérification que tous les champs sont remplis
    if (empty($pseudo) || empty($email) || empty($password) || empty($confirm_password)) {
        $msg = "Veuillez remplir tous les champs.";
        return false;
    }

    // Vérification mdp = confirm_mdp (si pas d'erreur précédente)
    if ($password !== $confirm_password) {
        $msg = "Les mots de passe ne correspondent pas.";
        return false;
    }

    // Vérifier que l'email est valide (si pas d'erreur précédente)
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "Adresse email invalide.";
        return false;
    }

    // Vérifier si le pseudo ou l'email existe déjà (si pas d'erreur précédente)
    global $pdo;
    $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = :pseudo OR email = :email");
    $stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
    if ($stmt->fetch()) {
        $msg = "Ce pseudo ou cet email est déjà utilisé.";
        return false;
    }

    // Hasher le mot de passe
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insérer l'utilisateur dans la base de données
    $stmt = $pdo->prepare("INSERT INTO users (pseudo, email, password_hash) VALUES (:pseudo, :email, :password_hash)");
    $stmt->execute([
        'pseudo' => $pseudo,
        'email' => $email,
        'password_hash' => $password_hash
    ]);

    $msg = "Création du compte réussie !";
    return true;
    
}