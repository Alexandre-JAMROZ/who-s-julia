<?php

session_start();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    require_once __DIR__ . "/../config/logout.php";
    exit;
}

$msgRegister = '';
$errorRegister = false;
$msgLogin = '';
$errorLogin = false;


$pseudo = '';
$email = '';
$lpseudo = '';

// Formulaire register soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pseudo'])) {
    require_once __DIR__ . '/../config/register.php';

    $pseudo = trim($_POST['pseudo'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (register($pseudo, $email, $password, $confirm_password, $msgRegister)) {
        // Succès : Rediriger vers l'accueil
        //header('Location: index.html?success=1');
        //exit;
    } else {
        // Erreur : On reste sur la page
        $errorRegister = true;
    }
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lpseudo'])) {
    require_once __DIR__ . '/../config/login.php';

    $lpseudo = trim($_POST['lpseudo'] ?? '');
    $lpassword = $_POST['lpassword'] ?? '';

    if (login($lpseudo, $lpassword, $msgLogin)) {
        // Succès : Rediriger vers l'accueil
        //header('Location: index.html?success=1');
        //exit;
    } else {
        $errorLogin = true;
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
    <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
    <header>
        <?php include __DIR__ . "/../config/nav.php" ?>
    </header>

    <main>
        <!-- Div register -->
        <div class="minor">
            <h1>Créer un compte</h1>

            <?php if ($msgRegister && $errorRegister): ?>
                <p style="color: red;"><?php echo htmlspecialchars($msgRegister); ?></p>
            <?php endif; ?>
            <?php if ($msgRegister && !$errorRegister): ?>
                <p style="color: green;"><?php echo htmlspecialchars($msgRegister); ?></p>
            <?php endif; ?>  

            <form action="compte.php" method="post" class="compte-form">
                <div class="compte-form">
                    <label for="pseudo">Votre pseudo</label>
                    <input type="text" name="pseudo" id="pseudo" required placeholder="ex: Torterra" value="<?php echo htmlspecialchars($pseudo); ?>">
                </div>

                <div class="compte-form">
                    <label for="email">Votre E-mail</label>
                    <input type="email" name="email" id="email" required placeholder="ex: torterra@gmail.com" value="<?php echo htmlspecialchars($email); ?>">
                </div>

                <div class="compte-form">
                    <label for="password">Votre mot de passe</label>
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

        <!-- Div login -->
        <div class="minor">
                <h1>Se connecter</h1>

                <?php if ($msgLogin && $errorLogin): ?>
                    <p style="color: red;"><?php echo htmlspecialchars($msgLogin); ?></p>
                <?php endif; ?>
                <?php if ($msgLogin && !$errorLogin): ?>
                    <p style="color: green;"><?php echo htmlspecialchars($msgLogin); ?></p>
                <?php endif; ?>  

                <!-- Ajouter php htmlspecialchars dans chaque input -->
                <form action="compte.php" method="post" class="compte-form">
                    <div class="compte-form">
                        <label for="lpseudo">Votre pseudo</label>
                        <input type="text" name="lpseudo" id="lpseudo" required placeholder="ex: Torterra" value="<?php echo htmlspecialchars($lpseudo); ?>">
                    </div>

                    <div class="compte-form">
                        <label for="lpassword">Votre mot de passe</label>
                        <input type="password" name="lpassword" id="lpassword" required>
                    </div>

                    <div class="compte-form">
                        <button type="submit">Se connecter</button>
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