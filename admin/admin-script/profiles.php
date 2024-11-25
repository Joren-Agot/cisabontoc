<?php 
require_once '../core/partials/config.php';

// Check if a session is set and the user is logged in
if (isset($_SESSION['admin_id']) || isset($_SESSION['client_id']) || isset($_SESSION['staff_id']) || isset($_SESSION['student_id']) || isset($_SESSION['id'])) {

    // Determine the type of logged-in user (admin, client, staff, or student)
    if (isset($_SESSION['admin_id'])) {
        $userId = $_SESSION['admin_id'];

        // Fetch admin data
        $sqlUserData = "SELECT r.first_name, r.middle_name, r.last_name, r.image_path 
                        FROM admin_info r 
                        WHERE r.admin_id = $userId";
    } elseif (isset($_SESSION['client_id'])) {
        $userId = $_SESSION['client_id'];

        // Fetch client data
        $sqlUserData = "SELECT r.first_name, r.middle_name, r.last_name, r.image_path 
                        FROM client_info r 
                        WHERE r.client_id = $userId";
    } elseif (isset($_SESSION['staff_id'])) {
        $userId = $_SESSION['staff_id'];

        // Fetch staff data (corrected)
        $sqlUserData = "SELECT r.first_name, r.middle_name, r.last_name, r.image_path 
                        FROM staff_info r 
                        WHERE r.staff_id = $userId"; // This was missing a closing bracket earlier
    } elseif (isset($_SESSION['student_id'])) {
        $userId = $_SESSION['student_id'];

        // Fetch student data
        $sqlUserData = "SELECT r.first_name, r.middle_name, r.last_name, r.image_path 
                        FROM student_info r 
                        WHERE r.student_id = $userId";
    } elseif (isset($_SESSION['id'])) {
        $userId = $_SESSION['id'];

        // Fetch student data (new session ID added for student data)
        $sqlUserData = "SELECT r.first_name, r.middle_name, r.last_name, r.image_path
                        FROM student r 
                        WHERE r.id = $userId";  // Assuming 'id' is the session ID for students
    }

    // Execute the query
    $resultUserData = $conn->query($sqlUserData);

    if ($resultUserData && $resultUserData->num_rows > 0) {
        $userData = $resultUserData->fetch_assoc();
        $firstName = $userData['first_name'];
        $middleName = $userData['middle_name'];
        $lastName = $userData['last_name'];
        $profileImage = $userData['image_path'] ?? '../core/assets/images/users/profile_default.png';

        // If no profile image is found, set to default
        if (empty($profileImage)) {
            $profileImage = '../core/assets/images/users/admin/test.png';
        }
    } else {
        echo "Error fetching user data for user ID $userId";
        exit();
    }
} else {
    header("location: ../index.php");
    exit();
}
?>

