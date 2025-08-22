<?php
// Web interface to load demo data
session_start();
require_once 'config/database.php';
require_once 'includes/auth.php';

// Only allow admin users to load demo data
if (!isLoggedIn() || !hasRole('admin')) {
    header('Location: login.php');
    exit();
}

$message = '';
$error = '';

if ($_POST && isset($_POST['load_demo'])) {
    try {
        // Read and execute the demo data SQL
        $sql = file_get_contents('database/demo_data.sql');
        
        // Split into individual statements
        $statements = explode(';', $sql);
        
        $count = 0;
        foreach ($statements as $statement) {
            $statement = trim($statement);
            if (!empty($statement) && !preg_match('/^(--|USE|\/\*)/', $statement)) {
                try {
                    $pdo->exec($statement);
                    $count++;
                } catch (PDOException $e) {
                    // Skip errors for duplicate entries
                    if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                        throw $e;
                    }
                }
            }
        }
        
        $message = "Demo data loaded successfully! Added sample data including 12 orders, 8 workers, 10 products, and more.";
        
    } catch (Exception $e) {
        $error = "Error loading demo data: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Load Demo Data - GarmentsTrack ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    
    <div class="container-fluid">
        <div class="row">
            <?php include 'includes/sidebar.php'; ?>
            
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h2">Load Demo Data</h1>
                </div>
                
                <?php if ($message): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($message); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <?php if ($error): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-database"></i> Demo Data Loader</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <h6>What will be loaded:</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-users text-primary"></i> <strong>9 Additional Customers</strong> - Various companies and individuals</li>
                                    <li><i class="fas fa-box text-success"></i> <strong>10 Products</strong> - Shirts, pants, dresses, and accessories with variants</li>
                                    <li><i class="fas fa-user-hard-hat text-info"></i> <strong>8 Workers</strong> - Different departments and skill levels</li>
                                    <li><i class="fas fa-shopping-cart text-warning"></i> <strong>12 Orders</strong> - Various statuses and customers (as requested)</li>
                                    <li><i class="fas fa-industry text-secondary"></i> <strong>Production Data</strong> - Production orders and tasks</li>
                                    <li><i class="fas fa-boxes text-primary"></i> <strong>Inventory</strong> - Stock levels and movements</li>
                                    <li><i class="fas fa-palette text-success"></i> <strong>5 Sample Designs</strong> - Various approval stages</li>
                                    <li><i class="fas fa-money-bill-wave text-info"></i> <strong>Payroll Records</strong> - Worker payment history</li>
                                    <li><i class="fas fa-clock text-warning"></i> <strong>Time Entries</strong> - Worker time tracking data</li>
                                </ul>
                                
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> <strong>Note:</strong> This will populate your system with realistic sample data for demonstration and testing purposes. Existing data will not be affected.
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <i class="fas fa-database fa-4x text-muted mb-3"></i>
                                    <form method="POST">
                                        <button type="submit" name="load_demo" class="btn btn-primary btn-lg">
                                            <i class="fas fa-download"></i> Load Demo Data
                                        </button>
                                    </form>
                                    <p class="text-muted mt-2">This may take a few seconds</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow mt-4">
                    <div class="card-header">
                        <h6 class="mb-0">After Loading Demo Data</h6>
                    </div>
                    <div class="card-body">
                        <p>Once demo data is loaded, you can:</p>
                        <div class="row">
                            <div class="col-md-6">
                                <ul>
                                    <li>View realistic dashboard metrics</li>
                                    <li>Browse through sample orders</li>
                                    <li>Check inventory levels</li>
                                    <li>Review production schedules</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <ul>
                                    <li>Examine worker performance data</li>
                                    <li>View sample designs and approvals</li>
                                    <li>Check payroll calculations</li>
                                    <li>Generate reports with real data</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
