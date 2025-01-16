<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['otp'])) {
        $input_otp = $_POST['otp'];
        $session_otp = $_SESSION['otp'];

        if ($input_otp == $session_otp) {
            // OTP is correct, redirect to dashboard
            header('Location: user/dashboard.php');
            exit();
        } else {
            $error_message = "Invalid OTP. Please try again.";
        }
    } elseif (isset($_POST['resend'])) {
        // Generate new OTP
        $new_otp = rand(100000, 999999);
        $_SESSION['otp'] = $new_otp;
        
        // Send email with new OTP
        $to = $_SESSION['email'];
        $subject = "New OTP Code";
        $message = "Your new OTP code is: " . $new_otp;
        $headers = "From: noreply@yourwebsite.com";
        
        mail($to, $subject, $message, $headers);
        
        $success_message = "OTP has been resent successfully to " . $_SESSION['email'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-blue-50 flex items-center justify-center min-h-screen">
    <div class="bg-white p-10 rounded-xl shadow-lg w-96 border border-blue-100">
        <h2 class="text-2xl font-bold mb-6 text-center text-blue-800">Enter OTP Code</h2>
        <p class="text-blue-600 text-sm text-center mb-6">A code has been sent to:<br/>(<?php echo $_SESSION['email']?>)</p>
        <?php if (isset($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-md" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>
        <?php if (isset($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4 rounded-md" role="alert">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>
        <form action="" method="POST" class="space-y-6">
            <div>
                <label for="otp" class="block text-blue-700 text-sm font-semibold mb-2">One-Time Password</label>
                <input type="text" id="otp" name="otp" required 
                    class="shadow-sm appearance-none border border-blue-200 rounded-lg w-full py-3 px-4 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-200">
            </div>
            <div class="space-y-4">
                <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-200 w-full transform hover:scale-[1.02]">
                    Verify OTP
                </button>
                <button type="submit" name="resend" formnovalidate
                    class="block text-center text-blue-600 hover:text-blue-800 text-sm font-medium transition duration-200 w-full">
                    Resend OTP Code
                </button>
            </div>
        </form>
    </div>
</body>
</html>