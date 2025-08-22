<?php
// Script to load demo data into the database
require_once '../config/database.php';

try {
    echo "Loading demo data...\n";
    
    // Read and execute the demo data SQL
    $sql = file_get_contents('demo_data.sql');
    
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
                // Skip errors for duplicate entries or other non-critical issues
                if (strpos($e->getMessage(), 'Duplicate entry') === false) {
                    echo "Warning: " . $e->getMessage() . "\n";
                }
            }
        }
    }
    
    echo "Demo data loaded successfully! Executed $count statements.\n";
    echo "\nDemo data includes:\n";
    echo "- 9 customers\n";
    echo "- 10 products with variants\n";
    echo "- 8 workers\n";
    echo "- 12 orders (as requested)\n";
    echo "- Production orders and tasks\n";
    echo "- Time entries and payroll records\n";
    echo "- Sample designs\n";
    echo "- Inventory movements\n";
    
} catch (Exception $e) {
    echo "Error loading demo data: " . $e->getMessage() . "\n";
}
?>
