  <body class="bg-gray-200 flex flex-col min-h-screen">
    <!-- Navigation -->
     <?php 
     
    //  get user profile picture
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM users WHERE user_id = '$user_id'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $profile_image = $row['profile_image'];
    
     ?>
    <nav class="bg-blue-800 text-white border-b border-gray-500 py-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <div class="text-center">
            <h1 class="text-2xl font-bold">OTMS</h1>
            <p class="text-xs text-gray-200">Online Tutoring Management System</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white">Home</a>
                <a href="my_problem.php" class="text-white">My Problems</a>
                <a href="../logout.php" class="text-white">Logout</a>
                <!-- Profile Image -->
                <img
                    src="../image/<?php echo $profile_image ?>"
                    alt="Profile"
                    class="w-10 h-10 rounded-full object-cover cursor-pointer"
                    onclick="sendToProfile()"
                />
            </div>
        </div>
    </nav>


    
    <!-- Profile Modal -->
</body>
<!-- Footer
<footer class="bg-gray-200 text-gray-600 py-4 mt-8">
    <div class="container mx-auto text-center">
        © 2024 Online Tutoring Platform. All rights reserved.
    </div>
</footer> -->

<script>
    function sendToProfile() {
        // send to profile.php
        window.location.href = 'profile.php';
    }

    function closeprofileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }
</script>
</html>