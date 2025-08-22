<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

// Get inventory data with product information
$stmt = $pdo->query("
    SELECT i.*, p.name as product_name, p.sku, c.name as category_name,
           pv.variant_name, pv.variant_value, p.min_stock_level, p.cost_price
    FROM inventory i
    JOIN products p ON i.product_id = p.id
    LEFT JOIN categories c ON p.category_id = c.id
    LEFT JOIN product_variants pv ON i.variant_id = pv.id
    WHERE p.active = 1
    ORDER BY p.name, pv.variant_name
");
$inventory = $stmt->fetchAll();

// Get products for adding inventory
$stmt = $pdo->query("SELECT id, name, sku FROM products WHERE active = 1 ORDER BY name");
$products = $stmt->fetchAll();

// Calculate summary statistics
$total_products = count($inventory);
$low_stock_count = 0;
$total_value = 0;

foreach ($inventory as $item) {
    if ($item['quantity_available'] <= $item['min_stock_level']) {
        $low_stock_count++;
    }
    $total_value += $item['quantity_on_hand'] * $item['cost_price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory - GarmentsTrack ERP</title>
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
                    <h1 class="h2">Inventory Management</h1>
                    <div class="btn-toolbar mb-2 mb-md-0">
                        <button type="button" class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addProductModal">
                            <i class="fas fa-plus"></i> Add Product
                        </button>
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#stockAdjustmentModal">
                            <i class="fas fa-edit"></i> Stock Adjustment
                        </button>
                    </div>
                </div>
                
                <!-- Inventory Summary Cards -->
                <div class="row mb-4">
                    <div class="col-xl-3 col-md-6 mb-4">
                        <div class="card border-left-primary shadow h-100 py-2">
                            <div class="card-body">
                                <div class="row no-gutters align-items-center">
                                    <div class="col mr-2">
                                        <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                            Total Products</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_products; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-boxes fa-2x text-gray-300"></i>
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
                                            Low Stock Items</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $low_stock_count; ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
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
                                            Total Stock Value</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">₹<?php echo number_format($total_value, 0); ?></div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-rupee-sign fa-2x text-gray-300"></i>
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
                                            Locations</div>
                                        <div class="h5 mb-0 font-weight-bold text-gray-800">1</div>
                                    </div>
                                    <div class="col-auto">
                                        <i class="fas fa-warehouse fa-2x text-gray-300"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card shadow">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>SKU</th>
                                        <th>Product Name</th>
                                        <th>Category</th>
                                        <th>Variant</th>
                                        <th>On Hand</th>
                                        <th>Reserved</th>
                                        <th>Available</th>
                                        <th>Location</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($inventory)): ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">
                                                No inventory items found. <a href="#" data-bs-toggle="modal" data-bs-target="#addProductModal">Add your first product</a>
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php foreach ($inventory as $item): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($item['sku']); ?></td>
                                                <td><?php echo htmlspecialchars($item['product_name']); ?></td>
                                                <td><?php echo htmlspecialchars($item['category_name'] ?: 'N/A'); ?></td>
                                                <td>
                                                    <?php if ($item['variant_name']): ?>
                                                        <?php echo htmlspecialchars($item['variant_name'] . ': ' . $item['variant_value']); ?>
                                                    <?php else: ?>
                                                        <span class="text-muted">Standard</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo $item['quantity_on_hand']; ?></td>
                                                <td><?php echo $item['quantity_reserved']; ?></td>
                                                <td>
                                                    <?php 
                                                    $available = $item['quantity_available'];
                                                    $min_level = $item['min_stock_level'];
                                                    if ($available <= 0) {
                                                        $class = 'text-danger';
                                                        $icon = 'fas fa-exclamation-triangle';
                                                    } elseif ($available <= $min_level) {
                                                        $class = 'text-warning';
                                                        $icon = 'fas fa-exclamation-circle';
                                                    } else {
                                                        $class = 'text-success';
                                                        $icon = 'fas fa-check-circle';
                                                    }
                                                    ?>
                                                    <span class="<?php echo $class; ?>">
                                                        <i class="<?php echo $icon; ?>"></i> <?php echo $available; ?>
                                                    </span>
                                                </td>
                                                <td><?php echo htmlspecialchars($item['location']); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-outline-primary" onclick="adjustStock(<?php echo $item['id']; ?>)">
                                                        <i class="fas fa-edit"></i>
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
    
    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addProductForm">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">SKU *</label>
                                    <input type="text" class="form-control" name="sku" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Product Name *</label>
                                    <input type="text" class="form-control" name="name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Category</label>
                                    <select class="form-control" name="category_id">
                                        <option value="">Select Category</option>
                                        <option value="1">Shirts</option>
                                        <option value="2">Pants</option>
                                        <option value="3">Dresses</option>
                                        <option value="4">Accessories</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Initial Quantity</label>
                                    <input type="number" class="form-control" name="initial_quantity" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Cost Price (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="cost_price">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Selling Price (₹)</label>
                                    <input type="number" step="0.01" class="form-control" name="selling_price">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Min Stock Level</label>
                                    <input type="number" class="form-control" name="min_stock_level" min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Max Stock Level</label>
                                    <input type="number" class="form-control" name="max_stock_level" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="2"></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="addProductForm" class="btn btn-primary">Add Product</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Stock Adjustment Modal -->
    <div class="modal fade" id="stockAdjustmentModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Stock Adjustment</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="stockAdjustmentForm">
                        <div class="mb-3">
                            <label class="form-label">Select Product *</label>
                            <select class="form-control" name="inventory_id" required>
                                <option value="">Choose product to adjust</option>
                                <?php foreach ($inventory as $item): ?>
                                    <option value="<?php echo $item['id']; ?>">
                                        <?php echo htmlspecialchars($item['product_name']); ?>
                                        <?php if ($item['variant_name']): ?>
                                            (<?php echo htmlspecialchars($item['variant_name'] . ': ' . $item['variant_value']); ?>)
                                        <?php endif; ?>
                                        - Current: <?php echo $item['quantity_on_hand']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Adjustment Type *</label>
                                    <select class="form-control" name="adjustment_type" required>
                                        <option value="">Select Type</option>
                                        <option value="increase">Increase Stock</option>
                                        <option value="decrease">Decrease Stock</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">Quantity *</label>
                                    <input type="number" class="form-control" name="quantity" min="1" required>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Reason</label>
                            <textarea class="form-control" name="reason" rows="2" placeholder="Reason for adjustment..."></textarea>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="stockAdjustmentForm" class="btn btn-success">Apply Adjustment</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/inventory.js"></script>
    <script>
        function adjustStock(id) {
            // Pre-select the inventory item in the modal
            document.querySelector('select[name="inventory_id"]').value = id;
            // Show the modal
            new bootstrap.Modal(document.getElementById('stockAdjustmentModal')).show();
        }
    </script>
</body>
</html>
