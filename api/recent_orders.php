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
        SELECT o.id, o.order_number, c.name as customer_name, 
               o.total_amount, DATE_FORMAT(o.created_at, '%M %d, %Y') as created_at
        FROM orders o
        JOIN customers c ON o.customer_id = c.id
        ORDER BY o.created_at DESC
        LIMIT 5
    ");
    
    $orders = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true,
        'orders' => $orders
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
