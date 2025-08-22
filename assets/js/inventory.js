// Inventory management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const addProductForm = document.getElementById('addProductForm');
    const stockAdjustmentForm = document.getElementById('stockAdjustmentForm');
    
    if (addProductForm) {
        addProductForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addProduct();
        });
    }
    
    if (stockAdjustmentForm) {
        stockAdjustmentForm.addEventListener('submit', function(e) {
            e.preventDefault();
            adjustStock();
        });
    }
});

function addProduct() {
    const form = document.getElementById('addProductForm');
    const formData = new FormData(form);
    
    fetch('add_product.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Product added successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the product');
    });
}

function adjustStock() {
    const form = document.getElementById('stockAdjustmentForm');
    const formData = new FormData(form);
    
    fetch('adjust_stock.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Stock adjusted successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adjusting stock');
    });
}
