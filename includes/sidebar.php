<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <?php
            $current_page = basename($_SERVER['REQUEST_URI'], '.php');
            $base_path = dirname($_SERVER['SCRIPT_NAME']);
            if ($base_path === '/') $base_path = '';
            ?>
            
            <li class="nav-item">
                <a class="nav-link <?php echo ($current_page === 'index' || $current_page === '') ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/workers') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/workers/">
                    <i class="fas fa-users"></i> Workers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/orders') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/orders/">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/inventory') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/inventory/">
                    <i class="fas fa-boxes"></i> Inventory
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/production') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/production/">
                    <i class="fas fa-industry"></i> Production
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/samples') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/samples/">
                    <i class="fas fa-palette"></i> Samples
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/payroll') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/payroll/">
                    <i class="fas fa-money-bill-wave"></i> Payroll
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/reports') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/reports/">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            
            <?php if (hasAnyRole(['admin', 'manager'])): ?>
            <li class="nav-item">
                <a class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/settings') !== false) ? 'active' : ''; ?>" href="<?php echo $base_path; ?>/settings/">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
