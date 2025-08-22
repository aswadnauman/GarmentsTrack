<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

// Get samples
$stmt = $pdo->query("
    SELECT s.*, c.name as customer_name, u.full_name as created_by_name
    FROM samples s
    LEFT JOIN customers c ON s.customer_id = c.id
    LEFT JOIN users u ON s.created_by = u.id
    ORDER BY s.created_at DESC
");
$samples = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Samples - GarmentsTrack ERP</title>
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
                    <h1 class="h2">Sample Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" onclick="createSample()">
                            <i class="fas fa-plus"></i> New Sample
                        </button>
                    </div>
                </div>
                
                <!-- Sample Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Samples</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo count($samples); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-palette fa-2x text-gray-300"></i>
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
                                            In Development</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-cog fa-2x text-gray-300"></i>
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
                                            Approved</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
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
                                            Pending Review</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-clock fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">Sample Catalog</h6>
                    </div>
                    <div class="card-body">
                        <?php if (empty($samples)): ?>
                            <div class="text-center py-4">
                                <i class="fas fa-palette fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">No Samples Created</h5>
                                <p class="text-muted">Start creating samples for your customers to showcase your designs.</p>
                                <button class="btn btn-primary" onclick="createSample()">
                                    <i class="fas fa-plus"></i> Create First Sample
                                </button>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Sample Code</th>
                                            <th>Product Name</th>
                                            <th>Customer</th>
                                            <th>Colors</th>
                                            <th>Sizes</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($samples as $sample): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($sample['sample_code']); ?></td>
                                                <td><?php echo htmlspecialchars($sample['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($sample['customer_name'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($sample['colors'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($sample['sizes'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <?php
                                                    $statusColors = [
                                                        'requested' => 'secondary',
                                                        'in_development' => 'warning',
                                                        'completed' => 'info',
                                                        'approved' => 'success',
                                                        'rejected' => 'danger'
                                                    ];
                                                    $color = $statusColors[$sample['status']] ?? 'secondary';
                                                    ?>
                                                    <span class="badge bg-<?php echo $color; ?>">
                                                        <?php echo ucfirst(str_replace('_', ' ', $sample['status'])); ?>
                                                    </span>
                                                </td>
                                                <td><?php echo date('M d, Y', strtotime($sample['created_at'])); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="viewSample(<?php echo $sample['id']; ?>)">
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
        function createSample() {
            alert('Sample creation will include:\n\n• Product specifications\n• Color and size options\n• Fabric details\n• Customer requirements\n• Photo uploads\n• Cost estimation');
        }
        
        function viewSample(id) {
            alert('Sample details will show:\n\n• Design specifications\n• Progress photos\n• Customer feedback\n• Approval status\n• Cost breakdown');
        }
    </script>
</body>
</html>
