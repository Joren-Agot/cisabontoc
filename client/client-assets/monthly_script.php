
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusDetails = <?php echo json_encode($statusDetails); ?>;

    Object.keys(statusDetails).forEach(rowId => {
        const rowStatuses = statusDetails[rowId];
        Object.keys(rowStatuses).forEach(status => {
            const cell = document.querySelector(`td[data-row='${rowId}'][data-cell='${status}']`);
            if (cell) {
                let color;
                switch (status) {
                    case 'ok':
                        color = '#8BC34A';
                        break;
                    case 'repair':
                        color = '#FF5722';
                        break;
                    case 'na':
                        color = '#607D8B';
                        break;
                }
                if (color) {
                    const dropdownItem = cell.querySelector(`.dropdown-item.select-color[data-color='${color}']`);
                    if (dropdownItem) {
                        dropdownItem.click();
                    }
                }
            }
        });
    });
});
</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Set current date into the date input field
        const today = new Date();
        const date = today.getFullYear() + '-' + ('0' + (today.getMonth() + 1)).slice(-2) + '-' + ('0' + today.getDate()).slice(-2);
        document.getElementById('date_input').value = date; // Corrected id here
    });
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusCells = document.querySelectorAll('tbody td[data-status]');
    const inputDetailsModal = document.getElementById('inputDetailsModal');
    const inputDetailsTextArea = document.getElementById('inputDetailsTextArea');
    const saveInputDetailsBtn = document.getElementById('saveInputDetailsBtn');
    const toast = document.getElementById('toast');
    let selectedCell = null; // To store the currently selected cell

    statusCells.forEach(cell => {
        const dropdownToggle = cell.querySelector('.dropdown-toggle');
        const dropdownMenu = cell.querySelector('.dropdown-menu');
        const selectColorItems = dropdownMenu.querySelectorAll('.select-color');
        const inputDetailsItem = dropdownMenu.querySelector('.input-details');
        const removeColorItem = dropdownMenu.querySelector('.remove-color');

        dropdownToggle.addEventListener('click', function() {
            // Toggle dropdown visibility
            dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';

            // Close other dropdowns
            closeOtherDropdowns(cell);
        });

        // Handle select color options
        selectColorItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const selectedColor = item.getAttribute('data-color');
                
                // Remove existing color classes from all cells in the same row
                const rowCells = cell.parentNode.querySelectorAll('td');
                rowCells.forEach(c => c.style.backgroundColor = '');

                // Add selected color to the clicked cell
                cell.style.backgroundColor = selectedColor;

                // Enable 📄 Details if a color is selected
                inputDetailsItem.classList.remove('disabled');

                // Store the selected cell for use in modal
                selectedCell = cell;

                // Automatically save the selection and details (if any)
                saveSelection(cell);

                // Close dropdown after selection
                dropdownMenu.style.display = 'none';
            });
        });

        // Handle 📄 Details option
        inputDetailsItem.addEventListener('click', function(e) {
            e.preventDefault();
            // Check if a color is selected
            if (!cell.style.backgroundColor) {
                showToast('Please select a color first');
                return;
            }

            // Retrieve existing details if available
            const existingDetails = cell.getAttribute('data-details');
            if (existingDetails) {
                inputDetailsTextArea.value = existingDetails;
            } else {
                inputDetailsTextArea.value = ''; // Clear textarea if no existing details
            }

            // Position modal to the center of the viewport
            inputDetailsModal.style.display = 'block';
            selectedCell = cell; // Update selectedCell to the current cell
        });

        // Handle save 📄 Details button
        saveInputDetailsBtn.addEventListener('click', function() {
            // Get the 📄 Details from the textarea
            const inputDetails = inputDetailsTextArea.value.trim();

            if (inputDetails) {
                // Store 📄 Details in a data attribute of the selected cell
                selectedCell.setAttribute('data-details', inputDetails);

                // Automatically save the details
                saveSelection(selectedCell);

                // Close the modal
                inputDetailsModal.style.display = 'none';

                // Clear the textarea
                inputDetailsTextArea.value = '';
            } else {
                showToast('Please enter some details.');
            }
        });

        // Handle ❌ Remove option
        removeColorItem.addEventListener('click', function(e) {
            e.preventDefault();
            // ❌ Remove from the cell
            cell.style.backgroundColor = '';

            // Remove data-details attribute if exists
            cell.removeAttribute('data-details');

            // Disable 📄 Details when removing color
            inputDetailsItem.classList.add('disabled');

            // Automatically save the selection (remove details)
            saveSelection(cell);

            dropdownMenu.style.display = 'none';
        });

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(e) {
            if (!cell.contains(e.target)) {
                dropdownMenu.style.display = 'none';
            }
        });
    });

    // Close modal when clicking the close button (X)
    document.querySelector('.close').addEventListener('click', function() {
        inputDetailsModal.style.display = 'none';
    });

    // Close modal when clicking outside of the modal
    window.addEventListener('click', function(event) {
        if (event.target === inputDetailsModal) {
            inputDetailsModal.style.display = 'none';
        }
    });

    // Function to show toast message
    function showToast(message) {
        toast.textContent = message;
        toast.classList.add('show-toast');
        setTimeout(function() {
            toast.classList.remove('show-toast');
        }, 3000); // Hide after 3 seconds
    }

    function closeOtherDropdowns(currentCell) {
        const allDropdowns = document.querySelectorAll('.dropdown-menu');
        allDropdowns.forEach(dropdown => {
            if (dropdown !== currentCell.querySelector('.dropdown-menu')) {
                dropdown.style.display = 'none';
            }
        });
    }

    // Function to save selection and details via AJAX
    function saveSelection(cell) {
        const row = cell.closest('tr').id.replace('row', '');
        const cellStatus = cell.getAttribute('data-status');
        const details = cell.getAttribute('data-details') || '';

        const data = {
            row: row,
            cell: cellStatus,
            details: details
        };

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (!data.success) {
                showToast('Error saving data: ' + data.error);
            }
        })
        .catch(error => {
            showToast('Error: ' + error);
        });
    }
});

