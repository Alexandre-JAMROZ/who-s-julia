<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Module : <?php echo htmlspecialchars($module_info['nom_module'] ?? 'Module'); ?> - Exercice <?php echo $current_exercise; ?></title>
    <link rel="stylesheet" href="../../../static/css/styles.css">
    <link rel="stylesheet" href="../../../static/css/styles-exercices.css">
    <!-- CodeMirror CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/theme/monokai.min.css">
    <link rel="icon" type="image/x-icon" href="/static/img/favicon.ico">
</head>
<body>
    <?php
    session_start();
    require_once '../../../config/db.php';
    
    // Vérification de la connexion
    if (!isset($_SESSION['user'])) {
        // Sauvegarder l'URL pour retour après connexion
        $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
        header('Location: ../../../compte.php');
        exit();
    }
    
    // Récupérer l'ID de l'utilisateur depuis son pseudo
    $stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$user) {
        header('Location: ../../../compte.php');
        exit();
    }
    
    $user_id = $user['id'];
    $module_id = isset($_GET['module']) ? intval($_GET['module']) : 1;
    
    // Récupération des infos du module
    $stmt = $pdo->prepare("SELECT * FROM modules WHERE id_module = ?");
    $stmt->execute([$module_id]);
    $module_info = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$module_info) {
        header('Location: ../modules.html');
        exit();
    }
    
    // Récupération du dernier exercice non réussi ou de l'exercice demandé
    $current_exercise = isset($_GET['ex']) ? intval($_GET['ex']) : null;
    
    if (!$current_exercise) {
        // Trouver le premier exercice non réussi
        $stmt = $pdo->prepare("
            SELECT MIN(e.id_exercice) as next_ex
            FROM exercices e
            LEFT JOIN exercices_users eu ON e.id_module = eu.id_module 
                AND e.id_exercice = eu.id_exercice 
                AND eu.id_user = ?
            WHERE e.id_module = ? AND (eu.est_reussi IS NULL OR eu.est_reussi = 0)
        ");
        $stmt->execute([$user_id, $module_id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $current_exercise = $result['next_ex'] ?? 1;
    }
    
    // Récupération de l'exercice actuel
    $stmt = $pdo->prepare("SELECT * FROM exercices WHERE id_module = ? AND id_exercice = ?");
    $stmt->execute([$module_id, $current_exercise]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$exercise) {
        header('Location: ../modules.html');
        exit();
    }
    
    // Décoder les test_cases pour déterminer le type d'exercice
    $test_cases = json_decode($exercise['test_cases'], true);
    $is_function_exercise = false;
    $example_text = '';
    
    if ($test_cases && is_array($test_cases)) {
        // Vérifier si c'est un exercice de fonction
        if (isset($test_cases[0]['function_call'])) {
            $is_function_exercise = true;
            // Extraire l'exemple de l'énoncé si présent
            if (preg_match('/Exemple:\s*(.+?)(?:\n|$)/s', $exercise['enonce'], $matches)) {
                $example_text = $matches[1];
            }
        }
    }
    
    // Récupération de tous les exercices du module pour la navigation
    $stmt = $pdo->prepare("
        SELECT e.*, eu.est_reussi 
        FROM exercices e
        LEFT JOIN exercices_users eu ON e.id_module = eu.id_module 
            AND e.id_exercice = eu.id_exercice 
            AND eu.id_user = ?
        WHERE e.id_module = ?
        ORDER BY e.id_exercice
    ");
    $stmt->execute([$user_id, $module_id]);
    $all_exercises = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Calcul de la progression
    $total_exercises = count($all_exercises);
    $completed_exercises = 0;
    foreach ($all_exercises as $ex) {
        if ($ex['est_reussi']) {
            $completed_exercises++;
        }
    }
    
    // Récupération du code sauvegardé si existe
    $stmt = $pdo->prepare("SELECT code_soumis FROM exercices_users WHERE id_user = ? AND id_module = ? AND id_exercice = ?");
    $stmt->execute([$user_id, $module_id, $current_exercise]);
    $saved_code = $stmt->fetch(PDO::FETCH_ASSOC);
    $user_code = $saved_code['code_soumis'] ?? '';
    ?>
    
    <header>
        <div class="nav-container">
    <nav>
        <ul>
            <li><a href="/">Accueil</a></li>
            <li><a href="../../documentation/documentation.php">Documentation</a></li>
            <li><a href="../modules.php">Modules</a></li>
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
    
    <main class="exercice-container">
        <div class="exercice-header">
            <h1><?php echo htmlspecialchars($module_info['nom_module']); ?></h1>
            <div class="progress-info">
                <span class="progress-text">Progression : <?php echo $completed_exercises; ?>/<?php echo $total_exercises; ?></span>
                <div class="progress-bar">
                    <div class="progress-fill" style="width: <?php echo ($completed_exercises / $total_exercises) * 100; ?>%"></div>
                </div>
            </div>
            <div class="exercise-selector">
                <select id="exercise-dropdown" onchange="changeExercise(this.value)">
                    <?php foreach ($all_exercises as $ex): ?>
                        <option value="<?php echo $ex['id_exercice']; ?>" 
                                <?php echo $ex['id_exercice'] == $current_exercise ? 'selected' : ''; ?>>
                            <?php echo $ex['id_exercice']; ?>. <?php echo htmlspecialchars($ex['titre']); ?>
                            <?php echo $ex['est_reussi'] ? ' ✓' : ''; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        
        <div class="exercice-content">
            <div class="left-panel">
                <div class="exercise-info">
                    <h2>Exercice <?php echo $current_exercise; ?> : <?php echo htmlspecialchars($exercise['titre']); ?></h2>
                    <div class="enonce">
                        <?php 
                        $enonce = $exercise['enonce'];
                        if ($is_function_exercise) {
                            $enonce = preg_replace('/\n\nExemple:.*$/s', '', $enonce);
                        }
                        echo nl2br(htmlspecialchars($enonce)); 
                        ?>
                    </div>
                    
                    <?php if ($is_function_exercise && $example_text): ?>
                    <div class="example-section">
                        <h3>Exemple :</h3>
                        <pre><?php echo htmlspecialchars($example_text); ?></pre>
                    </div>
                    <?php elseif (!$is_function_exercise && isset($test_cases[0]['expected_output'])): ?>
                    <div class="expected-output">
                        <h3>Résultat attendu :</h3>
                        <pre><?php echo htmlspecialchars($test_cases[0]['expected_output']); ?></pre>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="code-editor-section">
                    <div class="editor-header">
                        <span>Éditeur Julia</span>
                        <button type="button" onclick="clearCode()" class="btn-secondary">Effacer</button>
                    </div>
                    <textarea id="code-editor"><?php echo htmlspecialchars($user_code); ?></textarea>
                </div>
                
                <div class="action-buttons">
                    <button type="button" onclick="validateCode()" class="btn-primary">Exécuter et Valider</button>
                </div>
            </div>
            
            <div class="right-panel">
                <div class="output-section">
                    <h3>Sortie du programme</h3>
                    <div id="output" class="output-display"></div>
                </div>
                <div id="feedback" class="feedback-section"></div>
            </div>
        </div>
    </main>
    
    <footer>
        <p><strong>Contacts :</strong> alexandrejamrozpro@gmail.com | lucas.jamet03@gmail.com</p>
        <p><strong>Université :</strong> Université de la Nouvelle-Calédonie</p>
        <p>Projet Tutoré - Alexandre JAMROZ & Lucas JAMET</p>
    </footer>
    
    <!-- CodeMirror JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.2/mode/julia/julia.min.js"></script>
    
    <!-- Variables PHP accessibles en JS -->
    <script>
    const moduleId = <?php echo json_encode($module_id); ?>;
    const currentExercise = <?php echo json_encode($current_exercise); ?>;
    </script>

    <script src="../../static/js/exercice.js" defer></script>

</body>
</html>