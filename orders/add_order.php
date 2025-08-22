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
    $order_number = trim($_POST['order_number']);
    $customer_id = (int)$_POST['customer_id'];
    $order_date = $_POST['order_date'];
    $delivery_date = $_POST['delivery_date'] ?: null;
    $notes = trim($_POST['notes']);
    
    if (empty($order_number) || empty($customer_id) || empty($order_date)) {
        echo json_encode(['success' => false, 'error' => 'Order number, customer, and order date are required']);
        exit();
    }
    
    // Check if order number already exists
    $stmt = $pdo->prepare("SELECT id FROM orders WHERE order_number = ?");
    $stmt->execute([$order_number]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Order number already exists']);
        exit();
    }
    
    // Verify customer exists
    $stmt = $pdo->prepare("SELECT id FROM customers WHERE id = ? AND active = 1");
    $stmt->execute([$customer_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Invalid customer selected']);
        exit();
    }
    
    // Insert order
    $stmt = $pdo->prepare("
        INSERT INTO orders (order_number, customer_id, order_date, delivery_date, notes, created_by)
        VALUES (?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $order_number,
        $customer_id,
        $order_date,
        $delivery_date,
        $notes ?: null,
        $_SESSION['user_id']
    ]);
    
    $order_id = $pdo->lastInsertId();
    
    echo json_encode([
        'success' => true, 
        'message' => 'Order created successfully',
        'order_id' => $order_id
    ]);
    
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'error' => 'Order number already exists']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error occurred']);
    }
}
?>
