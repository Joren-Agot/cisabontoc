<?php
include '../core/partials/session.php';
include '../core/partials/main.php';
include '../core/partials/config.php';

// SQL to create the roles table if it doesn't exist
$sql = "CREATE TABLE IF NOT EXISTS roles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    role_name VARCHAR(255) NOT NULL,
    role_type VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)";

// Execute the query to create the roles table
if ($conn->query($sql) === TRUE) {
    // Roles table created successfully or already exists
} else {
    echo "Error creating roles table: " . $conn->error;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture the role name and type from the form
    $newRole = $_POST['newRole'];
    $newType = $_POST['newType'];

    // Insert the new role into the 'roles' table
    $sql = "INSERT INTO roles (role_name, role_type) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $newRole, $newType);

    if ($stmt->execute()) {
        // Get the ID of the newly created role
        $roleId = $conn->insert_id; // Get the ID of the new role

        echo "Role added successfully!";

        // Create the corresponding role_info table for the new role with a foreign key reference to roles
        $createRoleInfoTable = "CREATE TABLE IF NOT EXISTS `{$newRole}_info` (
            id INT AUTO_INCREMENT PRIMARY KEY,
            first_name VARCHAR(255) NOT NULL,
            middle_name VARCHAR(255),
            last_name VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            role_id INT NOT NULL, -- Foreign key column referencing roles table
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE -- Reference to roles table
        )";

        if ($conn->query($createRoleInfoTable) === TRUE) {
            echo "Table '{$newRole}_info' created successfully.";

            // Create the new role-specific table with a foreign key to the role_info table
            $createRoleTable = "CREATE TABLE IF NOT EXISTS `$newRole` (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                role_info_id INT NOT NULL, -- Foreign key column
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (role_info_id) REFERENCES `{$newRole}_info`(id) ON DELETE CASCADE
            )";

            if ($conn->query($createRoleTable) === TRUE) {
                echo "Table '$newRole' created successfully.";
            } else {
                echo "Error creating table '$newRole': " . $conn->error;
            }
        } else {
            echo "Error creating table '{$newRole}_info': " . $conn->error;
        }
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>
