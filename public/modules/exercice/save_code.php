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

try {
    // Sauvegarde du code sans modifier est_reussi
    $stmt = $pdo->prepare("
        INSERT INTO exercices_users (id_user, id_module, id_exercice, code_soumis, date_soumission)
        VALUES (?, ?, ?, ?, NOW())
        ON DUPLICATE KEY UPDATE 
            code_soumis = VALUES(code_soumis),
            date_soumission = NOW()
    ");
    $stmt->execute([$user_id, $module_id, $exercise_id, $code]);
    
    echo json_encode(['success' => true, 'message' => 'Code sauvegardé']);
    
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur de sauvegarde']);
}
?>