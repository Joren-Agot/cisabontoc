<?php
 // Include session file
include '../core/partials/config.php';  // Include database connection

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $category = $_POST['category'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Insert into your events table
    $query = "INSERT INTO events (title, category, start_date, end_date) 
              VALUES (:title, :category, :start_date, :end_date)";
    $stmt = $pdo->prepare($query);
    
    // Bind parameters
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':category', $category);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);
    
    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Event saved']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save event']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
