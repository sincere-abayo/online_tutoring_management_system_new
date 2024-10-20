<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $problem_id = $_POST['problem_id'];
    $category = $_POST['category_id'];
    $description = $_POST['description'];
    $contact = $_POST['contact'];

    // Prepare the SQL statement
    $stmt = $conn->prepare("UPDATE problems SET category_id = ?, description = ?, contact = ? WHERE problem_id = ?");
    
    // Bind parameters
    $stmt->bind_param('issi', $category, $description, $contact, $problem_id);

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
