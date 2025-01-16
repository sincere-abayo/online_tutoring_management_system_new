<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'vendor/autoload.php';

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
  $role = "user"; // Default role for new signups
  $stmt = $conn->prepare("INSERT INTO users (user_name, email, password, profile_image, role) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $username, $email, $hashed_password, $profile_image, $role);

  if ($stmt->execute()) {
      // Get the last inserted user ID
      $user_id = $conn->insert_id;

      // Set session variables
      $_SESSION['user_id'] = $user_id;
      $_SESSION['username'] = $username;
      $_SESSION['email'] = $email;
      $_SESSION['role'] = $role;
      

      // Generate a random 6-digit OTP
      $otp = sprintf("%06d", mt_rand(1, 999999));
      $_SESSION['otp'] = $otp;

      $mail = new PHPMailer(true);

      try {
          //Server settings
          $mail->SMTPDebug = 0;
          $mail->isSMTP();
          $mail->Host       = 'smtp.gmail.com';
          $mail->SMTPAuth   = true;
          $mail->Username   = 'onlinetutoringmanagementsystem@gmail.com';
          $mail->Password   = 'echq pdvq pszu dhyf';
          $mail->SMTPSecure = 'tls';
          $mail->Port       = 587;
            
          //Recipients
          $mail->setFrom('onlinetutoringmanagementsystem@gmail.com', 'Online Tutoring Management System');
          $mail->addAddress($email, $username);
      
          //Content
          $mail->isHTML(true);
          $mail->Subject = 'OTP Verification for Online Tutoring Management System';
          $mail->Body    = "Your OTP for account verification is: <b>$otp</b>. Please enter this code to complete your registration.";
          $mail->AltBody = "Your OTP for account verification is: $otp. Please enter this code to complete your registration.";
      
          $mail->send();
          echo "<script>
          alert('Signup successful! Please check your email for OTP verification.');
          window.location.href = 'otp.php';
          </script>";
          exit();
      } catch (Exception $e) {
          echo "Email could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }
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


  <!-- Main Content -->
  <div class="container md:mx-auto mx-5 flex justify-center items-center h-screen">
    <!-- Signup Form -->
    <div class="bg-white shadow-md rounded-lg p-8  md:w-1/2 w-full">

        
    <div class="text-center">
      <h1 class="text-xl font-semibold text-blue-800 text-center">Online Tutoring Platform</h1>
      </div>

      <h2 class="text-xl font-medium my-4 text-center">Sign Up</h2>
  
      <!-- Display errors if there are any -->
      <?php if (!empty($errors)): ?>
        <div id="error-container" class="text-red-500 mb-4">
          <?php foreach ($errors as $error): ?>
            <p><?php echo $error; ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
      <form id="signup-form" action="" method="post" enctype="multipart/form-data">

      <div class="grid  grid-cols-1 md:grid-cols-2 gap-5">


        <div class="mb-1">
          <label for="signup-username" class="block text-black ">Username <span class="text-red-500">*</span> </label>
          <input type="text" id="signup-username" name="username" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required placeholder="Enter your username">
        </div>
        <div class="mb-1">
          <label for="signup-email" class="block text-black ">Email <span class="text-red-500">*</span> </label>
          <input type="email" id="signup-email" name="email" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required placeholder="Enter your email">
        </div>
        
        <div class="mb-1">
          <label for="signup-password" class="block text-black ">Password <span class="text-red-500">*</span> </label>
          <div class="relative">
            <input type="password" id="signup-password" name="password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required placeholder="Enter your password">
            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2" onclick="togglePassword('signup-password', 'eye-icon-1')">
              <svg id="eye-icon-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
        </div>
        <div class="mb-1">
          <label for="signup-confirm-password" class="block text-black ">Confirm Password <span class="text-red-500">*</span> </label>
          <div class="relative">
            <input type="password" id="signup-confirm-password" name="confirm-password" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required placeholder="Confirm your password">
            <button type="button" class="absolute right-3 top-1/2 transform -translate-y-1/2" onclick="togglePassword('signup-confirm-password', 'eye-icon-2')">
              <svg id="eye-icon-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="h-5 w-5">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
              </svg>
            </button>
          </div>
          <p id="error-message" class="text-red-500 mt-2 hidden">Passwords do not match.</p>
        </div>
        <script>
          function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
              input.type = 'text';
              icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />`;
            } else {
              input.type = 'password';
              icon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />`;
            }
          }
        </script>        <div class="mb-1">
          <label for="profile-image" class="block text-black ">Profile Image <span class="text-red-500">*</span> </label>
          <input type="file" id="profile-image" name="profileImage" accept="image/*" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
        </div>
        <div class="md:col-span-2 ">
          <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Sign Up</button>
        </div>

        </div>
      </form>
      <p class="text-gray-600 text-sm mt-4 text-center">Already have an account? <a href="login.php" class="text-blue-500 hover:underline">Login</a></p>
      <p class="text-gray-600 text-sm mt-4 text-center"> <a href="index.php" class="text-blue-500 hover:underline">Back to Home</a></p>
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
