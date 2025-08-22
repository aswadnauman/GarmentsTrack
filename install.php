<?php
// Installation script for GarmentsTrack ERP
$step = $_GET['step'] ?? 1;
$error = '';
$success = '';

if ($_POST) {
    if ($step == 1) {
        // Database connection test
        $host = $_POST['host'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $dbname = $_POST['dbname'];
        
        try {
            $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Create database if it doesn't exist
            $pdo->exec("CREATE DATABASE IF NOT EXISTS `$dbname`");
            $pdo->exec("USE `$dbname`");
            
            // Save database config
            $config = "<?php\n";
            $config .= "// Database configuration\n";
            $config .= "\$host = '$host';\n";
            $config .= "\$dbname = '$dbname';\n";
            $config .= "\$username = '$username';\n";
            $config .= "\$password = '$password';\n\n";
            $config .= "try {\n";
            $config .= "    \$pdo = new PDO(\"mysql:host=\$host;dbname=\$dbname;charset=utf8mb4\", \$username, \$password);\n";
            $config .= "    \$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);\n";
            $config .= "    \$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);\n";
            $config .= "} catch (PDOException \$e) {\n";
            $config .= "    die(\"Database connection failed: \" . \$e->getMessage());\n";
            $config .= "}\n";
            $config .= "?>";
            
            file_put_contents('config/database.php', $config);
            
            header('Location: install.php?step=2');
            exit();
            
        } catch (PDOException $e) {
            $error = "Database connection failed: " . $e->getMessage();
        }
    } elseif ($step == 2) {
        // Run database schema
        try {
            require_once 'config/database.php';
            
            $schema = file_get_contents('database/schema.sql');
            $statements = explode(';', $schema);
            
            foreach ($statements as $statement) {
                $statement = trim($statement);
                if (!empty($statement)) {
                    $pdo->exec($statement);
                }
            }
            
            header('Location: install.php?step=3');
            exit();
            
        } catch (PDOException $e) {
            $error = "Database setup failed: " . $e->getMessage();
        }
    } elseif ($step == 3) {
        // Create admin user
        $username = $_POST['admin_username'];
        $password = $_POST['admin_password'];
        $full_name = $_POST['admin_name'];
        $email = $_POST['admin_email'];
        
        if (empty($username) || empty($password) || empty($full_name)) {
            $error = "All fields are required";
        } else {
            try {
                require_once 'config/database.php';
                
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                // Update the default admin user
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET username = ?, password = ?, full_name = ?, email = ?
                    WHERE role = 'admin' 
                    LIMIT 1
                ");
                $stmt->execute([$username, $hashed_password, $full_name, $email]);
                
                header('Location: install.php?step=4');
                exit();
                
            } catch (PDOException $e) {
                $error = "Failed to create admin user: " . $e->getMessage();
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install GarmentsTrack ERP</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow mt-5">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-industry"></i> GarmentsTrack ERP Installation</h4>
                    </div>
                    <div class="card-body">
                        <!-- Progress bar -->
                        <div class="progress mb-4">
                            <div class="progress-bar" style="width: <?php echo ($step / 4) * 100; ?>%"></div>
                        </div>
                        
                        <?php if ($error): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($success): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>
                        
                        <?php if ($step == 1): ?>
                            <h5>Step 1: Database Configuration</h5>
                            <p>Please provide your MySQL database connection details:</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Database Host</label>
                                    <input type="text" class="form-control" name="host" value="localhost" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Database Name</label>
                                    <input type="text" class="form-control" name="dbname" value="garments_erp" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Database Username</label>
                                    <input type="text" class="form-control" name="username" value="root" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Database Password</label>
                                    <input type="password" class="form-control" name="password">
                                </div>
                                <button type="submit" class="btn btn-primary">Test Connection & Continue</button>
                            </form>
                            
                        <?php elseif ($step == 2): ?>
                            <h5>Step 2: Database Setup</h5>
                            <p>Database connection successful! Now we'll create the required tables.</p>
                            
                            <form method="POST">
                                <button type="submit" class="btn btn-primary">Create Database Tables</button>
                            </form>
                            
                        <?php elseif ($step == 3): ?>
                            <h5>Step 3: Admin User Setup</h5>
                            <p>Create your administrator account:</p>
                            
                            <form method="POST">
                                <div class="mb-3">
                                    <label class="form-label">Admin Username</label>
                                    <input type="text" class="form-control" name="admin_username" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Admin Password</label>
                                    <input type="password" class="form-control" name="admin_password" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Full Name</label>
                                    <input type="text" class="form-control" name="admin_name" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" class="form-control" name="admin_email">
                                </div>
                                <button type="submit" class="btn btn-primary">Create Admin User</button>
                            </form>
                            
                        <?php elseif ($step == 4): ?>
                            <h5>Step 4: Installation Complete!</h5>
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle"></i> GarmentsTrack ERP has been successfully installed!
                            </div>
                            
                            <h6>What's Next?</h6>
                            <ul>
                                <li>Delete or rename the <code>install.php</code> file for security</li>
                                <li>Login with your admin credentials</li>
                                <li>Start adding workers, customers, and products</li>
                                <li>Configure your company settings</li>
                            </ul>
                            
                            <a href="login.php" class="btn btn-success">
                                <i class="fas fa-sign-in-alt"></i> Go to Login
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
