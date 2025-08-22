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
    $category_id = (int)$_POST['category_id'] ?: null;
    $sku = trim($_POST['sku']);
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $cost_price = (float)$_POST['cost_price'] ?: 0;
    $selling_price = (float)$_POST['selling_price'] ?: 0;
    $min_stock_level = (int)$_POST['min_stock_level'] ?: 0;
    $max_stock_level = (int)$_POST['max_stock_level'] ?: 0;
    $initial_quantity = (int)$_POST['initial_quantity'] ?: 0;
    
    if (empty($sku) || empty($name)) {
        echo json_encode(['success' => false, 'error' => 'SKU and Product Name are required']);
        exit();
    }
    
    // Check if SKU already exists
    $stmt = $pdo->prepare("SELECT id FROM products WHERE sku = ?");
    $stmt->execute([$sku]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'SKU already exists']);
        exit();
    }
    
    // Start transaction
    $pdo->beginTransaction();
    
    // Insert product
    $stmt = $pdo->prepare("
        INSERT INTO products (category_id, sku, name, description, cost_price, selling_price, min_stock_level, max_stock_level)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $category_id,
        $sku,
        $name,
        $description ?: null,
        $cost_price,
        $selling_price,
        $min_stock_level,
        $max_stock_level
    ]);
    
    $product_id = $pdo->lastInsertId();
    
    // Add initial inventory if specified
    if ($initial_quantity > 0) {
        $stmt = $pdo->prepare("
            INSERT INTO inventory (product_id, quantity_on_hand, quantity_reserved)
            VALUES (?, ?, 0)
        ");
        $stmt->execute([$product_id, $initial_quantity]);
        
        // Record inventory movement
        $stmt = $pdo->prepare("
            INSERT INTO inventory_movements (product_id, location, movement_type, quantity, reference_type, notes, created_by)
            VALUES (?, 'Main Warehouse', 'in', ?, 'adjustment', 'Initial stock', ?)
        ");
        $stmt->execute([$product_id, $initial_quantity, $_SESSION['user_id']]);
    }
    
    $pdo->commit();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Product added successfully',
        'product_id' => $product_id
    ]);
    
} catch (PDOException $e) {
    $pdo->rollBack();
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'error' => 'SKU already exists']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error occurred']);
    }
}
?>
