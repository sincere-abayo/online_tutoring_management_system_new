<?php
require 'user/db.php'; // Include your database connection

// Error message container
$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize form data
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm-password'];
    $profile_image = $_FILES['profileImage']['name'];

    // Basic validation
    if (empty($username) || empty($email)) {
        $errors[] = "Username and Email are required.";
    }

    // Check if passwords match
    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match.";
    }

    // Check if email is already registered
    $stmt = $conn->prepare("SELECT email FROM users WHERE email = ?");
    $stmt->bind_param('s', $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
        $errors[] = "Email is already registered. Please log in.";
    }

    // If no errors, proceed with registration
    if (empty($errors)) {
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Move uploaded profile image to a directory (e.g., uploads/)
        $target_dir = "image/";
        $target_file = $target_dir . basename($profile_image);
        move_uploaded_file($_FILES["profileImage"]["tmp_name"], $target_file);

        // Insert user into the database
        $stmt = $conn->prepare("INSERT INTO users (user_name, email, password, profile_image) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $hashed_password, $profile_image);

        if ($stmt->execute()) {
            // Get the last inserted user ID
            $user_id = $conn->insert_id;

            // Set session variables
            $_SESSION['user_id'] = $user_id;
            $_SESSION['username'] = $username;
            $_SESSION['email'] = $email;


            echo "<script>
                alert('Signup successful! Redirecting to your dashboard...');
                window.location.href = 'user/problem.php';
                </script>";
            exit();
        } else {
            $errors[] = "Something went wrong during signup. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign Up - Online Tutoring Platform</title>
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
    <!-- Signup Form -->
    <div class="bg-white shadow-md rounded-lg p-8 max-w-sm w-full">
      <h2 class="text-2xl font-bold mb-6 text-center">Sign Up</h2>
      <!-- Display errors if there are any -->
      <?php if (!empty($errors)): ?>
        <div id="error-container" class="text-red-500 mb-4">
          <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form id="signup-form" action="" method="post" enctype="multipart/form-data">
        <div class="mb-4">
          <label for="signup-username" class="block text-gray-700 font-bold mb-2">Username</label>
          <input type="text" id="signup-username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
          <label for="signup-email" class="block text-gray-700 font-bold mb-2">Email</label>
          <input type="email" id="signup-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-4">
          <label for="signup-password" class="block text-gray-700 font-bold mb-2">Password</label>
          <input type="password" id="signup-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="mb-6">
          <label for="signup-confirm-password" class="block text-gray-700 font-bold mb-2">Confirm Password</label>
          <input type="password" id="signup-confirm-password" name="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
          <p id="error-message" class="text-red-500 mt-2 hidden">Passwords do not match.</p>
        </div>
        <div class="mb-4">
          <label for="profile-image" class="block text-gray-700 font-bold mb-2">Profile Image</label>
          <input type="file" id="profile-image" name="profileImage" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Sign Up</button>
      </form>
      <p class="text-gray-600 text-sm mt-4 text-center">Already have an account? <a href="user_login.php" class="text-blue-500 hover:underline">Login</a></p>
    </div>
  </div>

  <!-- Footer -->
  <footer class="bg-gray-200 text-gray-600 py-4 mt-8">
    <div class="container mx-auto text-center">
      © 2024 Online Tutoring Platform. All rights reserved.
    </div>
  </footer>

  <script>
    document.getElementById('signup-form').addEventListener('submit', function(event) {
      const password = document.getElementById('signup-password').value;
      const confirmPassword = document.getElementById('signup-confirm-password').value;
      const errorMessage = document.getElementById('error-message');

      if (password !== confirmPassword) {
        errorMessage.classList.remove('hidden');
        event.preventDefault();
      } else {
        errorMessage.classList.add('hidden');
      }
    });
  </script>
</body>
</html>
