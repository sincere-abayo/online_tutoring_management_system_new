<?php
require 'user/db.php'; // Include your database connection

// Initialize variables for error messages
$error = "";

// Check if the form has been submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collect the form data
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Basic validation
    if (empty($email) || empty($password)) {
        $error = 'Please fill in all fields.';
    } else {
        // Check if the email exists in the database
        $stmt = $conn->prepare("SELECT user_id, user_name, password FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $hashed_password);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password matches, set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;

                // Redirect to the dashboard with success message
                echo "<script>
                   
                    window.location.href = 'user/dashboard.php';
                </script>";
                exit();
            } else {
                // Incorrect password
                $error = 'Incorrect password.';
            }
        } else {
            // Email not found
            $error = 'Email not found. Please sign up.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Online Tutoring Platform</title>
  <!-- Include Tailwind CSS -->
  <script src="js/tailwind.js"></script>
  <!-- Optional: Include any custom CSS -->
  <link href="styles.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
  <!-- Navigation -->
  <nav class="bg-blue-500 text-white py-4">
    <div class="container mx-auto flex justify-between items-center px-4">
      <h1 class="text-2xl font-bold">Online Tutoring Platform</h1>
      <a href="index.html" class="text-white">Home</a>
    </div>
  </nav>

  <!-- Main Content -->
  <div class="container mx-auto flex justify-center items-center h-screen">
    <!-- Login Form -->
    <div class="bg-white shadow-md rounded-lg p-8 max-w-sm w-full">
      <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>
      <?php if (!empty($error)) : ?>
        <div id="error-message" class="text-red-500 mb-4"><?php echo $error; ?></div>
      <?php endif; ?>
      <form id="login-form" method="POST" action="">
        <div class="mb-4">
          <label for="login-email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input type="email" id="login-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-6">
          <label for="login-password" class="block text-gray-700 font-bold mb-2">Password</label>
          <input type="password" id="login-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Login</button>
      </form>
      <p class="text-gray-600 text-sm mt-4 text-center">Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign up</a></p>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-200 text-gray-600 py-4 mt-8">
    <div class="container mx-auto text-center">
      © 2024 Online Tutoring Platform. All rights reserved.
    </div>
  </footer>
</body>
</html>
