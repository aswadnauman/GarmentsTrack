// Workers management JavaScript
document.addEventListener('DOMContentLoaded', function() {
    const addWorkerForm = document.getElementById('addWorkerForm');
    
    if (addWorkerForm) {
        addWorkerForm.addEventListener('submit', function(e) {
            e.preventDefault();
            addWorker();
        });
    }
});

function addWorker() {
    const form = document.getElementById('addWorkerForm');
    const formData = new FormData(form);
    
    fetch('add_worker.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Worker added successfully!');
            location.reload();
        } else {
            alert('Error: ' + (data.error || 'Unknown error occurred'));
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred while adding the worker');
    });
}

function editWorker(id) {
    // TODO: Implement edit worker functionality
    alert('Edit worker functionality will be implemented');
}

function viewWorker(id) {
    // TODO: Implement view worker details
    alert('View worker details functionality will be implemented');
}
