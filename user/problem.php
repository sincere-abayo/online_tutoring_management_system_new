<?php
// Start the session
session_start();

// Include the database connection
include 'db.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header('Location: login.php');
    exit;
}

// Get the logged-in user's ID and email from the session
$user_id = $_SESSION['user_id'];
$user_email = $_SESSION['email'];

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data
    $category = $_POST['category'];
    $description = $_POST['description'];
    $email = $_POST['email'];
    $country_code = $_POST['country_code'];
    $phone = $_POST['phone'];

    // Server-side phone number validation (format: 732 286 284)
    if (preg_match("/^[0-9]{3} [0-9]{3} [0-9]{3}$/", $phone)) {
        // Concatenate country code and phone number
        $full_phone_number = $country_code . ' ' . $phone;

        // Prepare and bind the SQL query for problems table
        $stmt = $conn->prepare("INSERT INTO problems (user_id, category_id, description, email, contact, status) VALUES (?, ?, ?, ?, ?, 'pending')");
        $stmt->bind_param('iisss', $user_id, $category, $description, $email, $full_phone_number);

        // Execute the query for problems
        if ($stmt->execute()) {
            $problem_id = $stmt->insert_id; // Get the inserted problem ID

            // Handle image uploads
            if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
                $imageDir = '../image/'; // Directory where images will be uploaded

                foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
                    $file_name = basename($_FILES['images']['name'][$key]);
                    $target_file = $imageDir . $file_name;

                    // Validate the file type (optional)
                    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                    $allowedTypes = ['jpg', 'jpeg', 'png'];

                    if (in_array($imageFileType, $allowedTypes)) {
                        if (move_uploaded_file($tmp_name, $target_file)) {
                            // Prepare and bind the SQL query for problem_images table
                            $stmt_images = $conn->prepare("INSERT INTO problem_images (problem_id, image_url) VALUES (?, ?)");
                            $stmt_images->bind_param('is', $problem_id, $target_file);
                            $stmt_images->execute();
                            $stmt_images->close();
                        } else {
                            $error = "Error uploading image: " . $file_name;
                        }
                    } else {
                        $error = "Invalid file type for image: " . $file_name;
                    }
                }
            }

            // Success, redirect to the dashboard
            header('Location: dashboard.php');
            exit;
        } else {
            $error = "Error: " . $stmt->error;
        }

        // Close the statement
        $stmt->close();
    } else {
        $error = "Invalid phone number format. Please use the format: 732 286 284.";
    }
}


// Include the navbar
include 'navbar.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post a Problem - Online Tutoring Platform</title>
    <script src="../js/tailwind.js"></script>
    <link href="styles.css" rel="stylesheet">
</head>

<body>
    <!-- Main Content -->
    <div class="container mx-auto mt-8 px-4">
        <div class="bg-white shadow-md rounded-lg p-8 max-w-md mx-auto">
            <h2 class="text-2xl font-bold mb-6 text-center">Post a Problem</h2>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 text-red-700 p-4 mb-4 rounded">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Problem Posting Form -->
            <form action="" method="POST" enctype="multipart/form-data">
                <!-- Hidden field for User ID -->
                <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">

                <div class="mb-4">
                    <label for="category" class="block text-gray-700 font-bold mb-2">Category</label>
                    <select id="category" name="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                        required>
                        <option value="">Select a category</option>
                        <option value="1">Information Technology (IT) and Software Development</option>
                        <option value="2">Data Science, Analytics, and AI</option>
                        <option value="3">Cybersecurity and Database Management</option>
                        <option value="4">Digital Media and Marketing</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-gray-700 font-bold mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 resize-none"
                        maxlength="500" placeholder="Approx. 50 words" required></textarea>
                    <p class="text-gray-500 text-xs mt-1">Maximum 500 characters (approx. 50 words).</p>
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-gray-700 font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?= $user_email ?>"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                        required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-gray-700 font-bold mb-2">Phone Contact</label>
                    <div class="flex">
                        <!-- Country code dropdown -->
                        <select id="country_code" name="country_code"
                            class="w-1/4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                            <option value="250">+250 Rwanda</option>
                            <option value="254">+254 Kenya</option>
                            <option value="255">+255 Tanzania</option>
                            <option value="256">+256 Uganda</option>
                        </select>
                        <!-- Phone number input -->
                        <input type="tel" id="phone" name="phone"
                            class="w-3/4 px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 ml-2"
                            placeholder="732 286 284" required>
                    </div>
                    <small class="text-gray-500">Phone number should be in the format: 732 286 284</small>
                </div>
                <div class="mb-4">
                    <label for="images" class="block text-gray-700 font-bold mb-2">Upload Images</label>
                    <input type="file" id="images" name="images[]"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"
                        multiple>
                    <small class="text-gray-500">You can upload multiple images (JPEG, PNG).</small>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md w-full hover:bg-blue-600">Post
                    Problem</button>
            </form>

            <p class="text-gray-600 text-sm mt-4 text-center">Return to <a href="dashboard.php"
                    class="text-blue-500 hover:underline">Home</a></p>
        </div>
    </div>

    <!-- JavaScript for phone number formatting and validation -->
    <script>
        function formatPhoneNumber(value) {
            // Remove non-digit characters
            const digits = value.replace(/\D/g, '');
            const formatted = [];

            // Format the phone number
            for (let i = 0; i < digits.length; i++) {
                if (i === 3 || i === 6) {
                    formatted.push(' '); // Add a space after 3rd and 6th digits
                }
                formatted.push(digits[i]);
            }

            return formatted.join('');
        }

        function validatePhoneNumber(phone) {
            const phonePattern = /^[0-9]{3} [0-9]{3} [0-9]{3}$/;
            return phonePattern.test(phone);
        }

        document.getElementById('phone').addEventListener('input', function (e) {
            this.value = formatPhoneNumber(this.value); // Format phone number on input
        });

        document.querySelector('form').addEventListener('submit', function (e) {
            const phone = document.getElementById('phone').value;

            if (!validatePhoneNumber(phone)) {
                e.preventDefault();  // Prevent form submission
                alert('Invalid phone number format. Please use the format: 732 286 284.');
            }
        });
    </script>
</body>

</html>