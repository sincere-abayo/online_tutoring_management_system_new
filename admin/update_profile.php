<?php
include('../user/db.php');
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    die(json_encode(['error' => 'Unauthorized access']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'];
    
    if (isset($_POST['action']) && $_POST['action'] === 'change_password') {
        $currentPassword = $_POST['currentPassword'];
        $newPassword = $_POST['newPassword'];
        
        // Verify current password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($currentPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateStmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $updateStmt->bind_param("si", $hashedPassword, $userId);
            
            if ($updateStmt->execute()) {
                echo json_encode(['success' => 'Password updated successfully']);
            } else {
                echo json_encode(['error' => 'Failed to update password']);
            }
        } else {
            echo json_encode(['error' => 'Current password is incorrect']);
        }
    }
    
    if (isset($_POST['action']) && $_POST['action'] === 'change_email') {
        $newEmail = $_POST['newEmail'];
        $password = $_POST['password'];
        
        // Verify password
        $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
            $updateStmt = $conn->prepare("UPDATE users SET email = ? WHERE id = ?");
            $updateStmt->bind_param("si", $newEmail, $userId);
            
            if ($updateStmt->execute()) {
                echo json_encode(['success' => 'Email updated successfully']);
            } else {
                echo json_encode(['error' => 'Failed to update email']);
            }
        } else {
            echo json_encode(['error' => 'Password is incorrect']);
        }
    }
}
?>
