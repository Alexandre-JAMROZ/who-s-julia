<?php

session_start();
if (isset($_GET['action']) && $_GET['action'] === 'logout') {
  require_once __DIR__ . "/../config/logout.php";
  exit;
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apprendre Julia</title>
  <link rel="stylesheet" href="/static/css/styles.css">
</head>
<body>
  <header>
    <?php include __DIR__ . "/../config/nav.php" ?>
  </header>

  <!-- Affichage message bienvenue si user connecté-->
  <?php if (isset($_SESSION['user'])): ?>
    <div class="welcome-banner">
      <h1 style="text-align: center;">Bienvenue <?php echo htmlspecialchars($_SESSION['user']); ?></h1>
    </div>
  <?php endif; ?>  
  
  <main>
    <!-- Présentation du langage Julia -->
    <section class="main" id="presentation">
      <article>
        <h1>Qu'est-ce que Julia&nbsp;?</h1>
        <p>Julia est un langage de programmation performant et dynamique, idéal pour le calcul scientifique, l'analyse de données et le développement de modèles prédictifs. Conçu pour combiner la facilité d'écriture de Python avec la rapidité du C, Julia permet d'exécuter des algorithmes complexes en un temps record.</p>
        <h2>Pourquoi utiliser Julia&nbsp;?</h2>
        <ul>
          <li>Haute performance grâce à la compilation Just-In-Time (JIT).</li>
          <li>Syntaxe claire et expressive.</li>
          <li>Écosystème riche pour le calcul scientifique et l'apprentissage automatique.</li>
          <li>Interopérabilité avec C, Python, R et plus.</li>
        </ul>
      </article>
    </section>
    <!-- Modules / Niveaux et exercices -->
    <section class="minor" id="modules">
      <h2>Modules: Niveaux et Exercices</h2>
      <p>Notre site est organisée en modules progressifs, du niveau débutant à avancé. Chaque module contient des exercices interactifs dans le contexte d'un jeu textuel de fantaisie pour mettre en pratique vos connaissances en Julia.</p>
      <a href="/modules/accueill/accueil.html" class="btn">Commencer l'aventure !</a>
    </section>
  </main>
  <footer>
    <p><strong>Contacts&nbsp;:</strong> alexandrejamrozpro@gmail.com | lucas.jamet03@gmail.com </p>
    <p><strong>Université&nbsp;:</strong> Université de la Nouvelle-Calédonie</p>
    <p>Projet Tutoré - Alexandre JAMROZ & Lucas JAMET</p>
  </footer>
</body>
</html>
