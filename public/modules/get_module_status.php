<?php
session_start();
require_once '../../config/db.php';

header('Content-Type: application/json');

// Si l'utilisateur n'est pas connecté, tous les modules sont verrouillés sauf le premier
if (!isset($_SESSION['user'])) {
    echo json_encode([
        1 => ['unlocked' => true, 'started' => false, 'completed' => false],
        2 => ['unlocked' => false, 'started' => false, 'completed' => false],
        3 => ['unlocked' => false, 'started' => false, 'completed' => false]
    ]);
    exit();
}

// Récupérer l'ID de l'utilisateur depuis son pseudo
$stmt = $pdo->prepare("SELECT id FROM users WHERE pseudo = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    echo json_encode(['error' => 'Utilisateur non trouvé']);
    exit();
}

$user_id = $user['id'];

try {
    // Récupération du statut de chaque module
    $stmt = $pdo->prepare("
        SELECT 
            m.id_module,
            COUNT(DISTINCT e.id_exercice) as total_exercises,
            COUNT(DISTINCT CASE WHEN eu.est_reussi = 1 THEN eu.id_exercice END) as completed_exercises,
            COUNT(DISTINCT eu.id_exercice) as started_exercises
        FROM modules m
        LEFT JOIN exercices e ON m.id_module = e.id_module
        LEFT JOIN exercices_users eu ON e.id_module = eu.id_module 
            AND e.id_exercice = eu.id_exercice 
            AND eu.id_user = ?
        GROUP BY m.id_module
        ORDER BY m.id_module
    ");
    $stmt->execute([$user_id]);
    $modules = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $status = [];
    $previous_completed = true; // Le premier module est toujours débloqué
    
    foreach ($modules as $module) {
        $module_id = $module['id_module'];
        $completed = ($module['total_exercises'] > 0 && 
                     $module['completed_exercises'] == $module['total_exercises']);
        $started = $module['started_exercises'] > 0;
        
        // Un module est débloqué si le précédent est complété
        $unlocked = $previous_completed || $module_id == 1;
        
        $status[$module_id] = [
            'unlocked' => $unlocked,
            'started' => $started,
            'completed' => $completed,
            'progress' => $module['total_exercises'] > 0 ? 
                         $module['completed_exercises'] . '/' . $module['total_exercises'] : '0/0'
        ];
        
        // Pour le prochain module, vérifier si celui-ci est complété
        $previous_completed = $completed;
    }
    
    echo json_encode($status);
    
} catch (Exception $e) {
    echo json_encode(['error' => 'Erreur lors de la récupération du statut']);
}
?>