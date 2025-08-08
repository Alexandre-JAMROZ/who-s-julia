<?php

require_once __DIR__ . '/../config/register.php';

$error = '';
$pseudo = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (register($pseudo, $email, $password, $confirm_password, $error)) {
        // Succès : Rediriger vers l'accueil
        header('Location: index.html?success=1');
        exit;
    } else {
        // Erreur : On reste sur la page
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte</title>
</head>
<body>
    <h1>Créer un compte</h1>

    <?php if ($error): ?>
        <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form action="compte.php" method="post" class="compte_form">
        <div class="compte_form">
            <label for="pseudo">Entrez votre pseudo :</label>
            <input type="text" name="pseudo" id="pseudo" required value="<?php echo htmlspecialchars($pseudo); ?>">
        </div>

        <div class="compte_form">
            <label for="email">Entrez votre email :</label>
            <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">
        </div>

        <div class="compte_form">
            <label for="password">Entrez votre mot de passe :</label>
            <input type="password" name="password" id="password" required>
        </div>

        <div class="compte_form">
            <label for="confirm_password">Confirmez votre mot de passe :</label>
            <input type="password" name="confirm_password" id="confirm_password" required>
        </div>

        <div class="compte_form">
            <button type="submit">Créer votre compte</button>
        </div>
    </form>
</body>
</html>