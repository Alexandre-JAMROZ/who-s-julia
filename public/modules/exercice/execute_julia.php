<?php
session_start();
require_once '../../../config/db.php';

header('Content-Type: application/json');

// Vérification de la connexion
if (!isset($_SESSION['user'])) {
    echo json_encode(['success' => false, 'message' => 'Non connecté']);
    exit();
}

// Récupérer l'ID de l'utilisateur depuis son pseudo
$stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non trouvé']);
    exit();
}

$user_id = $user['id'];

// Récupération des données
$data = json_decode(file_get_contents('php://input'), true);
$module_id = $data['module_id'] ?? 0;
$exercise_id = $data['exercise_id'] ?? 0;
$code = $data['code'] ?? '';
$action = $data['action'] ?? 'validate'; // 'validate' par défaut, peut être 'execute_only' si besoin

// Configuration Julia - Exécution locale sur la machine
$julia_path = 'C:\\Users\\Utilisateur\\AppData\\Local\\Programs\\Julia-1.11.6\\bin\\julia.exe';

/**
 * Fonction pour exécuter du code Julia localement
 */
function executeJuliaCode($code, $julia_path) {
    if (!file_exists($julia_path)) {
        return ['success' => false, 'error' => 'Julia non trouvé sur la machine locale'];
    }
    
    // Vérifications de sécurité basiques
    $forbidden_patterns = [
        '/\binclude\b/i',
        '/\busing\s+Pkg\b/i',
        '/\bimport\s+Pkg\b/i',
        '/\bPkg\./i',
        '/\brun\s*\(/i',
        '/\bopen\s*\(/i',
        '/\bwrite\s*\(/i',
        '/\brm\s*\(/i',
        '/\bcd\s*\(/i',
        '/\bENV\[/i',
        '/\@eval/i',
        '/\breaddir/i',
        '/\bmkdir/i',
        '/\bdownload/i',
        '/`.*`/'
    ];
    
    foreach ($forbidden_patterns as $pattern) {
        if (preg_match($pattern, $code)) {
            return [
                'success' => false, 
                'error' => 'Code non autorisé détecté pour des raisons de sécurité'
            ];
        }
    }
    
    $temp_file = tempnam(sys_get_temp_dir(), 'julia_');
    $julia_file = $temp_file . '.jl';
    file_put_contents($julia_file, $code);
    
    $command = '"' . $julia_path . '" "' . $julia_file . '" 2>&1';
    exec($command, $output_array, $return_var);
    $output = implode("\n", $output_array);
    
    @unlink($julia_file);
    @unlink($temp_file);
    
    return [
        'success' => ($return_var === 0),
        'output' => trim($output),
        'return_code' => $return_var
    ];
}

/**
 * Fonction pour valider avec des test cases
 */
function validateWithTestCases($user_code, $test_cases, $julia_path, $type_exercice) {
    $passed_tests = 0;
    $total_tests = count($test_cases);
    $test_results = [];
    $raw_output = null;
    $has_error = false;
    
    // D'abord, exécuter le code tel quel pour obtenir la sortie brute
    $raw_result = executeJuliaCode($user_code, $julia_path);
    $raw_output = $raw_result['output'];
    $has_error = !$raw_result['success'];
    
    // Si le code a une erreur, on retourne directement
    if ($has_error) {
        return [
            'all_passed' => false,
            'passed_count' => 0,
            'total_count' => $total_tests,
            'test_results' => [],
            'output' => $raw_output,
            'has_error' => true
        ];
    }
    
    // Ensuite, faire les tests de validation
    foreach ($test_cases as $index => $test) {
        // Créer le code de test complet
        $test_code = $user_code . "\n\n";
        
        // Déterminer le type de test
        if (isset($test['function_call'])) {
            // Test d'une fonction
            $test_code .= "result = " . $test['function_call'] . "\n";
            $test_code .= "println(result)";
            $input_description = $test['function_call'];
        } else if (isset($test['test_type']) && $test['test_type'] === 'variable_check') {
            // Test avec vérification de variables
            if (isset($test['required_vars'])) {
                foreach ($test['required_vars'] as $var) {
                    $test_code .= "\n# Vérification de l'existence de la variable $var\n";
                    $test_code .= "if !@isdefined($var)\n";
                    $test_code .= "    error(\"Variable $var non définie\")\n";
                    $test_code .= "end\n";
                }
            }
            $input_description = 'Exécution directe';
        } else {
            // Test direct du code (exercice output simple)
            $test_code = $user_code;
            $input_description = 'Exécution directe';
        }
        
        // Exécuter le test
        $result = executeJuliaCode($test_code, $julia_path);
        
        if ($result['success']) {
            $actual_output = trim($result['output']);
            $expected_output = trim($test['expected_output']);
            
            $test_passed = ($actual_output === $expected_output);
            
            $test_results[] = [
                'test_number' => $index + 1,
                'passed' => $test_passed,
                'input' => $input_description,
                'expected' => $expected_output,
                'actual' => $actual_output
            ];
            
            if ($test_passed) {
                $passed_tests++;
            }
        } else {
            $test_results[] = [
                'test_number' => $index + 1,
                'passed' => false,
                'input' => $input_description,
                'error' => $result['output'],
                'expected' => $test['expected_output']
            ];
        }
    }
    
    return [
        'all_passed' => ($passed_tests === $total_tests),
        'passed_count' => $passed_tests,
        'total_count' => $total_tests,
        'test_results' => $test_results,
        'output' => $raw_output,
        'has_error' => false
    ];
}

