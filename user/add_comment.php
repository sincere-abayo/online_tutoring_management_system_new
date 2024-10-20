<?php
include 'db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $comment = $_POST['comment'];
    $problem_id = $_POST['problem_id'];
    $user_id = 1; // Replace with the actual logged-in user ID (from session or other authentication)
    
    // Check if this is a reply to another comment
    $parent_comment_id = isset($_POST['parent_comment_id']) ? $_POST['parent_comment_id'] : NULL;

    // Handle image upload
    $image_url = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $upload_dir = '../image/';
        $file_name = uniqid() . '_' . $_FILES['image']['name'];
        $upload_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $upload_path)) {
            $image_url = $upload_path;
        }
    }

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO comments (problem_id, user_id, parent_comment_id, comment, image_url) VALUES (?, ?, ?, ?, ?)");
    
    // Bind parameters (problem_id, user_id, parent_comment_id, comment, image_url)
    $stmt->bind_param('iiiss', $problem_id, $user_id, $parent_comment_id, $comment, $image_url);

    // Execute the query and check if it was successful
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
    exit();
}
?>
