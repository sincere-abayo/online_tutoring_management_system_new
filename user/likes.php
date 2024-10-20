<?php
include "db.php";
header('Content-Type: application/json');

if (isset($_POST['comment_id'])) {
    $comment_id = $_POST['comment_id'];
    $user_id = $_SESSION['user_id'];
    
    // Check if the like already exists
    $check_sql = "SELECT * FROM likes WHERE comment_id = $comment_id AND user_id = $user_id";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        // Like exists, so delete it
        $delete_sql = "DELETE FROM likes WHERE comment_id = $comment_id AND user_id = $user_id";
        $result = $conn->query($delete_sql);
        $action = 'unlike';
    } else {
        // Like doesn't exist, so add it
        $insert_sql = "INSERT INTO likes (comment_id, user_id) VALUES ($comment_id, $user_id)";
        $result = $conn->query($insert_sql);
        $action = 'like';
    }
    
    if ($result) {
        echo json_encode(['success' => true, 'action' => $action, 'message' => $action === 'like' ? 'Like added successfully' : 'Like removed successfully']);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to ' . $action]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Comment ID not provided']);
}

