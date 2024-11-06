<?php
include('../user/db.php');

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    
    // First get the current role
    $stmt = $conn->prepare("SELECT role FROM users WHERE user_id = ?");
    $stmt->bind_param('i', $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    // Determine the new role
    $new_role = ($user['role'] === 'admin') ? 'user' : 'admin';
    
    // Update with the new role
    $update_stmt = $conn->prepare("UPDATE users SET role = ? WHERE user_id = ?");
    $update_stmt->bind_param('si', $new_role, $user_id);
    
    if ($update_stmt->execute()) {
        echo json_encode(['success' => true, 'new_role' => $new_role]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
