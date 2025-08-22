<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(['success' => false, 'error' => 'Unauthorized']);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
    exit();
}

try {
    $inventory_id = (int)$_POST['inventory_id'];
    $adjustment_type = $_POST['adjustment_type']; // 'increase' or 'decrease'
    $quantity = (int)$_POST['quantity'];
    $reason = trim($_POST['reason']);
    
    if (empty($inventory_id) || empty($adjustment_type) || $quantity <= 0) {
        echo json_encode(['success' => false, 'error' => 'Invalid adjustment parameters']);
        exit();
    }
    
    // Get current inventory
    $stmt = $pdo->prepare("SELECT * FROM inventory WHERE id = ?");
    $stmt->execute([$inventory_id]);
    $inventory = $stmt->fetch();
    
    if (!$inventory) {
        echo json_encode(['success' => false, 'error' => 'Inventory item not found']);
        exit();
    }
    
    // Calculate new quantity
    $current_quantity = $inventory['quantity_on_hand'];
    if ($adjustment_type === 'increase') {
        $new_quantity = $current_quantity + $quantity;
        $movement_type = 'in';
    } else {
        $new_quantity = $current_quantity - $quantity;
        $movement_type = 'out';
        
        if ($new_quantity < 0) {
            echo json_encode(['success' => false, 'error' => 'Insufficient stock for adjustment']);
            exit();
        }
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Update inventory
    $stmt = $pdo->prepare("UPDATE inventory SET quantity_on_hand = ? WHERE id = ?");
    $stmt->execute([$new_quantity, $inventory_id]);
    
    // Record movement
    $stmt = $pdo->prepare("
        INSERT INTO inventory_movements (product_id, variant_id, location, movement_type, quantity, reference_type, notes, created_by)
        VALUES (?, ?, ?, ?, ?, 'adjustment', ?, ?)
    ");
    $stmt->execute([
        $inventory['product_id'],
        $inventory['variant_id'],
        $inventory['location'],
        $movement_type,
        $quantity,
        $reason ?: 'Stock adjustment',
        $_SESSION['user_id']
    ]);
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Stock adjusted successfully',
        'new_quantity' => $new_quantity
    ]);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => 'Database error occurred']);
}
?>
