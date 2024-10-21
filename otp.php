<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input_otp = $_POST['otp'];
    $session_otp = $_SESSION['otp'];

    if ($input_otp == $session_otp) {
        // OTP is correct, redirect to dashboard
        header('Location: user/dashboard.php');
        exit();
    } else {
        $error_message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="../js/tailwind.js"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded-lg shadow-md w-96">
        <h2 class="text-2xl font-bold mb-6 text-center">Enter OTP code you have received on  (<?php echo $_SESSION['email']?>)</h2>
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        <form action="" method="POST">
            <div class="mb-4">
                <label for="otp" class="block text-gray-700 text-sm font-bold mb-2">One-Time Password</label>
                <input type="text" id="otp" name="otp" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            </div>
            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Verify OTP
                </button>
            </div>
        </form>
    </div>
</body>
</html>
