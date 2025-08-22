<nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="index.php">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="workers/">
                    <i class="fas fa-users"></i> Workers
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="orders/">
                    <i class="fas fa-shopping-cart"></i> Orders
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="inventory/">
                    <i class="fas fa-boxes"></i> Inventory
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="production/">
                    <i class="fas fa-industry"></i> Production
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="samples/">
                    <i class="fas fa-palette"></i> Samples
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="payroll/">
                    <i class="fas fa-money-bill-wave"></i> Payroll
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link" href="reports/">
                    <i class="fas fa-chart-bar"></i> Reports
                </a>
            </li>
            
            <?php if (hasAnyRole(['admin', 'manager'])): ?>
            <li class="nav-item">
                <a class="nav-link" href="settings/">
                    <i class="fas fa-cog"></i> Settings
                </a>
            </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
