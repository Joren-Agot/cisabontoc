<?php
require_once '../core/partials/config.php';

// Handle the form submission (via AJAX)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the data from the AJAX request
    $title = $_POST['title'];
    $category = $_POST['category'];
    $start = $_POST['start'];
    $end = $_POST['end'];

    // Prepare the SQL statement to insert the event into the calevents table
    $stmt = $conn->prepare("INSERT INTO calevents (title, start, end, className) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $title, $start, $end, $category);

    // Execute the query
    if ($stmt->execute()) {
        echo "Event saved successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    exit();  // Prevent further processing if it's a POST request
}

// Fetch events from the database
$query = "SELECT title, start, end, className FROM calevents"; // Adjust table/column names as necessary
$result = $conn->query($query);

// Initialize an empty array to store the events
$sched_res = [];

while ($row = $result->fetch_assoc()) {
    // Add each event to the array
    $sched_res[] = [
        'title' => $row['title'],
        'start' => $row['start'],
        'end' => $row['end'],
        'className' => $row['className'] // Assuming className stores the event color/style
    ];
}
?>
