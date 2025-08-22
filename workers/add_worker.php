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
    $employee_id = trim($_POST['employee_id']);
    $full_name = trim($_POST['full_name']);
    $department = trim($_POST['department']);
    $position = trim($_POST['position']);
    $phone = trim($_POST['phone']);
    $hire_date = $_POST['hire_date'] ?: null;
    $hourly_rate = $_POST['hourly_rate'] ?: null;
    $piece_rate = $_POST['piece_rate'] ?: null;
    $address = trim($_POST['address']);
    
    if (empty($employee_id) || empty($full_name)) {
        echo json_encode(['success' => false, 'error' => 'Employee ID and Full Name are required']);
        exit();
    }
    
    // Check if employee ID already exists
    $stmt = $pdo->prepare("SELECT id FROM workers WHERE employee_id = ?");
    $stmt->execute([$employee_id]);
    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'error' => 'Employee ID already exists']);
        exit();
    }
    
    // Insert worker
    $stmt = $pdo->prepare("
        INSERT INTO workers (employee_id, department, position, hire_date, hourly_rate, piece_rate, phone, address)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    
    $stmt->execute([
        $employee_id,
        $department ?: null,
        $position ?: null,
        $hire_date,
        $hourly_rate,
        $piece_rate,
        $phone ?: null,
        $address ?: null
    ]);
    
    echo json_encode(['success' => true, 'message' => 'Worker added successfully']);
    
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        echo json_encode(['success' => false, 'error' => 'Employee ID already exists']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Database error occurred']);
    }
}
?>
