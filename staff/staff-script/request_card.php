<script>
// Function to toggle editable mode for card fields
function toggleEditable(button) {
    const card = button.closest('.card');
    const fields = card.querySelectorAll('.field-display, .field-input');

    fields.forEach(field => {
        if (field.classList.contains('field-display')) {
            field.classList.toggle('d-none'); // Hide or show display span
        } else {
            field.classList.toggle('d-none'); // Show or hide input field
        }
    });

    // Change button text to indicate save or edit mode
    if (button.textContent === 'Edit') {
        button.textContent = 'Save';
    } else {
        button.textContent = 'Edit';
        saveChanges(card); // Call a function to save changes if needed
    }
}

// Optional: Function to handle saving changes, e.g., with an AJAX call
function saveChanges(card) {
    const requestId = card.getAttribute('data-request-id');
    const updatedData = {};

    // Collect the values from input fields
    card.querySelectorAll('.field-input').forEach(input => {
        const key = input.previousElementSibling.textContent.trim();
        updatedData[key] = input.value;
    });

    // Example AJAX call to save changes to the server
    fetch('/save-changes.php', {
        method: 'POST',
        body: JSON.stringify({ requestId, ...updatedData }),
        headers: { 'Content-Type': 'application/json' }
    })
    .then(response => response.json())
    .then(data => {
        // Handle response after saving (e.g., show a success message)
        console.log('Changes saved:', data);
    })
    .catch(error => {
        // Handle any error that occurs during the AJAX request
        console.error('Error saving changes:', error);
    });
}
function sortRequests() {
    const sortBy = document.getElementById('sortBy').value;

    // Send an AJAX request to sort the data
    fetch(`/form.php?sort_by=${encodeURIComponent(sortBy)}`)
        .then(response => response.json())
        .then(data => {
            // Update the page content dynamically, e.g., by updating the request cards
            document.querySelector('.row').innerHTML = data.sortedHtml;
        })
        .catch(error => {
            console.error('Error sorting requests:', error);
        });
}
function updateStatus(requestId, status) {
    // Show confirmation dialog with SweetAlert
    Swal.fire({
        title: 'Are you sure?',
        text: `You want to set the status to ${status}.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, update it!'
    }).then((result) => {
        if (result.isConfirmed) {
            // Perform the status update via fetch only if confirmed
            fetch('form_request.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ id: requestId, status: status })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Updated!', `Status has been updated to ${status}.`, 'success');
                    // Optionally, update the card's display or refresh part of the page
                } else {
                    Swal.fire('Error!', 'Failed to update status.', 'error');
                }
            })
                    Swal.fire('Updated!', `Status has been updated to ${status}.`, 'success')
                        .then(() => {
                            window.location.href = 'form_request.php';
                        });
        }
    });
}

</script>
<!-- JavaScript to toggle card visibility and unread status -->
<script>
function toggleCard(cardId, listItem) {
    const card = document.getElementById(cardId);
    
    // Toggle card visibility
    if (card.style.display === "none" || card.style.display === "") {
        card.style.display = "block"; // Show the card
    } else {
        card.style.display = "none"; // Hide the card
    }

    // Toggle unread status
    listItem.classList.toggle('read'); // Add or remove the 'unread' class
}
</script>

<!-- CSS for unread class -->
<style>
@keyframes moveColor {
    0% { 
        background-color: transparent; 
        background-position: left; /* Start from the left */
    }
    33% { 
        background-color: #b2ebf2; /* Pale blue */
        background-position: left; 
    }
    66% { 
        background-color: #80deea; /* Light blue */
        background-position: left; 
    }
    100% { 
        background-color: #80deea; /* Solid blue */
        background-position: left; 
    }
}

.read {

    background-size: 200% 100%; /* Double the size for the sliding effect */
    animation: moveColor 2s ease forwards; /* Animation for color change */
    /* Optional styling for visibility */

    height: 50px;
    display: inline-block; /* For better sizing */
}
</style>

<!-- JavaScript to Toggle Between View and Edit Modes -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const cards = document.querySelectorAll('.card');

    cards.forEach(card => {
        const actionBtn = card.querySelector('.action-btn');
        const cancelBtn = card.querySelector('.cancel-btn');
        const viewMode = card.querySelector('.view-mode');
        const editMode = card.querySelector('.edit-mode');

        // Show edit mode when "Edit" button is clicked
        actionBtn.addEventListener('click', function () {
            viewMode.classList.add('d-none');
            editMode.classList.remove('d-none');
        });

        // Revert to view mode when "Cancel" button is clicked
        cancelBtn.addEventListener('click', function () {
            editMode.classList.add('d-none');
            viewMode.classList.remove('d-none');
        });
    });
});
</script>