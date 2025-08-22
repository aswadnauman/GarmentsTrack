<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

// Get payroll records
$stmt = $pdo->query("
    SELECT p.*, w.employee_id, u.full_name as worker_name
    FROM payroll p
    JOIN workers w ON p.worker_id = w.id
    LEFT JOIN users u ON w.user_id = u.id
    ORDER BY p.pay_period_end DESC
");
$payroll_records = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payroll - GarmentsTrack ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include '../includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include '../includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Payroll Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary me-2" onclick="generatePayroll()">
                            <i class="fas fa-calculator"></i> Generate Payroll
                        </button>
                        <button type="button" class="btn btn-success" onclick="processPayments()">
                            <i class="fas fa-money-bill-wave"></i> Process Payments
                        </button>
                    </div>
                </div>
                
                <!-- Payroll Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            This Month</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-warning shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                            Pending Approval</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-success shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                            Paid This Month</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-info shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                            Active Workers</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-users fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Payroll Records</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($payroll_records)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-money-bill-wave fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Payroll Records</h5>
                                <p class="text-muted">Generate payroll for your workers based on their time entries and piece work.</p>
                                <button class="btn btn-primary" onclick="generatePayroll()">
                                    <i class="fas fa-calculator"></i> Generate First Payroll
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Employee ID</th>
                                            <th>Worker Name</th>
                                            <th>Pay Period</th>
                                            <th>Regular Hours</th>
                                            <th>Overtime</th>
                                            <th>Piece Work</th>
                                            <th>Gross Pay</th>
                                            <th>Net Pay</th>
                                            <th>Status</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payroll_records as $record): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($record['employee_id']); ?></td>
                                                <td><?php echo htmlspecialchars($record['worker_name'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <?php echo date('M d', strtotime($record['pay_period_start'])); ?> - 
                                                    <?php echo date('M d, Y', strtotime($record['pay_period_end'])); ?>
                                                </td>
                                                <td><?php echo number_format($record['regular_hours'], 1); ?></td>
                                                <td><?php echo number_format($record['overtime_hours'], 1); ?></td>
                                                <td>₹<?php echo number_format($record['piece_work_amount'], 2); ?></td>
                                                <td>₹<?php echo number_format($record['gross_pay'], 2); ?></td>
                                                <td>₹<?php echo number_format($record['net_pay'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    $statusColors = [
                                                        'draft' => 'secondary',
                                                        'approved' => 'warning',
                                                        'paid' => 'success'
                                                    ];
                                                    $color = $statusColors[$record['status']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?php echo $color; ?>">
                                                        <?php echo ucfirst($record['status']); ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewPayslip(<?php echo $record['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generatePayroll() {
            alert('Payroll generation will include:\n\n• Time entry calculations\n• Piece-rate billing\n• Overtime calculations\n• Deductions\n• Tax calculations\n• Payslip generation');
        }
        
        function processPayments() {
            alert('Payment processing will handle:\n\n• Bank transfer integration\n• Payment confirmations\n• Receipt generation\n• Payment history\n• Bulk payments');
        }
        
        function viewPayslip(id) {
            alert('Payslip details will show:\n\n• Detailed breakdown\n• Hours worked\n• Rates applied\n• Deductions\n• Net payment\n• Print/download options');
        }
    </script>
</body>
</html>
