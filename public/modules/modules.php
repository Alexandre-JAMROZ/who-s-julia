<?php
session_start();
require_once '../../config/db.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sélection des Modules - Apprendre Julia</title>
  <link rel="stylesheet" href="../../static/css/styles.css">
  <link rel="stylesheet" href="../../static/css/modules-tree.css">
  <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
  <header>
        <div class="nav-container">
    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="../../documentation/documentation.php">Documentation</a></li>
            <li><a href="/modules/modules.php">Modules</a></li>
            <li><a href="/compte.php">Mon compte</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a class="deconnect" href="../../../index.php?action=logout">Déconnexion</a></li>
            <?php endif; ?>
        </ul>
    </nav>
    <div class="profile">
        <img src="/static/img/basic_profile_picture.png" alt="Photo de profil">
    </div>
</div>
    </header>
  
  <main>
    <section class="main">
      <h1>Choisissez votre niveau d'aventure</h1>
      <p>Progressez à travers les différents modules de Julia dans un univers fantastique. Chaque niveau vous permettra d'acquérir de nouvelles compétences en programmation tout en vivant une aventure interactive.</p>
    </section>
    
    <section class="modules-tree">
      <div id="tree-container"></div>
    </section>
  </main>
  
  <footer>
    <p><strong>Contacts :</strong> alexandrejamrozpro@gmail.com | lucas.jamet03@gmail.com </p>
    <p><strong>Université :</strong> Université de la Nouvelle-Calédonie</p>
    <p>Projet Tutoré - Alexandre JAMROZ & Lucas JAMET</p>
  </footer>
  
  <!-- D3.js -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/d3/7.8.5/d3.min.js"></script>
  <!-- script pour l'arborescence -->
  <script src="../../static/js/tree-d3.js"></script>
</body>
</html>