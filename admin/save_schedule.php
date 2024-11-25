<?php 
include '../core/partials/config.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] != 'POST') {
    echo "<script> alert('Error: No data to save.'); location.replace('calendar.php') </script>";
    $conn->close();
    exit;
}

extract($_POST);
$allday = isset($allday); // Ensure this variable exists

// Insert or update the schedule based on the presence of the `id`
if (empty($id)) {
    $sql = "INSERT INTO `schedule_list` (`title`, `description`, `start_datetime`, `end_datetime`) VALUES ('$title', '$description', '$start_datetime', '$end_datetime')";
} else {
    $sql = "UPDATE `schedule_list` SET 
                `title` = '{$title}', 
                `description` = '{$description}', 
                `start_datetime` = '{$start_datetime}', 
                `end_datetime` = '{$end_datetime}' 
            WHERE `id` = '{$id}'";
}

// Execute the query
$save = $conn->query($sql);

// Redirect or show error message
if ($save) {
    echo "<script> alert('Schedule Successfully Saved.'); location.replace('calendar.php') </script>";
} else {
    echo "<pre>";
    echo "An Error occurred.<br>";
    echo "Error: " . $conn->error . "<br>";
    echo "SQL: " . $sql . "<br>";
    echo "</pre>";
}

// Close the database connection
$conn->close();
?>
