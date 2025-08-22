<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

try {
    $stmt = $pdo->query("
        SELECT w.id, u.full_name as name, w.department,
               COALESCE(
                   (SELECT AVG(
                       CASE 
                           WHEN pt.quantity_target > 0 
                           THEN (pt.quantity_completed / pt.quantity_target) * 100
                           ELSE 100
                       END
                   )
                   FROM production_tasks pt 
                   WHERE pt.assigned_worker_id = w.id 
                   AND pt.created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                   ), 0
               ) as efficiency
        FROM workers w
        LEFT JOIN users u ON w.user_id = u.id
        WHERE w.active = 1
        ORDER BY efficiency DESC
        LIMIT 5
    ");
    
    $performance = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'performance' => $performance
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
