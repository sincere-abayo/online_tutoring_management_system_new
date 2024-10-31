<?php
include 'db.php';
include 'navbar.php';

$user_id = $_SESSION['user_id'];

// Fetch current user details
$sql = "SELECT * FROM users WHERE user_id = $user_id";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

// Handle form submission for profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $new_username = $_POST['user_name'];
    $new_email = $_POST['email'];
    
    $update_sql = "UPDATE users SET user_name = ?, email = ? WHERE user_id = ?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssi", $new_username, $new_email, $user_id);
    
    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
    } else {
        $error_message = "Error updating profile: " . $conn->error;
    }
    
    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_image'])) {
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] == 0) {
        $target_dir = "../image/";
        $profile_image = basename($_FILES['profile_image']['name']);
        $target_file = $target_dir . $profile_image;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $target_file)) {
            $update_image_sql = "UPDATE users SET profile_image = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_image_sql);
            $stmt->bind_param("si", $profile_image, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Profile image updated successfully!";
                $user['profile_image'] = $profile_image; // Update the current user data
            } else {
                $error_message = "Error updating profile image: " . $conn->error;
            }
            
            $stmt->close();
        } else {
            $error_message = "Sorry, there was an error uploading your file.";
        }
    } else {
        $error_message = "No file was uploaded or an error occurred.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Verify current password
    $stmt = $conn->prepare("SELECT password FROM users WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user_data = $result->fetch_assoc();

    if (password_verify($current_password, $user_data['password'])) {
        if ($new_password === $confirm_password) {
            $hashed_new_password = password_hash($new_password, PASSWORD_DEFAULT);
            
            $update_password_sql = "UPDATE users SET password = ? WHERE user_id = ?";
            $stmt = $conn->prepare($update_password_sql);
            $stmt->bind_param("si", $hashed_new_password, $user_id);
            
            if ($stmt->execute()) {
                $success_message = "Password updated successfully!";
            } else {
                $error_message = "Error updating password: " . $conn->error;
            }
        } else {
            $error_message = "New passwords do not match.";
        }
    } else {
        $error_message = "Current password is incorrect.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <script src="../js/tailwind.js"></script>
</head>
<body class="bg-gray-100">
    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-3xl font-bold mb-6">User Profile</h1>
        
        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                            <p><?php echo $success_message; ?></p>
                            <script>
                                setTimeout(function() {
                                   window.location.href = 'profile.php'
                                }, 2000);
                            </script>
            </div>
        <?php endif; ?>

        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        
        <!-- Display current details -->
         <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <div class="bg-white shadow-md rounded p-6 mb-6 h-full">
            <h2 class="text-xl font-semibold mb-4">Current Details</h2>
            <div class="flex items-center mb-4">
                <img src="../image/<?php echo htmlspecialchars($user['profile_image']); ?>" alt="Profile Picture" class="w-24 h-24 rounded-full object-cover mr-4">
                <div>
                    <p><strong>Name:</strong> <?php echo htmlspecialchars($user['user_name']); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
                </div>
            </div>
            <form action="" method="POST" enctype="multipart/form-data" class="mt-4">
                <label for="profile_image" class="block text-gray-700 text-sm font-bold mb-2">Update Profile Picture</label>
                <input type="file" id="profile_image" name="profile_image" accept="image/*" class="mb-2">
                <button type="submit" name="update_image" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Upload New Image
                </button>
            </form>
        </div>

        <!-- Edit Profile Form -->
        <div class="bg-white shadow-md rounded p-6 h-full flex flex-col">
            <h2 class="text-xl font-semibold mb-4">Edit Profile</h2>
            <form action="#" method="POST" class="flex-grow">
                <div class="mb-4">
                    <label for="user_name" class="block text-gray-700 text-sm font-bold mb-2">Name</label>
                    <input type="text" id="user_name" name="user_name" value="<?php echo htmlspecialchars($user['user_name']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                </div>
                <div class="flex items-center justify-between">
                    <button type="submit" name="update_profile" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Update Profile
                    </button>
                </div>
            </form>

            <!-- Password Update Form (Initially Hidden) -->
            <div id="passwordUpdateForm" class="hidden bg-white rounded mt-6">
                <h2 class="text-xl font-semibold mb-4">Update Password</h2>
                <form action="" method="POST">
                    <div class="mb-4">
                        <label for="current_password" class="block text-gray-700 text-sm font-bold mb-2">Current Password</label>
                        <input type="password" id="current_password" name="current_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="new_password" class="block text-gray-700 text-sm font-bold mb-2">New Password</label>
                        <input type="password" id="new_password" name="new_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="mb-4">
                        <label for="confirm_password" class="block text-gray-700 text-sm font-bold mb-2">Confirm New Password</label>
                        <input type="password" id="confirm_password" name="confirm_password" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    </div>
                    <div class="flex items-center justify-between">
                        <button type="submit" name="update_password" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            <!-- Button to toggle password update form -->
            <button onclick="togglePasswordForm()" class="mt-4 bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Change Password
            </button>
        </div>
        </div>


        <script>
function togglePasswordForm() {
    const form = document.getElementById('passwordUpdateForm');
    form.classList.toggle('hidden');
    if (!form.classList.contains('hidden')) {
        form.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}
</script>


</body>
</html>