// Si c'est juste une exécution simple (pour tests futurs)
if ($action === 'execute_only') {
    $result = executeJuliaCode($code, $julia_path);
    echo json_encode($result);
    exit();
}

// Sinon, on fait la validation complète
try {
    // Récupérer les informations de l'exercice depuis la base de données
    $stmt = $pdo->prepare("SELECT * FROM exercices WHERE id_module = ? AND id_exercice = ?");
    $stmt->execute([$module_id, $exercise_id]);
    $exercise = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$exercise) {
        echo json_encode(['success' => false, 'message' => 'Exercice non trouvé']);
        exit();
    }
    
    // Décoder les test cases
    $test_cases = null;
    if (!empty($exercise['test_cases'])) {
        $test_cases = json_decode($exercise['test_cases'], true);
    }
    
    if (!$test_cases || !is_array($test_cases)) {
        echo json_encode(['success' => false, 'message' => 'Erreur de configuration de l\'exercice']);
        exit();
    }
    
    // Validation avec test cases
    $validation_result = validateWithTestCases($code, $test_cases, $julia_path, $exercise['type_exercice']);
    $is_correct = $validation_result['all_passed'];
    
    
    if ($is_correct) {
        // Enregistrement de la réussite
        $stmt = $pdo->prepare("
            INSERT INTO exercices_users (id_user, id_module, id_exercice, code_soumis, est_reussi, date_soumission)
            VALUES (?, ?, ?, ?, 1, NOW())
            ON DUPLICATE KEY UPDATE 
                code_soumis = VALUES(code_soumis),
                est_reussi = 1,
                date_soumission = NOW()
        ");
        $stmt->execute([$user_id, $module_id, $exercise_id, $code]);
        
        // Recherche de l'exercice suivant
        $stmt = $pdo->prepare("
            SELECT MIN(id_exercice) as next_ex
            FROM exercices
            WHERE id_module = ? AND id_exercice > ?
        ");
        $stmt->execute([$module_id, $exercise_id]);
        $next = $stmt->fetch(PDO::FETCH_ASSOC);
        
        // Calcul de la progression
        $stmt = $pdo->prepare("
            SELECT 
                COUNT(DISTINCT e.id_exercice) as total,
                COUNT(DISTINCT eu.id_exercice) as completed
            FROM exercices e
            LEFT JOIN exercices_users eu ON e.id_module = eu.id_module 
                AND e.id_exercice = eu.id_exercice 
                AND eu.id_user = ? 
                AND eu.est_reussi = 1
            WHERE e.id_module = ?
        ");
        $stmt->execute([$user_id, $module_id]);
        $progress = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $response = [
            'success' => true,
            'message' => 'Tous les tests sont passés ! 🎉',
            'next_exercise' => $next['next_ex'],
            'completed' => $progress['completed'],
            'total' => $progress['total'],
            'validation_details' => $validation_result,
            'output' => $validation_result['output']
        ];
        
        if (isset($validation_result['warning'])) {
            $response['warning'] = $validation_result['warning'];
        }
        
        echo json_encode($response);
        
    } else {
        // Sauvegarde du code même si incorrect
        $stmt = $pdo->prepare("
            INSERT INTO exercices_users (id_user, id_module, id_exercice, code_soumis, est_reussi, date_soumission)
            VALUES (?, ?, ?, ?, 0, NOW())
            ON DUPLICATE KEY UPDATE 
                code_soumis = VALUES(code_soumis),
                est_reussi = 0,
                date_soumission = NOW()
        ");
        $stmt->execute([$user_id, $module_id, $exercise_id, $code]);
        
        echo json_encode([
            'success' => false,
            'message' => 'Certains tests ont échoué. Vérifiez votre code.',
            'validation_details' => $validation_result,
            'output' => $validation_result['output']
        ]);
    }
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Erreur lors de la validation : ' . $e->getMessage()
    ]);
}
?>