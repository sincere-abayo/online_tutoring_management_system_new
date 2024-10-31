<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Dashboard</title>
    <!-- Add Tailwind CSS CDN link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <!-- Sidebar -->
        <?php include 'sidebar.php'; ?>
        
        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col w-full">
            <!-- Top Bar -->
            <header class="bg-white shadow p-4 flex items-center justify-between">
                <button id="menu-toggle" class="text-gray-800 md:hidden focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800">Category Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-white bg-blue-600 px-4 py-2 rounded" onclick="window.location.href='../index.php'">Logout</button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-auto transition-all duration-300 w-full" id="main-content">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-bold">Categories</h2>
                        <button class="bg-green-500 text-white px-4 py-2 rounded" onclick="openAddCategoryModal()">Add New Category</button>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Category Name</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../user/db.php');
                                $query = "SELECT * FROM problem_categories";
                                $result = mysqli_query($conn, $query);

                                $count=1;
                                while ($row = mysqli_fetch_assoc($result)) {
                                    $id = $row['category_id'];
                                    $category_name = $row['category_name'];
                                    
                                    echo "<tr>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'>" . $count . "</td>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'>" . $category_name . "</td>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'>";
                                    echo "<button class='bg-red-500 text-white px-2 py-1 rounded' onclick='deleteCategory($id)'>Delete</button>";
                                    echo "</td>";
                                    echo "</tr>";
                                    $count++;
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
      <!-- Add Category Modal -->
      <div id="addCategoryModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center">
          <div class="p-5 border w-96 shadow-lg rounded-md bg-white">
              <div class="mt-3 text-center">
                  <h3 class="text-lg leading-6 font-medium text-gray-900">Add New Category</h3>
                  <form id="addCategoryForm" method="POST" action="category.php">
                      <div class="mt-2 px-7 py-3">
                          <input type="text" id="categoryName" name="categoryName" class="w-full px-3 py-2 border rounded-md" placeholder="Category Name" required>
                      </div>
                      <div class="items-center px-4 py-3">
                          <button type="submit" class="px-4 py-2 bg-blue-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300">
                              Add Category
                          </button>
                      </div>
                  </form>
                  <div class="items-center px-4 py-3">
                      <button id="closeCategoryModal" class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-300">
                          Close
                      </button>
                  </div>
              </div>
          </div>
      </div>

      <?php
      // add_category.php
      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          include('../user/db.php');
        
          $categoryName = mysqli_real_escape_string($conn, $_POST['categoryName']);
        
          $sql = "INSERT INTO problem_categories (category_name) VALUES ('$categoryName')";
        
          if (mysqli_query($conn, $sql)) {
              echo "<script>alert('New category added successfully'); window.location.href='category.php';</script>";
          } else {
              echo "<script>alert('Error: " . mysqli_error($conn) . "');</script>";
          }
        
          mysqli_close($conn);
      }

      // Delete category
      if (isset($_GET['delete'])) {
          include('../user/db.php');
          $id = mysqli_real_escape_string($conn, $_GET['delete']);
          $sql = "DELETE FROM problem_categories WHERE category_id = $id";
          if (mysqli_query($conn, $sql)) {
              echo "<script>alert('Category deleted successfully'); window.location.href='category.php';</script>";
          } else {
              echo "<script>alert('Error deleting category: " . mysqli_error($conn) . "');</script>";
          }
          mysqli_close($conn);
      }
      ?>

    <script src="sidebar.js"></script>
    <script>
        function openAddCategoryModal() {
            document.getElementById('addCategoryModal').classList.remove('hidden');
        }

        document.getElementById('closeCategoryModal').addEventListener('click', function() {
            document.getElementById('addCategoryModal').classList.add('hidden');
        });

        document.getElementById('addCategoryBtn').addEventListener('click', function() {
            var categoryName = document.getElementById('categoryName').value;
            // Add AJAX call to insert new category
            // After successful insertion, reload the page or update the table
        });

        function deleteCategory(id) {
            if (confirm('Are you sure you want to delete this category?')) {
                window.location.href = 'category.php?delete=' + id;
            }
        }
    </script>
</body>
</html>