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
        // Modify the SQL query to include the role
        $stmt = $conn->prepare("SELECT user_id, user_name, password, role FROM users WHERE email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($user_id, $username, $hashed_password, $role);
        $stmt->fetch();

        if ($stmt->num_rows > 0) {
            // Verify the password
            if (password_verify($password, $hashed_password)) {
                // Password matches, set session variables
                $_SESSION['user_id'] = $user_id;
                $_SESSION['username'] = $username;
                $_SESSION['email'] = $email;
                $_SESSION['role'] = $role;

                // Redirect based on role
                if ($role === 'admin') {
                    echo "<script>window.location.href = 'admin/dashboard.php';</script>";
                } else {
                    echo "<script>window.location.href = 'user/dashboard.php';</script>";
                }
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


  <!-- Main Content -->
  <div class="container mx-auto flex justify-center items-center min-h-screen bg-gray-100 px-4 py-8">
    <!-- Login Form and Image -->
    <div class="flex flex-col md:flex-row items-center bg-white shadow-md rounded-lg p-8 md:w-1/2 w-full ">
      <div class="mb-8 md:mb-0 md:mr-8 w-full md:w-1/2">
        <img src="image/login.avif" alt="Login" class=" w-full max-w-md mx-auto">
        <div class="mt-4 text-center">
          <a href="index.php" class=" hover:text-blue-400 text-blue-800 font-medium py-2 px-4 rounded">
            Back to Home
          </a>
        </div>
      </div>
      <div class="w-full md:w-1/2">
      <h1 class="text-xl font-semibold text-blue-800 text-center">Online Tutoring Platform</h1>
        <h2 class="text-xl font-medium my-4 text-center">Login</h2>
        <?php if (!empty($error)) : ?>
          <div id="error-message" class="text-red-500 mb-4"><?php echo $error; ?></div>
        <?php endif; ?>
        <form id="login-form" method="POST" action="">
          <div class="mb-2">
            <label for="login-email" class="block text-black">Email <span class="text-red-500">*</span></label>
            <input type="email" id="login-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" placeholder="Email" required>
          </div>
          <div class="mb-2">
            <label for="login-password" class="block text-black">Password <span class="text-red-500">*</span></label>
            <div class="relative">
              <input type="password" id="login-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" placeholder="Password" required>
              <button type="button" onclick="togglePassword()" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                <svg class="h-5 w-5 text-gray-500" id="eye-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                </svg>
              </button>
            </div>
          </div>
          <script>
            function togglePassword() {
              const passwordInput = document.getElementById('login-password');
              const eyeIcon = document.getElementById('eye-icon');
              if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
              } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
              }
            }
          </script>          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Login</button>
        </form>
        <p class="text-gray-600 text-sm mt-4 text-center">Don't have an account? <a href="signup.php" class="text-blue-500 hover:underline">Sign up</a></p>
      </div>
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
