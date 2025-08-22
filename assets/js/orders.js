// Orders management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const addOrderForm = document.getElementById('addOrderForm');
    
    if (addOrderForm) {
        addOrderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addOrder();
        });
    }
});

function addOrder() {
    const form = document.getElementById('addOrderForm');
    const formData = new FormData(form);
    
    fetch('add_order.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order created successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while creating the order');
    });
}

function viewOrder(id) {
    // TODO: Implement view order details
    window.location.href = `view_order.php?id=${id}`;
}

function editOrder(id) {
    // TODO: Implement edit order functionality
    alert('Edit order functionality will be implemented');
}
