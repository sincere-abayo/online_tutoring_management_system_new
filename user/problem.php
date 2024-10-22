<?php
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
            header('Location: my_problem.php');
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
$problem_category_sql= "SELECT * from problem_categories";
$problem_category_result = mysqli_query($conn, $problem_category_sql);


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
<body class="bg-gray-100">

    <div class="container mx-auto mt-8 px-4">
        <div class="bg-white shadow-lg rounded-lg p-8 max-w-2xl mx-auto">
            <h2 class="text-3xl text-center text-gray-800">Post a Problem</h2>

            <?php if (isset($error)): ?>
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded">
                    <p class="font-bold">Error</p>
                    <p><?= $error ?></p>
                </div>
            <?php endif; ?>

            <form action="" method="POST" enctype="multipart/form-data" class="">
    <input type="hidden" id="user_id" name="user_id" value="<?= $user_id ?>">

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <!-- Category -->
    <div>
        <label for="category" class="block text-sm  text-gray-700 mb-1">Category <span class="text-red-500">*</span> </label>
        <select id="category" name="category" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" required>
            <option value="" disabled selected>Select a category</option>
            <?php while ($row = mysqli_fetch_assoc($problem_category_result)): ?>
                <option value="<?= $row['category_id'] ?>"><?= $row['category_name'] ?></option>
            <?php endwhile; ?>
           
        </select>
    </div>

    <!-- Description -->
    <div>
        <label for="description" class="block text-sm  text-gray-700 mb-1">Description <span class="text-red-500">*</span> </label>
        <textarea id="description" name="description" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 resize-none" maxlength="500" placeholder="Describe your problem (approx. 50 words)" required></textarea>
        <p class="text-gray-500 text-xs mt-1">Maximum 500 characters (approx. 50 words).</p>
    </div>

    <!-- Email -->
    <div>
        <label for="email" class="block text-sm  text-gray-700 mb-1">Email <span class="text-red-500">*</span> </label>
        <input type="email" id="email" name="email" value="<?= $user_email ?>" readonly class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200 bg-gray-100 cursor-not-allowed" required>
    </div>

    <!-- Phone Contact -->
    <div>
        <label for="phone" class="block text-sm  text-gray-700 mb-1">Phone Contact <span class="text-red-500">*</span> </label>
        <div class="flex">
            <select id="country_code" name="country_code" class="w-1/3 px-3 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                <option value="250">+250 Rwanda</option>
                <option value="254">+254 Kenya</option>
                <option value="255">+255 Tanzania</option>
                <option value="256">+256 Uganda</option>
            </select>
            <input type="tel" id="phone" name="phone" class="w-2/3 px-3 py-2 border border-gray-300 rounded-r-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200" placeholder="732 286 284" required>
        </div>
        <p class="text-gray-500 text-xs mt-1">Phone number format: 732 286 284</p>
    </div>

    <!-- Image Upload -->
    <div>
        <label for="images" class="block text-sm  text-gray-700 mb-1">Upload Images <span class="text-red-500">*</span> </label>
        <input type="file" id="images" name="images[]" multiple accept="image/jpeg, image/png" class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
        <p class="text-gray-500 text-xs mt-1">You can upload multiple images (JPEG, PNG).</p>
    </div>

    <!-- Submit Button -->
     <div class="md:col-span-2">
         <button type="submit" class="w-full bg-blue-500 text-white px-4 py-1 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200">Post</button>
     </div>
    </div>
</form>


         
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