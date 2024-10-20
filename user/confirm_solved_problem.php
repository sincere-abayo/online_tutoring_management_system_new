<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $problem_id = $_POST['problem_id'];
    $status = "solved";
  
    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE problems SET status = ? WHERE problem_id = ?");
    
    // Bind parameters
    $stmt->bind_param('si', $status, $problem_id);

    // Execute the query
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Problem updated successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to update problem']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

$conn->close();
?>
