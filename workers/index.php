<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

// Get workers list
$stmt = $pdo->query("
    SELECT w.*, u.full_name, u.email, u.active as user_active
    FROM workers w
    LEFT JOIN users u ON w.user_id = u.id
    ORDER BY w.created_at DESC
");
$workers = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Workers - GarmentsTrack ERP</title>
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
                    <h1 class="h2">Workers Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addWorkerModal">
                            <i class="fas fa-plus"></i> Add Worker
                        </button>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee ID</th>
                                        <th>Name</th>
                                        <th>Department</th>
                                        <th>Position</th>
                                        <th>Phone</th>
                                        <th>Hire Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($workers)): ?>
                                        <tr>
                                            <td colspan="8" class="text-center text-muted">No workers found</td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($workers as $worker): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($worker['employee_id']); ?></td>
                                                <td><?php echo htmlspecialchars($worker['full_name'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($worker['department'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($worker['position'] ?: 'N/A'); ?></td>
                                                <td><?php echo htmlspecialchars($worker['phone'] ?: 'N/A'); ?></td>
                                                <td><?php echo $worker['hire_date'] ? date('M d, Y', strtotime($worker['hire_date'])) : 'N/A'; ?></td>
                                                <td>
                                                    <?php if ($worker['active']): ?>
                                                        <span class="badge bg-success">Active</span>
                                                    <?php else: ?>
                                                        <span class="badge bg-danger">Inactive</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="editWorker(<?php echo $worker['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-outline-info" onclick="viewWorker(<?php echo $worker['id']; ?>)">
                                                        <i class="fas fa-eye"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <!-- Add Worker Modal -->
    <div class="modal fade" id="addWorkerModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Worker</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="addWorkerForm">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Employee ID *</label>
                                    <input type="text" class="form-control" name="employee_id" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Full Name *</label>
                                    <input type="text" class="form-control" name="full_name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Department</label>
                                    <select class="form-control" name="department">
                                        <option value="">Select Department</option>
                                        <option value="Cutting">Cutting</option>
                                        <option value="Sewing">Sewing</option>
                                        <option value="Finishing">Finishing</option>
                                        <option value="Quality Control">Quality Control</option>
                                        <option value="Packaging">Packaging</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Position</label>
                                    <input type="text" class="form-control" name="position">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Phone</label>
                                    <input type="tel" class="form-control" name="phone">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hire Date</label>
                                    <input type="date" class="form-control" name="hire_date">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Hourly Rate (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="hourly_rate">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Piece Rate (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="piece_rate">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="address" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Worker</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/workers.js"></script>
</body>
</html>
