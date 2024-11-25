<?php 
require_once '../core/partials/config.php';

$sql = "SELECT role_name, role_type FROM roles";
$result = $conn->query($sql);

// Initialize the $role variable
$role = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get role from POST request if it exists
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
    }

    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];  // Don't forget to capture username
    $password = $_POST['password'];  // Don't forget to capture password

    if ($role == 'staff') {
        $staffType = $_POST['staff_type'];

        // Check if staff info exists
        $checkInfoQuery = "SELECT * FROM staff_info WHERE first_name = ? AND last_name = ?";
        $checkInfoStmt = $conn->prepare($checkInfoQuery);
        $checkInfoStmt->bind_param("ss", $firstName, $lastName);
        $checkInfoStmt->execute();
        $infoResult = $checkInfoStmt->get_result();

        if ($infoResult->num_rows == 0) {
            // Insert into staff_info if not exists
            $insertStaffInfo = "INSERT INTO staff_info (first_name, middle_name, last_name, email, role) VALUES (?, ?, ?, ?, ?)";
            $insertStaffStmt = $conn->prepare($insertStaffInfo);
            $insertStaffStmt->bind_param("sssss", $firstName, $middleName, $lastName, $email, $role); // Include role in insertion
            if ($insertStaffStmt->execute()) {
                $staffInfoId = $conn->insert_id; // Get the inserted staff_info_id

                // Now insert into staff table (authentication)
                $insertStaffAuth = "INSERT INTO staff (username, password, staff_info_id) VALUES (?, ?, ?)";
                $insertAuthStmt = $conn->prepare($insertStaffAuth);
                $insertAuthStmt->bind_param("ssi", $username, $password, $staffInfoId); // Use staff_info_id
                if ($insertAuthStmt->execute()) {
                    echo "<div class='alert alert-success'>Staff account successfully created</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error creating staff account: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error inserting staff information: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Staff information already exists</div>";
        }
        $checkInfoStmt->close();

    } elseif ($role == 'client') {
        $clientType = $_POST['client_type'];

        // Check if client info exists
        $checkInfoQuery = "SELECT * FROM client_info WHERE first_name = ? AND last_name = ?";
        $checkInfoStmt = $conn->prepare($checkInfoQuery);
        $checkInfoStmt->bind_param("ss", $firstName, $lastName);
        $checkInfoStmt->execute();
        $infoResult = $checkInfoStmt->get_result();

        if ($infoResult->num_rows == 0) {
            // Insert into client_info if not exists
            $insertClientInfo = "INSERT INTO client_info (first_name, middle_name, last_name, email) VALUES (?, ?, ?, ?)";
            $insertClientStmt = $conn->prepare($insertClientInfo);
            $insertClientStmt->bind_param("ssss", $firstName, $middleName, $lastName, $email);
            if ($insertClientStmt->execute()) {
                $clientId = $conn->insert_id; // Get the inserted client_id

                // Now insert into client table (authentication)
                $insertClientAuth = "INSERT INTO client (username, password, client_id) VALUES (?, ?, ?)";
                $insertAuthStmt = $conn->prepare($insertClientAuth);
                $insertAuthStmt->bind_param("ssi", $username, $password, $clientId);
                if ($insertAuthStmt->execute()) {
                    echo "<div class='alert alert-success'>Client account successfully created</div>";
                } else {
                    echo "<div class='alert alert-danger'>Error creating client account: " . $conn->error . "</div>";
                }
            } else {
                echo "<div class='alert alert-danger'>Error inserting client information: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Client information already exists</div>";
        }
        $checkInfoStmt->close();
    }
}
?>



    <style>
        .card {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            background-color: #f9f9f9;
        }

        .form-container {
            display: flex;
            justify-content: space-between;
            gap: 50px; /* Increase the gap between the columns */
        }

        .column {
            flex: 1;
        }

        .column label {
            display: block;
            margin-bottom: 5px;
        }

        .column input, .column select {
            width: 100%;
            padding: 8px;
            margin-bottom: 20px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }

        .role-options {
            display: none;
        }

        input[type="submit"] {
            padding: 10px 15px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Password input wrapper with eye icon */
        .password-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .password-wrapper input[type="password"], 
        .password-wrapper input[type="text"] {
            padding-right: 40px;
            width: 100%;
        }

        .password-wrapper .toggle-password {
            position: absolute;
            right: 10px;
            cursor: pointer;
            top: 35%; /* Center vertically */
            transform: translateY(-50%); /* Adjust for perfect centering */
        }

    </style>

<script>
// Show the role options based on the selected role
function showRoleOptions() {
    var selectedRole = document.getElementById("role").value;
    var staffOptions = document.getElementById("staffOptions");
    var clientOptions = document.getElementById("clientOptions");
    var roleTypeOptions = document.getElementById("dynamicRoleOptions"); // Updated to reflect the correct div ID

    // Hide all role-specific options initially
    staffOptions.style.display = "none";
    clientOptions.style.display = "none";
    roleTypeOptions.style.display = "none";

    // Show the corresponding options based on the selected role
    if (selectedRole === "staff") {
        staffOptions.style.display = "block";
    } else if (selectedRole === "client") {
        clientOptions.style.display = "block";
    } else if (selectedRole === "add_new_role") {
        openModal();  // Open the modal to add a new role
    } else {
        fetchRoleTypes(selectedRole);  // Fetch role types dynamically for other roles
    }
}

// Fetch role types for dynamically selected roles
function fetchRoleTypes(selectedRole) {
    var roleTypeOptions = document.getElementById("dynamicRoleOptions");
    var roleTypeSelect = document.getElementById("dynamic_type"); // Corrected to match the select ID

    roleTypeOptions.style.display = "none"; // Hide initially

    if (selectedRole === "" || selectedRole === "add_new_role") {
        return; // No action if no valid role is selected or if user is adding a new role
    }

    // Send AJAX request to fetch corresponding role types
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_role.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function() {
        if (xhr.status === 200) {
            roleTypeSelect.innerHTML = xhr.responseText; // Populate the role types
            roleTypeOptions.style.display = "block"; // Show the role type dropdown
        }
    };

    xhr.send("role_name=" + selectedRole);
}

// Handle role selection logic
function handleRoleSelection() {
    var selectedRole = document.getElementById("role").value;
    if (selectedRole === "add_new_role") {
        openModal(); // If user selects "Add New Role", open the modal
    }
}

// Function to open the modal for adding a new role
function openModal() {
    document.getElementById("myModal").style.display = "block";
}

// Function to close the modal
function closeModal() {
    document.getElementById("myModal").style.display = "none";
}

// Toggle password visibility with the eye icon
function togglePasswordVisibility() {
    var passwordField = document.getElementById("password");
    var icon = document.getElementById("togglePasswordIcon");

    if (passwordField.type === "password") {
        passwordField.type = "text";
        icon.classList.remove("bx-show");
        icon.classList.add("bx-hide");
    } else {
        passwordField.type = "password";
        icon.classList.remove("bx-hide");
        icon.classList.add("bx-show");
    }
}

// Handle form submission after adding a new role
document.getElementById("newRoleForm").addEventListener("submit", function(event) {
    event.preventDefault();  // Prevent form from submitting normally

    var newRole = document.getElementById("newRole").value;
    var newType = document.getElementById("newType").value;

    // Send an AJAX request to add the new role to the database
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "add_role.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onload = function() {
        if (xhr.status === 200) {
            // After adding, close the modal and add the new role to the dropdown
            closeModal();
            addNewRoleToDropdown(newRole, newType);
        }
    };
    xhr.send("newRole=" + newRole + "&newType=" + newType);
});

// Add the new role and type to the dropdown and show the new options
function addNewRoleToDropdown(newRole, newType) {
    var roleSelect = document.getElementById("role");
    var newOption = document.createElement("option");
    newOption.value = newRole.toLowerCase();
    newOption.text = newRole;
    roleSelect.appendChild(newOption);

    // Handle showing the new type options based on role
    if (newRole === "staff") {
        document.getElementById("staff_type").innerHTML += '<option value="' + newType.toLowerCase() + '">' + newType + '</option>';
    } else if (newRole === "client") {
        document.getElementById("client_type").innerHTML += '<option value="' + newType.toLowerCase() + '">' + newType + '</option>';
    } else {
        // Add dynamic options for any other newly added roles
        var roleTypeOptions = document.getElementById("dynamicRoleOptions");
        var roleTypeSelect = document.getElementById("dynamic_type"); // Corrected to match the select ID
        roleTypeSelect.innerHTML += '<option value="' + newType.toLowerCase() + '">' + newType + '</option>';
        roleTypeOptions.style.display = "block";  // Show the role type dropdown
    }
}
</script>
