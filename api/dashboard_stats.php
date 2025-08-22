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
    // Get active workers count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM workers WHERE active = 1");
    $active_workers = $stmt->fetch()['count'];
    
    // Get monthly revenue (current month)
    $stmt = $pdo->query("
        SELECT COALESCE(SUM(total_amount), 0) as revenue 
        FROM orders 
        WHERE MONTH(order_date) = MONTH(CURRENT_DATE()) 
        AND YEAR(order_date) = YEAR(CURRENT_DATE())
        AND status != 'cancelled'
    ");
    $monthly_revenue = $stmt->fetch()['revenue'];
    
    // Get pending orders count
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM orders WHERE status IN ('pending', 'confirmed')");
    $pending_orders = $stmt->fetch()['count'];
    
    // Get low stock items count
    $stmt = $pdo->query("
        SELECT COUNT(*) as count 
        FROM inventory i 
        JOIN products p ON i.product_id = p.id 
        WHERE i.quantity_available <= p.min_stock_level 
        AND p.active = 1
    ");
    $low_stock_items = $stmt->fetch()['count'];
    
    echo json_encode([
        'success' => true,
        'stats' => [
            'active_workers' => (int)$active_workers,
            'monthly_revenue' => (float)$monthly_revenue,
            'pending_orders' => (int)$pending_orders,
            'low_stock_items' => (int)$low_stock_items
        ]
    ]);
    
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => 'Database error']);
}
?>
