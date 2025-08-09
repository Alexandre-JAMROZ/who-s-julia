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
    <link rel="stylesheet" href="static/css/styles-compte.css">
</head>
<body>
    <header>
        <div class="nav-container">
            <nav>
                <ul>
                <li><a href="/">Accueil</a></li>
                <li><a href="documentation/documentation.html">Documentation</a></li>
                <li><a href="modules/modules.html">Modules</a></li>
                <li><a href="#">Créer un compte</a></li>
                </ul>
            </nav>
            <div class="profile">
                <img src="https://via.placeholder.com/40" alt="Photo de profil">
            </div>
        </div>
    </header>

    <main>
        <div class="main">
            <h1>Créer un compte</h1>

            <?php if ($error): ?>
                <p style="color: red;"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>

            <form action="compte.php" method="post" class="compte-form">
                <div class="compte-form">
                    <label for="pseudo">Votre pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" required value="<?php echo htmlspecialchars($pseudo); ?>">
                </div>

                <div class="compte-form">
                    <label for="email">Votre E-mail</label>
                    <input type="email" name="email" id="email" required value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="compte-form">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="password" required>
                </div>

                <div class="compte-form">
                    <label for="confirm_password">Entrez le mot de passe à nouveau</label>
                    <input type="password" name="confirm_password" id="confirm_password" required>
                </div>

                <div class="compte-form">
                    <button type="submit">Créer votre compte</button>
                </div>
            </form>
        </div>
    </main>

    <footer>
        <p><strong>Contacts&nbsp;:</strong> alexandrejamrozpro@gmail.com | lucas.jamet03@gmail.com </p>
        <p><strong>Université&nbsp;:</strong> Université de la Nouvelle-Calédonie</p>
        <p>Projet Tutoré - Alexandre JAMROZ & Lucas JAMET</p>
    </footer>
</body>
</html>