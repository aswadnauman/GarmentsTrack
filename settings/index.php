<?php
session_start();
require_once '../config/database.php';
require_once '../includes/auth.php';

requireLogin();

// Only allow admin and manager roles to access settings
if (!hasAnyRole(['admin', 'manager'])) {
    header('Location: ../index.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - GarmentsTrack ERP</title>
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
                    <h1 class="h2">System Settings</h1>
                </div>
                
                <div class="row">
                    <!-- Company Settings -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h6 class="m-0"><i class="fas fa-building"></i> Company Settings</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="editSetting('company-info')">
                                        <i class="fas fa-info-circle text-primary"></i> Company Information
                                        <small class="text-muted d-block">Name, address, contact details</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="editSetting('tax-settings')">
                                        <i class="fas fa-percentage text-info"></i> Tax & GST Settings
                                        <small class="text-muted d-block">Tax rates and GST configuration</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="editSetting('currency')">
                                        <i class="fas fa-rupee-sign text-success"></i> Currency Settings
                                        <small class="text-muted d-block">Default currency and formatting</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- User Management -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-success text-white">
                                <h6 class="m-0"><i class="fas fa-users-cog"></i> User Management</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="manageSetting('users')">
                                        <i class="fas fa-user-plus text-primary"></i> Manage Users
                                        <small class="text-muted d-block">Add, edit, and deactivate users</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="manageSetting('roles')">
                                        <i class="fas fa-user-shield text-info"></i> Role Permissions
                                        <small class="text-muted d-block">Configure role-based access</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="manageSetting('security')">
                                        <i class="fas fa-lock text-warning"></i> Security Settings
                                        <small class="text-muted d-block">Password policies and security</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- System Configuration -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-warning text-white">
                                <h6 class="m-0"><i class="fas fa-cogs"></i> System Configuration</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('notifications')">
                                        <i class="fas fa-bell text-primary"></i> Notifications
                                        <small class="text-muted d-block">Email and system notifications</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('backup')">
                                        <i class="fas fa-database text-info"></i> Backup Settings
                                        <small class="text-muted d-block">Automated backup configuration</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('integrations')">
                                        <i class="fas fa-plug text-success"></i> Integrations
                                        <small class="text-muted d-block">Third-party service connections</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Business Rules -->
                    <div class="col-lg-6 mb-4">
                        <div class="card shadow">
                            <div class="card-header bg-info text-white">
                                <h6 class="m-0"><i class="fas fa-business-time"></i> Business Rules</h6>
                            </div>
                            <div class="card-body">
                                <div class="list-group list-group-flush">
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('workflows')">
                                        <i class="fas fa-project-diagram text-primary"></i> Workflow Settings
                                        <small class="text-muted d-block">Order and production workflows</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('pricing')">
                                        <i class="fas fa-tags text-success"></i> Pricing Rules
                                        <small class="text-muted d-block">Automatic pricing and discounts</small>
                                    </a>
                                    <a href="#" class="list-group-item list-group-item-action" onclick="configureSetting('inventory-rules')">
                                        <i class="fas fa-boxes text-warning"></i> Inventory Rules
                                        <small class="text-muted d-block">Reorder points and stock alerts</small>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- System Information -->
                <div class="card shadow">
                    <div class="card-header">
                        <h6 class="m-0 font-weight-bold text-primary">
                            <i class="fas fa-info"></i> System Information
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>System Version:</strong></td>
                                        <td>GarmentsTrack ERP v1.0</td>
                                    </tr>
                                    <tr>
                                        <td><strong>PHP Version:</strong></td>
                                        <td><?php echo PHP_VERSION; ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Database:</strong></td>
                                        <td>MySQL <?php echo $pdo->query('SELECT VERSION()')->fetchColumn(); ?></td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table table-borderless">
                                    <tr>
                                        <td><strong>Installation Date:</strong></td>
                                        <td><?php echo date('M d, Y'); ?></td>
                                    </tr>
                                    <tr>
                                        <td><strong>Last Backup:</strong></td>
                                        <td><span class="text-muted">Not configured</span></td>
                                    </tr>
                                    <tr>
                                        <td><strong>System Status:</strong></td>
                                        <td><span class="badge bg-success">Operational</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editSetting(settingType) {
            const settingNames = {
                'company-info': 'Company Information',
                'tax-settings': 'Tax & GST Settings',
                'currency': 'Currency Settings'
            };
            
            alert(`Editing ${settingNames[settingType]}...\n\nThis will provide forms to configure:\n• Company details\n• Contact information\n• Tax rates and rules\n• Currency preferences`);
        }
        
        function manageSetting(settingType) {
            const settingNames = {
                'users': 'User Management',
                'roles': 'Role Permissions',
                'security': 'Security Settings'
            };
            
            alert(`Managing ${settingNames[settingType]}...\n\nThis will include:\n• User creation and editing\n• Role assignment\n• Permission configuration\n• Security policies`);
        }
        
        function configureSetting(settingType) {
            const settingNames = {
                'notifications': 'Notification Settings',
                'backup': 'Backup Configuration',
                'integrations': 'System Integrations',
                'workflows': 'Workflow Configuration',
                'pricing': 'Pricing Rules',
                'inventory-rules': 'Inventory Rules'
            };
            
            alert(`Configuring ${settingNames[settingType]}...\n\nThis will provide options for:\n• System configuration\n• Business rule setup\n• Integration management\n• Automated processes`);
        }
    </script>
</body>
</html>
