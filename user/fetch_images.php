<?php
header('Content-Type: application/json');
include 'db.php'; // Include the database connection

$images = [];

try {
    if (isset($_GET['problem_id'])) {
        $problem_id = intval($_GET['problem_id']);
        
        // Fetch problem images
        $sql_problem = "SELECT image_url FROM problem_images WHERE problem_id = ?";
        $stmt_problem = $conn->prepare($sql_problem);
        $stmt_problem->bind_param('i', $problem_id);
        $stmt_problem->execute();
        $result_problem = $stmt_problem->get_result();

        while ($row = $result_problem->fetch_assoc()) {
            $images[] = ['type' => 'problem', 'url' => $row['image_url']];
        }

    } elseif (isset($_GET['comment_id']) || isset($_GET['reply_id'])) {
        $comment_id = isset($_GET['comment_id']) ? intval($_GET['comment_id']) : intval($_GET['reply_id']);
        
        // Fetch comment or reply image
        $sql_comment = "SELECT image_url FROM comments WHERE comment_id = ? AND image_url IS NOT NULL";
        $stmt_comment = $conn->prepare($sql_comment);
        $stmt_comment->bind_param('i', $comment_id);
        $stmt_comment->execute();
        $result_comment = $stmt_comment->get_result();

        if ($row = $result_comment->fetch_assoc()) {
            $images[] = ['type' => 'comment', 'url' => $row['image_url']];
        }
    } else {
        throw new Exception("No valid ID provided");
    }

    echo json_encode($images);
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
?>
