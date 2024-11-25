<?php
require_once '../core/partials/config.php';

// Get user type from the POST request (staff, client, or new role)
$userType = isset($_POST['user_type']) ? $_POST['user_type'] : 'staff';

// SQL query based on user type
if ($userType == 'client') {
    $sql = "SELECT client_id as user_id, first_name, middle_name, last_name, email, NULL as role, image_path FROM client_info";
} elseif ($userType == 'staff') {
    $sql = "SELECT staff_id as user_id, first_name, middle_name, last_name, email, role, image_path FROM staff_info";
} else {
    // Query to fetch users based on the new roles
$sql = "SELECT 
            r.id as role_id, 
            r.role_name, 
            ri.first_name, 
            ri.middle_name, 
            ri.last_name, 
            ri.email
        FROM 
            roles r 
        JOIN 
            `{$userType}_info` ri ON r.id = ri.role_id"; // Joining the roles with user info

}

$result = $conn->query($sql);

$userdata = array();
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $userdata[] = $row;
    }
} else {
    echo "Error: " . $conn->error;
}
?>

