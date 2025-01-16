<?php
// connection of the database
include('../user/db.php');
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add Tailwind CSS CDN link -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <!-- Add Chart.js CDN link -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <h1 class="text-xl font-bold text-gray-800">Dashboard</h1>
                <div class="flex items-center space-x-4">
                    <button class="text-white bg-blue-600 px-4 py-2 rounded" onclick="window.location.href='../logout.php'">Logout</button>
                </div>
            </header>

            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-auto transition-all duration-300 w-full" id="main-content">
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-2xl font-bold mb-4">Welcome to your Dashboard</h2>
                    <p>This is your main dashboard where you can manage your tasks, view reports, and analyze data.</p>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">

                        <div class="bg-blue-500 text-white rounded-lg p-6 shadow relative overflow-hidden">
                            <h3 class="text-lg font-bold">Total users</h3>
                            <p class="mt-2 text-3xl"><?php
                                $query = "SELECT COUNT(*) as total FROM users";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['total'];
                            ?></p>
                            <div class="absolute bottom-0 right-0 opacity-30">
                                <i class="fa fa-users text-8xl"></i>
                            </div>
                        </div>

                   

                        <div class="bg-blue-500 text-white rounded-lg p-6 shadow relative overflow-hidden">
                            <h3 class="text-lg font-bold">All Problems</h3>
                            <p class="mt-2 text-3xl"><?php
                                $query = "SELECT COUNT(*) as total FROM problems";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['total'];
                            ?></p>

                            <div class="absolute bottom-0 right-0 opacity-30">
                            <i class="fa-regular fa-circle-question text-8xl"></i>
                            </div>
                        </div>
                        
                        <div class="bg-yellow-500 text-white rounded-lg p-6 shadow relative overflow-hidden">
                            <h3 class="text-lg font-bold">Solved Problems</h3>
                            <p class="mt-2 text-3xl"><?php
                                $query = "SELECT COUNT(*) as total FROM problems WHERE status = 'solved'";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['total'];
                            ?></p>

                            <div class="absolute bottom-0 right-0 opacity-30">
                            <i class="fa-solid fa-spell-check text-8xl"></i>
                            </div>
                        </div>
                        <div class="bg-green-500 text-white rounded-lg p-6 shadow relative overflow-hidden">
                            <h3 class="text-lg font-bold">Pending Problems</h3>
                            <p class="mt-2 text-3xl"><?php
                                $query = "SELECT COUNT(*) as total FROM problems WHERE status = 'pending'";
                                $result = mysqli_query($conn, $query);
                                $row = mysqli_fetch_assoc($result);
                                echo $row['total'];
                            ?></p>

                            <div class="absolute bottom-0 right-0 opacity-30">
                            <i class="fa-solid fa-spinner text-8xl"></i>
                            </div>
                        </div>
                       
                    </div>
                    <!-- Add chart section -->
                    <div class="mt-8">
                        <h3 class="text-xl font-bold mb-4">Problem Solving Percentage</h3>
                        <div class="w-64 h-64 mx-auto">
                            <canvas id="problemSolvingChart"></canvas>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="sidebar.js"></script>
    <script>
        // Chart.js code
        document.addEventListener('DOMContentLoaded', function() {
            var ctx = document.getElementById('problemSolvingChart').getContext('2d');
            <?php
                $solvedQuery = "SELECT COUNT(*) as total FROM problems WHERE status = 'solved'";
                $pendingQuery = "SELECT COUNT(*) as total FROM problems WHERE status = 'pending'";
                
                $solvedResult = mysqli_query($conn, $solvedQuery);
                $pendingResult = mysqli_query($conn, $pendingQuery);
                
                $solvedProblems = mysqli_fetch_assoc($solvedResult)['total'];
                $pendingProblems = mysqli_fetch_assoc($pendingResult)['total'];
                
                $totalProblems = $solvedProblems + $pendingProblems;
                $solvedPercentage = ($solvedProblems / $totalProblems) * 100;
                $pendingPercentage = ($pendingProblems / $totalProblems) * 100;
            ?>
            var solvedProblems = <?php echo $solvedProblems; ?>;
            var pendingProblems = <?php echo $pendingProblems; ?>;
            var solvedPercentage = <?php echo $solvedPercentage; ?>;
            var pendingPercentage = <?php echo $pendingPercentage; ?>;

            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Solved Problems', 'Pending Problems'],
                    datasets: [{
                        data: [solvedPercentage, pendingPercentage],
                        backgroundColor: [
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)'
                        ],
                        borderColor: [
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        title: {
                            display: true,
                            text: 'Problem Solving Percentage'
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    var label = context.label || '';
                                    var value = context.parsed || 0;
                                    return label + ': ' + value.toFixed(2) + '%';
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>




    
</body>
</html>
