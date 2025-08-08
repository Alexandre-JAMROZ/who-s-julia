<?php

"""
Fichier création d'un compte utilisateur et envoi des données dans la base de données 'julia'
"""

// Inclure la base de données
require_once "db.php"; // Chemin relatif (même dossier)

// Récupérer les données du formulaire
$pseudo = trim($_POST['pseudo'] ?? '');
$email = trim($_POST['email' ?? '']);
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

$error = null;

// Vérification supplémentaire que tous les champs sont remplis
if (empty($pseudo) || empty($email) || empty($password) || empty($confirm_password) {
    $error = "Veuillez remplir tous les champs.";
})

// Vérification mdp = confirm_mdp
if ($password != $confirm_password) {
    $error = "Les mots de passe ne correspondent pas.";
}

// Vérifier que l'email est valde
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Adresse email introuvable.";
}

// Vérifier si le pseudo ou l'email existe déjà
$stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = :pseudo OR email = :email");
$stmt->execute(['pseudo' => $pseudo, 'email' => $email]);
if ($stmt->fetch()) {
    $error = "Ce pseudo ou cet email est déjà utilisé.";
}

// Si erreur, encode les valeurs pour les passer dans l'URL (reremplir les champs)
if ($error) {
    $params = http_build_query([
        'error' => $error,
        'pseudo' => $pseudo,
        'email' => $email
    ]);
    header("Location: ../src/compte.php?$params");
    exit;
}

// Hasher le mot de passe
$password_hash = password_hash($password, PASSWORD_DEFAULT);

// Insérer l'utilisateur dans la base de données
$stmt = $pdo->prepare("INSERT INTO users(pseudo, email, password_hash) VALUES (:pseudo, :email, :password_hash)");
$stmt->execute([
    'pseudo' => $pseudo,
    'email' => $email,
    'password_hash' => $password_hash
]);

header("Location: ../src/index.html?success=1");
exit;

?>