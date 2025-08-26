<!-- Barre de navigation -->
<div class="nav-container">
    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="documentation/documentation.php">Documentation</a></li>
            <li><a href="modules/modules.html">Modules</a></li>
            <li><a href="/compte.php">Mon compte</a></li>
            <?php include __DIR__ . "/../config/logoutButton.php"; ?>
        </ul>
    </nav>
    <div class="profile">
        <img src="https://via.placeholder.com/40" alt="Photo de profil">
    </div>
</div>