    <?php
    include 'user/db.php';
    $sort = $_GET['sort'] ?? 'all';
    $sql = "SELECT p.problem_id, p.description, p.email, p.contact, p.status, u.user_name, pc.category_name 
            FROM problems p
            JOIN users u ON p.user_id = u.user_id
            JOIN problem_categories pc ON p.category_id = pc.category_id";

    if ($sort == 'pending') {
        $sql .= " WHERE p.status = 'pending'";
    } elseif ($sort == 'solved') {
        $sql .= " WHERE p.status = 'solved'";
    }

    $result = $conn->query($sql);

    function time_ago($timestamp) {
        $time_ago = strtotime($timestamp);
        $current_time = time();
        $time_difference = $current_time - $time_ago;
        $seconds = $time_difference;
        $minutes = round($seconds / 60);
        $hours = round($seconds / 3600);
        $days = round($seconds / 86400);
        $weeks = round($seconds / 604800);
        $months = round($seconds / 2629440);
        $years = round($seconds / 31553280);

        if ($seconds <= 60) {
            return "Just now";
        } else if ($minutes <= 60) {
            return ($minutes == 1) ? "1 minute ago" : "$minutes minutes ago";
        } else if ($hours <= 24) {
            return ($hours == 1) ? "1 hour ago" : "$hours hours ago";
        } else if ($days <= 7) {
            return ($days == 1) ? "1 day ago" : "$days days ago";
        } else if ($weeks <= 4.3) {
            return ($weeks == 1) ? "1 week ago" : "$weeks weeks ago";
        } else if ($months <= 12) {
            return ($months == 1) ? "1 month ago" : "$months months ago";
        } else {
            return ($years == 1) ? "1 year ago" : "$years years ago";
        }
    }

    function countReplies($conn, $comment_id) {
        $count_sql = "SELECT COUNT(*) as count FROM comments WHERE parent_comment_id = $comment_id";
        $count_result = $conn->query($count_sql);
        return $count_result->fetch_assoc()['count'];
    }
    ?>

    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>Online Tutoring Platform</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="styles.css" rel="stylesheet" />
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
        <style>
            .hidden { display: none; }
            .reply {
                margin-left: 30px;
                background-color: #f9f9f9;
                padding: 10px;
                border-radius: 5px;
            }
            .comments-section {
                max-height: 400px;
                overflow-y: auto;
                padding-right: 10px;
            }
            .comments-section::-webkit-scrollbar {
                width: 8px;
            }
            .comments-section::-webkit-scrollbar-track {
                background: #f1f1f1;
                border-radius: 4px;
            }
            .comments-section::-webkit-scrollbar-thumb {
                background: #888;
                border-radius: 4px;
            }
            .comments-section::-webkit-scrollbar-thumb:hover {
                background: #555;
            }
            .nav-link {
                position: relative;
                transition: all 0.3s ease;
            }
            .nav-link::after {
                content: '';
                position: absolute;
                width: 0;
                height: 2px;
                bottom: -4px;
                left: 0;
                background-color: white;
                transition: width 0.3s ease;
            }
            .nav-link:hover::after {
                width: 100%;
            }
            .problem-card {
                transition: transform 0.3s ease;
            }
            .problem-card:hover {
                transform: translateY(-5px);
            }
            .service-card {
                transition: all 0.3s ease;
            }
            .service-card:hover {
                transform: translateY(-10px);
                box-shadow: 0 10px 20px rgba(0,0,0,0.2);
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <nav class="bg-gradient-to-r from-blue-800 to-blue-600 text-white py-4 border-b border-gray-500 fixed top-0 w-full z-50">
            <div class="container mx-auto flex justify-between items-center px-4">
                <h1 class="text-2xl font-bold flex items-center">
                    <i class="fas fa-graduation-cap mr-2"></i>
                    Online Tutoring Platform
                </h1>
                <div class="space-x-6">
                    <a href="login.php" class="nav-link text-white hover:text-gray-200 transition duration-300">
                        <i class="fas fa-sign-in-alt mr-1"></i> Login
                    </a>
                    <a href="signup.php" class="nav-link bg-white text-blue-800 px-4 py-2 rounded-lg hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-user-plus mr-1"></i> Sign Up
                    </a>
                </div>
            </div>
        </nav>

        <section class="hero bg-gray-800 text-white py-32 mt-14 relative overflow-hidden">
            <div class="absolute inset-0 bg-cover bg-center opacity-30 transform scale-105" style="background-image: url('image/5.webp');"></div>
            <div class="container mx-auto flex flex-col items-center justify-center text-center px-4 relative z-10">
                <h2 class="text-5xl font-bold mb-6 leading-tight">
                    Welcome to Our Online Tutoring Platform
                </h2>
                <p class="text-xl mb-8 max-w-2xl">
                    Get expert help from qualified tutors in various fields. Solve your academic problems effectively and achieve your learning goals!
                </p>
                <a href="#how-it-works" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-4 rounded-lg text-lg font-semibold transition duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-arrow-right mr-2"></i>Learn How It Works
                </a>
            </div>
        </section>

        <section class="py-16 px-4 md:px-16">
            <div class="container mx-auto">
                <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Recent Problems</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <div class="problem-card bg-white shadow-lg rounded-xl p-6 border border-gray-200">
                            <div class="flex items-center mb-4">
                                <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">
                                    <?= htmlspecialchars($row['category_name']) ?>
                                </span>
                                <span class="ml-auto text-sm text-gray-500">
                                    Status: <?= htmlspecialchars($row['status']) ?>
                                </span>
                            </div>
                            <p class="text-gray-700 mb-4"><?= htmlspecialchars($row['description']) ?></p>
                            <div class="space-y-2 text-sm text-gray-600">
                                <p><i class="far fa-envelope mr-2"></i><?= htmlspecialchars($row['email']) ?></p>
                                <p><i class="fas fa-phone mr-2"></i><?= htmlspecialchars($row['contact']) ?></p>
                            </div>
                            <div class="mt-6 flex justify-between items-center">
                                <a href="login.php" class="text-blue-600 hover:text-blue-800 font-medium flex items-center">
                                    <i class="far fa-comment mr-1"></i> Add Comment
                                </a>
                                <?php
                                $poster_name = $row['user_name'];
                                $problem = $row['description'];
                                $contact_number = preg_replace('/[^0-9]/', '', $row['contact']);
                                $message = urlencode("Hi " . $poster_name . ", I saw your problem: '" . $problem . "', and I would like to help you with it.");
                                $whatsappLink = "https://wa.me/" . $contact_number . "?text=" . $message;
                                ?>
                                <a href="<?= $whatsappLink ?>" target="_blank" class="text-green-600 hover:text-green-700 font-medium flex items-center">
                                    <i class="fab fa-whatsapp mr-1"></i> WhatsApp
                                </a>
                            </div>
                        </div>
                    <?php endwhile; ?>
                </div>
                <div class="text-center mt-12">
                    <a href="login.php" class="inline-block bg-blue-800 text-white px-8 py-3 rounded-lg hover:bg-blue-900 transition duration-300 transform hover:scale-105">
                        View More Problems
                    </a>
                </div>
            </div>
        </section>

        <section id="how-it-works" class="py-20 bg-gray-50">
            <div class="container mx-auto">
                <h2 class="text-4xl font-bold text-center mb-16 text-gray-800">Our Services</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 px-4 md:px-16">
                    <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative group h-60">
                            <img src="image/post.avif" alt="Post your Problem" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <p class="text-white text-xl font-medium transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    Post your Problem
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-right mb-4">
                                <span class="text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">01</span>
                            </div>
                            <h3 class="text-2xl font-bold text-center mb-4">POST YOUR PROBLEM</h3>
                            <p class="text-gray-600 text-center">
                                Describe your academic challenges in detail and attach relevant materials for comprehensive assistance.
                            </p>
                        </div>
                    </div>

                    <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative group h-60">
                            <img src="image/res.png" alt="Expert Response" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <p class="text-white text-xl font-medium transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    Get Expert Solutions
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-right mb-4">
                                <span class="text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">02</span>
                            </div>
                            <h3 class="text-2xl font-bold text-center mb-4">GET EXPERT RESPONSE</h3>
                            <p class="text-gray-600 text-center">
                                Receive detailed solutions and explanations from our qualified experts in your field.
                            </p>
                        </div>
                    </div>

                    <div class="service-card bg-white rounded-xl shadow-lg overflow-hidden">
                        <div class="relative group h-60">
                            <img src="image/6.jpg" alt="Schedule Appointments" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black bg-opacity-50 opacity-0 group-hover:opacity-100 transition-all duration-300 flex items-center justify-center">
                                <p class="text-white text-xl font-medium transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                                    Book Your Session
                                </p>
                            </div>
                        </div>
                        <div class="p-6">
                            <div class="text-right mb-4">
                                <span class="text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">03</span>
                            </div>
                            <h3 class="text-2xl font-bold text-center mb-4">SCHEDULE APPOINTMENTS</h3>
                            <p class="text-gray-600 text-center">
                                Book one-on-one sessions with tutors for personalized learning experiences.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

 <!-- Footer -->
 <footer class="bg-gray-800 text-white py-4">
      <div class="container mx-auto text-center">
        &copy; 2024 Online Tutoring Platform. All rights reserved.
      </div>
    </footer>

    </body>
    </html>