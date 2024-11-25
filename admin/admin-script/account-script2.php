<?php 
require_once '../core/partials/config.php';

// Fetch role ID along with role name and type
$sql = "SELECT id, role_name, role_type FROM roles"; 
$result = $conn->query($sql);

// Initialize the $role variable
$role = '';
$roleId = 0; // Initialize roleId

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get role from POST request if it exists
    if (isset($_POST['role'])) {
        $role = $_POST['role'];
        
        // Get the role ID for the selected role
        $roleIdQuery = "SELECT id FROM roles WHERE role_name = ?";
        $roleIdStmt = $conn->prepare($roleIdQuery);
        $roleIdStmt->bind_param("s", $role);
        $roleIdStmt->execute();
        $roleIdResult = $roleIdStmt->get_result();

        if ($roleIdResult->num_rows > 0) {
            $row = $roleIdResult->fetch_assoc();
            $roleId = $row['id']; // Assign the role ID
        } else {
            echo "<div class='alert alert-danger'>Role not found!</div>";
            exit; // Exit if role is not found
        }
    }

    $firstName = $_POST['first_name'];
    $middleName = $_POST['middle_name'];
    $lastName = $_POST['last_name'];
    $email = $_POST['email'];
    $username = $_POST['username'];  // Capture username
    $password = $_POST['password'];  // Capture password directly

    // Check if role-specific tables exist
    $roleTable = $role; // Example: 'guard'
    $roleInfoTable = $role . '_info'; // Example: 'guard_info'

    // Check if role info exists in the specific table
    $checkInfoQuery = "SELECT * FROM `$roleInfoTable` WHERE first_name = ? AND last_name = ?";
    $checkInfoStmt = $conn->prepare($checkInfoQuery);
    $checkInfoStmt->bind_param("ss", $firstName, $lastName);
    $checkInfoStmt->execute();
    $infoResult = $checkInfoStmt->get_result();

    if ($infoResult->num_rows == 0) {
        // Insert into role_info table if not exists
        $insertRoleInfo = "INSERT INTO `$roleInfoTable` (first_name, middle_name, last_name, email, role_id) VALUES (?, ?, ?, ?, ?)"; // Use role_id instead of role
        $insertRoleInfoStmt = $conn->prepare($insertRoleInfo);
        $insertRoleInfoStmt->bind_param("ssssi", $firstName, $middleName, $lastName, $email, $roleId); // Include role_id in insertion
        
        if ($insertRoleInfoStmt->execute()) {
            $roleInfoId = $conn->insert_id; // Get the inserted role_info_id

            // Now insert into role table (authentication)
            $insertRoleAuth = "INSERT INTO `$roleTable` (username, password, role_info_id) VALUES (?, ?, ?)"; 
            $insertAuthStmt = $conn->prepare($insertRoleAuth);
            $insertAuthStmt->bind_param("ssi", $username, $password, $roleInfoId);  // Pass role_info_id

            if ($insertAuthStmt->execute()) {
                echo "<div class='alert alert-success'>Account successfully created for role '$role'</div>";
            } else {
                echo "<div class='alert alert-danger'>Error creating account: " . $conn->error . "</div>";
            }
        } else {
            echo "<div class='alert alert-danger'>Error inserting information: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Information already exists in '$roleInfoTable'</div>";
    }

    // Close prepared statements
    $checkInfoStmt->close();
    $insertRoleInfoStmt->close();
    $insertAuthStmt->close();
    $roleIdStmt->close(); // Close the role ID query statement
}
?>
