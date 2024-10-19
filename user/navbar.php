  <body class="bg-gray-100 flex flex-col min-h-screen">
    <!-- Navigation -->
    <nav class="bg-blue-500 text-white py-4 sticky top-0 z-50">
        <div class="container mx-auto flex justify-between items-center px-4">
            <h1 class="text-2xl font-bold">Online Tutoring Platform</h1>
            <div class="flex items-center space-x-4">
                <a href="dashboard.php" class="text-white hover:text-blue-200">Home</a>
                <a href="my_problem.php" class="text-white hover:text-blue-200">My Problems</a>
                <a href="problem.php" class="text-white hover:text-blue-200">Post Problem</a>
                <a href="../logout.php" class="text-white hover:text-blue-200">Logout</a>
                <!-- Profile Image -->
                <img
                    src="https://via.placeholder.com/40"
                    alt="Profile"
                    class="w-10 h-10 rounded-full object-cover cursor-pointer"
                    onclick="openprofileModal()"
                />
            </div>
        </div>
    </nav>


    
    <!-- Profile Modal -->
    <div id="profileModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
          <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
              Update Password
            </h3>
            <div class="mt-2">
              <form action="" method="POST">
                <div class="mb-4">
                  <label for="oldPassword" class="block text-gray-700 font-bold mb-2">Old Password</label>
                  <input type="password" id="oldPassword" name="oldPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                  <label for="newPassword" class="block text-gray-700 font-bold mb-2">New Password</label>
                  <input type="password" id="newPassword" name="newPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="mb-4">
                  <label for="confirmPassword" class="block text-gray-700 font-bold mb-2">Confirm New Password</label>
                  <input type="password" id="confirmPassword" name="confirmPassword" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500">
                </div>
                <div class="flex justify-end space-x-2">
                  <button type="button" onclick="closeprofileModal()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 border border-transparent rounded-md hover:bg-gray-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-gray-500">
                    Cancel
                  </button>
                  <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                    Update Password
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
</body>
<!-- Footer
<footer class="bg-gray-200 text-gray-600 py-4 mt-8">
    <div class="container mx-auto text-center">
        © 2024 Online Tutoring Platform. All rights reserved.
    </div>
</footer> -->

<script>
    function openprofileModal() {
        document.getElementById('profileModal').classList.remove('hidden');
    }

    function closeprofileModal() {
        document.getElementById('profileModal').classList.add('hidden');
    }
</script>
</html>