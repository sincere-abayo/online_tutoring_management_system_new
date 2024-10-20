<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $problem_id = $_POST['problem_id'];

    // Prepare the SQL statement to delete the problem
    $stmt = $conn->prepare("DELETE FROM problems WHERE problem_id = ?");
    $stmt->bind_param('i', $problem_id);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Problem deleted successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to delete problem']);
    }

    // Close the statement
    $stmt->close();
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}

// Close the database connection
$conn->close();
?>
