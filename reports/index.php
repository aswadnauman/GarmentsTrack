<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - GarmentsTrack ERP</title>
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
                    <h1 class="h2">Reports & Analytics</h1>
                </div>
                
                <div class="row">
                    <!-- Sales Reports -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0"><i class="fas fa-chart-line"></i> Sales Reports</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('sales-summary')">
                                        <i class="fas fa-chart-bar text-primary"></i> Sales Summary
                                        <small class="text-muted d-block">Monthly and yearly sales overview</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('customer-analysis')">
                                        <i class="fas fa-users text-info"></i> Customer Analysis
                                        <small class="text-muted d-block">Top customers and buying patterns</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('product-performance')">
                                        <i class="fas fa-box text-success"></i> Product Performance
                                        <small class="text-muted d-block">Best selling products and trends</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Production Reports -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0"><i class="fas fa-industry"></i> Production Reports</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('production-efficiency')">
                                        <i class="fas fa-tachometer-alt text-primary"></i> Production Efficiency
                                        <small class="text-muted d-block">Output rates and efficiency metrics</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('worker-performance')">
                                        <i class="fas fa-user-clock text-info"></i> Worker Performance
                                        <small class="text-muted d-block">Individual and team productivity</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('quality-metrics')">
                                        <i class="fas fa-award text-warning"></i> Quality Metrics
                                        <small class="text-muted d-block">Quality control and defect rates</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Inventory Reports -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-white">
                                <h6 class="m-0"><i class="fas fa-boxes"></i> Inventory Reports</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('stock-levels')">
                                        <i class="fas fa-layer-group text-primary"></i> Stock Levels
                                        <small class="text-muted d-block">Current inventory and reorder points</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('inventory-valuation')">
                                        <i class="fas fa-rupee-sign text-success"></i> Inventory Valuation
                                        <small class="text-muted d-block">Stock value and cost analysis</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('movement-history')">
                                        <i class="fas fa-exchange-alt text-info"></i> Movement History
                                        <small class="text-muted d-block">Stock movements and transactions</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Financial Reports -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0"><i class="fas fa-calculator"></i> Financial Reports</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('profit-loss')">
                                        <i class="fas fa-chart-pie text-primary"></i> Profit & Loss
                                        <small class="text-muted d-block">Revenue, costs, and profitability</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('payroll-summary')">
                                        <i class="fas fa-money-bill-wave text-success"></i> Payroll Summary
                                        <small class="text-muted d-block">Labor costs and payroll analysis</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="generateReport('gst-reports')">
                                        <i class="fas fa-file-invoice text-warning"></i> GST Reports
                                        <small class="text-muted d-block">Tax calculations and compliance</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Custom Report Builder -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-tools"></i> Custom Report Builder
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-8">
                                <p class="text-muted">Create custom reports with specific date ranges, filters, and data points tailored to your business needs.</p>
                            </div>
                            <div class="col-md-4 text-end">
                                <button class="btn btn-primary" onclick="openReportBuilder()">
                                    <i class="fas fa-plus"></i> Build Custom Report
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function generateReport(reportType) {
            const reportNames = {
                'sales-summary': 'Sales Summary Report',
                'customer-analysis': 'Customer Analysis Report',
                'product-performance': 'Product Performance Report',
                'production-efficiency': 'Production Efficiency Report',
                'worker-performance': 'Worker Performance Report',
                'quality-metrics': 'Quality Metrics Report',
                'stock-levels': 'Stock Levels Report',
                'inventory-valuation': 'Inventory Valuation Report',
                'movement-history': 'Movement History Report',
                'profit-loss': 'Profit & Loss Report',
                'payroll-summary': 'Payroll Summary Report',
                'gst-reports': 'GST Reports'
            };
            
            alert(`Generating ${reportNames[reportType]}...\n\nThis will include:\n• Date range selection\n• Filter options\n• Export to PDF/Excel\n• Email delivery\n• Scheduled reports`);
        }
        
        function openReportBuilder() {
            alert('Custom Report Builder will provide:\n\n• Drag-and-drop interface\n• Multiple data sources\n• Custom filters and grouping\n• Chart and graph options\n• Save and schedule reports\n• Export in multiple formats');
        }
    </script>
</body>
</html>
