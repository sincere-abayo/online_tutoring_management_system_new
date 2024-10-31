<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users Dashboard</title>
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
                <h1 class="text-xl font-bold text-gray-800">Users Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-white bg-blue-600 px-4 py-2 rounded" onclick="window.location.href='../index.php'">Logout</button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-auto transition-all duration-300 w-full" id="main-content">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold mb-4">Users Table</h2>
                    <div class="overflow-x-auto">
                        <table class="min-w-full bg-white">
                            <thead>
                                <tr>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Profile Image</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Username</th>
                                    <th class="py-2 px-4 border-b border-gray-200 bg-gray-50 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include('../user/db.php');
                                $rows_per_page = 10;
                                $page = isset($_GET['page']) ? $_GET['page'] : 1;
                                $start = ($page - 1) * $rows_per_page;

                                $query = "SELECT * FROM users LIMIT $start, $rows_per_page";
                                $result = mysqli_query($conn, $query);

                                while ($row = mysqli_fetch_assoc($result)) {
                                    $username = $row['user_name'];
                                    $email = $row['email'];
                                    $image = $row['profile_image'];
                                    
                                    echo "<tr>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'><img src='../image/" . $image . "' alt='Profile Image' class='w-10 h-10 rounded-full'></td>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'>" . $username . "</td>";
                                    echo "<td class='py-2 px-4 border-b border-gray-200'>" . $email . "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination -->
                    <div class="mt-4 flex justify-center">
                        <?php
                        $query = "SELECT COUNT(*) as total FROM users";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_assoc($result);
                        $total_pages = ceil($row['total'] / $rows_per_page);

                        if ($total_pages > 1) {
                            echo '<div class="flex space-x-2">';
                            for ($i = 1; $i <= $total_pages; $i++) {
                                $active = $i == $page ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700';
                                echo "<a href='?page=$i' class='px-3 py-1 rounded $active'>$i</a>";
                            }
                            echo '</div>';
                        }
                        ?>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="sidebar.js"></script>
</body>
</html>