</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const statusCells = document.querySelectorAll('tbody .status-cell');

        function enableRow(row) {
            row.classList.remove('disabled-row');
            row.classList.add('enabled-row');
            animateRowEnable(row); // Animate row enable
        }

        function animateRowEnable(row) {
            // Apply animation by adding a class
            row.classList.add('enable-animation');
            // Remove animation class after animation completes
            setTimeout(() => {
                row.classList.remove('enable-animation');
            }, 1000); // Adjust duration to match CSS animation duration
        }

        function disableRow(row) {
            row.classList.remove('enabled-row');
            row.classList.add('disabled-row');
        }

        function handleStatusSelection(cell, color) {
            const row = cell.closest('tr');

            // Remove existing selected colors from all cells in the same row
            const cellsInRow = row.querySelectorAll('.status-cell');
            cellsInRow.forEach(c => {
                c.style.backgroundColor = ''; // Clear background color
                c.classList.remove('selected-ok', 'selected-repair', 'selected-na'); // Remove all selected classes
            });

            // Set the selected color and corresponding class to the current cell
            cell.style.backgroundColor = color;
            if (color === '#8BC34A') {
                cell.classList.add('selected-ok');
            } else if (color === '#FF5722') {
                cell.classList.add('selected-repair');
            } else if (color === '#607D8B') {
                cell.classList.add('selected-na');
            }

            // Enable the next row if necessary
            if (checkRowFilled(row)) {
                enableNextRow(row);
            }
        }

        function enableNextRow(currentRow) {
            let nextRow = currentRow.nextElementSibling;
            if (nextRow && nextRow.classList.contains('disabled-row')) {
                enableRow(nextRow);
            }
        }

        function checkRowFilled(row) {
            const statusCellsInRow = row.querySelectorAll('.status-cell');
            let filled = false;
            statusCellsInRow.forEach(cell => {
                if (cell.classList.contains('selected-ok') || cell.classList.contains('selected-repair') || cell.classList.contains('selected-na')) {
                    filled = true;
                }
            });
            return filled;
        }

        function enableRowsFromTo(startRowId, endRowId) {
            for (let i = parseInt(startRowId.slice(3)); i <= parseInt(endRowId.slice(3)); i++) {
                enableRow(document.getElementById('row' + i));
            }
        }

        function disableRowsFromTo(startRowId, endRowId) {
            for (let i = parseInt(startRowId.slice(3)); i <= parseInt(endRowId.slice(3)); i++) {
                disableRow(document.getElementById('row' + i));
            }
        }

        statusCells.forEach(cell => {
            const dropdownItems = cell.querySelectorAll('.dropdown-item.select-color');
            dropdownItems.forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    const color = this.dataset.color;
                    handleStatusSelection(cell, color);
                });
            });
        });

        // Initially enable row1
        enableRow(document.getElementById('row1'));
        // Disable rows 2 to 10 initially
        disableRowsFromTo('row2', 'row10');
    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const inputDetailsModal = document.getElementById('inputDetailsModal');
    const inputDetailsTextArea = document.getElementById('inputDetailsTextArea');
    const saveInputDetailsBtn = document.getElementById('saveInputDetailsBtn');
    let selectedCell = null; // To store the currently selected cell

    // Handle 📄 Details option
    document.querySelectorAll('.input-details').forEach(inputDetailsItem => {
        inputDetailsItem.addEventListener('click', function(e) {
            e.preventDefault();
            const cell = this.closest('.status-cell');
            if (!cell.style.backgroundColor) {
                showToast('Please select a color first');
                return;
            }
            inputDetailsModal.style.display = 'block';
            selectedCell = cell;
        });
    });

    // Handle save 📄 Details button
    saveInputDetailsBtn.addEventListener('click', function() {
        const inputDetails = inputDetailsTextArea.value.trim();
        if (inputDetails) {
            selectedCell.setAttribute('data-details', inputDetails);
            inputDetailsModal.style.display = 'none';
            inputDetailsTextArea.value = '';
        } else {
            showToast('Please enter some details.');
        }
    });

    // Handle close modal button
    document.querySelector('.close').addEventListener('click', function() {
        inputDetailsModal.style.display = 'none';
    });

    // Close modal when clicking outside of the modal
    window.addEventListener('click', function(event) {
        if (event.target === inputDetailsModal) {
            inputDetailsModal.style.display = 'none';
        }
    });

    // Function to show toast message
    function showToast(message) {
        const toast = document.getElementById('toast');
        toast.textContent = message;
        toast.classList.add('show-toast');
        setTimeout(function() {
            toast.classList.remove('show-toast');
        }, 3000); // Hide after 3 seconds
    }
});
</script>
<script>
   // Example JavaScript for handling AJAX request on cell selection
    const selectColorItems = document.querySelectorAll('.dropdown-item.select-color');

    selectColorItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault();

            const rowId = this.getAttribute('data-row');
            const cellId = this.getAttribute('data-cell');

            saveSelection(rowId, cellId);
        });
    });

    function saveSelection(rowId, cellId) {
        const data = {
            row: rowId,
            cell: cellId
        };

        fetch(window.location.href, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Selection saved successfully');
            } else {
                console.error('Failed to save selection', data.error);
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

</script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const statusDetails = <?= json_encode($statusDetails) ?>;
        const colorMap = {
            'ok': '#8BC34A',
            'repair': '#FF5722',
            'na': '#607D8B'
            // Add more statuses and their corresponding colors as needed
        };

        for (let row in statusDetails) {
            for (let status in statusDetails[row]) {
                const cell = document.querySelector(`[data-row='${row}'][data-cell='${status}']`);
                if (cell) {
                    const color = colorMap[status];
                    cell.style.backgroundColor = color;
                }
            }
        }
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the icon and the toast element
        const icon = document.getElementById('icon');
        const toast = document.getElementById('toast');

        // Calculate icon position relative to its container
        const iconRect = icon.getBoundingClientRect();
        const containerRect = icon.parentElement.getBoundingClientRect();

        // Calculate toast position
        const toastTop = iconRect.top - containerRect.top + (iconRect.height / -400) - (toast.clientHeight / -600);
        const toastLeft = iconRect.left - containerRect.left + iconRect.width -380; // Adjust for spacing

        // Set toast position
        toast.style.top = `${toastTop}px`;
        toast.style.left = `${toastLeft}px`;

        // Show toast for 3 seconds on page load
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
        setTimeout(function() {
            bsToast.hide();
        }, 3000);

        // Show toast on icon hover
        icon.addEventListener('mouseover', function() {
            bsToast.show();
        });

        icon.addEventListener('mouseleave', function() {
            bsToast.hide();
        });
    });






</script>
