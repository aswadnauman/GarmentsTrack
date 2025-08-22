// Dashboard JavaScript functionality
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    
    // Refresh dashboard data every 5 minutes
    setInterval(loadDashboardData, 300000);
});

function loadDashboardData() {
    // Load dashboard statistics
    fetch('api/dashboard_stats.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateDashboardCards(data.stats);
            }
        })
        .catch(error => {
            console.error('Error loading dashboard data:', error);
        });
    
    // Load recent orders
    fetch('api/recent_orders.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateRecentOrders(data.orders);
            }
        })
        .catch(error => {
            console.error('Error loading recent orders:', error);
        });
    
    // Load worker performance
    fetch('api/worker_performance.php')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                updateWorkerPerformance(data.performance);
            }
        })
        .catch(error => {
            console.error('Error loading worker performance:', error);
        });
}

function updateDashboardCards(stats) {
    document.getElementById('active-workers').textContent = stats.active_workers || 0;
    document.getElementById('monthly-revenue').textContent = '₹' + (stats.monthly_revenue || 0).toLocaleString();
    document.getElementById('pending-orders').textContent = stats.pending_orders || 0;
    document.getElementById('low-stock').textContent = stats.low_stock_items || 0;
}

function updateRecentOrders(orders) {
    const container = document.getElementById('recent-orders');
    
    if (!orders || orders.length === 0) {
        container.innerHTML = '<p class="text-muted">No recent orders</p>';
        return;
    }
    
    let html = '<div class="list-group list-group-flush">';
    orders.forEach(order => {
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">Order #${order.id}</h6>
                    <p class="mb-1">${order.customer_name}</p>
                    <small class="text-muted">${order.created_at}</small>
                </div>
                <span class="badge bg-primary rounded-pill">₹${order.total_amount}</span>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}

function updateWorkerPerformance(performance) {
    const container = document.getElementById('worker-performance');
    
    if (!performance || performance.length === 0) {
        container.innerHTML = '<p class="text-muted">No performance data</p>';
        return;
    }
    
    let html = '<div class="list-group list-group-flush">';
    performance.forEach(worker => {
        const efficiency = Math.round(worker.efficiency || 0);
        const badgeClass = efficiency >= 90 ? 'bg-success' : efficiency >= 70 ? 'bg-warning' : 'bg-danger';
        
        html += `
            <div class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-1">${worker.name}</h6>
                    <small class="text-muted">${worker.department}</small>
                </div>
                <span class="badge ${badgeClass} rounded-pill">${efficiency}%</span>
            </div>
        `;
    });
    html += '</div>';
    
    container.innerHTML = html;
}
