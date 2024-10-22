

<?php
include 'user/db.php'; // Include the database connection
// include 'session.php'; // Include the session
// include 'navbar.php'; // Include the navigation bar
// Fetch problems 
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
    <!-- Include Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Optional: Include any custom CSS -->
    <link href="styles.css" rel="stylesheet" />

    
    <style>
        .hidden {
            display: none;
        }

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
    }
    .comments-section::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }
    .comments-section::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    </style>


  </head>
  <body class="bg-gray-100">
    <!-- Navigation -->
    <nav class="bg-blue-700 text-white py-4 border-b border-gray-500 fixed top-0 w-full ">
      <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold">Online Tutoring Platform</h1>
        <div class="space-x-4">
          <a href="login.php" class="text-white transition duration-300">Login</a>
          <a href="signup.php" class="text-white transition duration-300">Sign Up</a>
        </div>
      </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero bg-gray-800 text-white py-24 mt-14 relative">
      <div class="absolute inset-0 bg-cover bg-center opacity-30" style="background-image: url('image/5.webp');"></div>
      <div
        class="container mx-auto flex flex-col items-center justify-center text-center px-4 relative z-10"
      >
        <h2 class="text-4xl font-bold mb-4">
          Welcome to Our Online Tutoring Platform
        </h2>
        <p class="text-lg mb-8">
          Get expert help from tutors in various fields. Solve your academic
          problems effectively!
        </p>
        <a
          href="#how-it-works"
          class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-md text-lg font-semibold transition duration-300"
          >Learn How It Works</a
        >
      </div>
    </section>


    <!-- problems -->

    <section class="py-12 mt-14 px-16">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h3 class="text-lg font-bold"><?= htmlspecialchars($row['category_name']) ?></h3>
                    <p class="text-gray-700"><?= htmlspecialchars($row['description']) ?></p>
                    <p class="text-gray-600">Email: <?= htmlspecialchars($row['email']) ?></p>
                    <p class="text-gray-600">Contact: <?= htmlspecialchars($row['contact']) ?></p>
                    <p class="text-gray-600">Status: <?= htmlspecialchars($row['status']) ?></p>

                    <div class="mt-4 md:space-x-10">
                        <?php
                        $poster_name = $row['user_name'];
                        $problem = $row['description'];
                        $contact_number = preg_replace('/[^0-9]/', '', $row['contact']);
                        $message = urlencode("Hi " . $poster_name . ", I saw your problem: '" . $problem . "', and I would like to help you with it.");
                        $whatsappLink = "https://wa.me/" . $contact_number . "?text=" . $message;
                        ?>
                        
                       <a href="login.php "> <button class="text-blue-800 font-medium cursor-pointer">Add Comment</button> </a> 
                        <a href="<?= $whatsappLink ?>" target="_blank" class="text-green-600 font-medium hover:text-green-600">
                            <i class="fab fa-whatsapp fa-2x"></i> WhatsApp Me
                        </a>
                    </div>

                    <!-- Add Comment Section (Initially hidden) -->
                    <div id="add-comment-<?= $row['problem_id'] ?>" class="hidden mt-4">
                        <form action="add_comment.php" method="post" enctype="multipart/form-data">
                            <div class="flex items-center mb-2">
                                <label for="file-input-<?= $row['problem_id'] ?>" class="cursor-pointer">
                                    <i class="fa-solid fa-image text-gray-500 hover:text-blue-500 text-xl"></i>
                                    <input id="file-input-<?= $row['problem_id'] ?>" name="image" type="file" class="hidden"
                                        accept="image/*" />
                                </label>
                                <textarea name="comment" placeholder="Add your comment here..."
                                    class="w-full px-3 py-2 border rounded-md ml-2"></textarea>
                            </div>
                            <input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
                            <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md">Send</button>
                        </form>
                    </div>

                    <!-- Comments Section (Initially hidden) -->
                    <div id="comments-<?= $row['problem_id'] ?>" class="hidden mt-4">
                        <?php
                        // Fetch top-level comments
                        $comments_sql = "SELECT c.comment_id, c.comment, c.image_url, u.user_name, c.created_at FROM comments c 
                                         JOIN users u ON c.user_id = u.user_id 
                                         WHERE c.problem_id = " . $row['problem_id'] . " AND c.parent_comment_id IS NULL";
                        $comments_result = $conn->query($comments_sql);
                        ?>
                        <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
                        <?php while ($comment_row = $comments_result->fetch_assoc()): ?>
                            <div class="bg-gray-100 p-4 rounded-md mb-2">
                                <div class="flex justify-between">
                                    <div>
                                        <span
                                            class="font-bold text-gray-800"><?= htmlspecialchars($comment_row['user_name']) ?>:</span>
                                        <p class="text-gray-700"><?= htmlspecialchars($comment_row['comment']) ?></p>
                                        <?php if ($comment_row['image_url']): ?>
                                            <button onclick="toggleReply(<?= $comment_row['comment_id'] ?>)"
                                                class="text-blue-500 hover:underline text-sm mt-2">View Reply</button>
                                        <?php endif; ?>
                                        <!-- Reply Button -->
                                        <button onclick="toggleReplyForm(<?= $comment_row['comment_id'] ?>)"
                                            class="text-blue-500 hover:underline text-sm mt-2">Reply</button>
                                            <button onclick="toggleReply(<?= $comment_row['comment_id'] ?>)"
                                            class="text-blue-500 hover:underline text-sm mt-2">View Reply</button>

                                        <!-- Reply Form (Initially hidden) -->
                                        <div id="reply-form-<?= $comment_row['comment_id'] ?>" class="hidden mt-2">
                                            <form action="add_comment.php" method="post" enctype="multipart/form-data">
                                                <div class="flex items-center mb-2">
                                                    <label for="file-input-reply-<?= $comment_row['comment_id'] ?>"
                                                        class="cursor-pointer">
                                                        <i
                                                            class="fa-solid fa-image text-gray-500 hover:text-blue-500 text-xl"></i>
                                                        <input id="file-input-reply-<?= $comment_row['comment_id'] ?>"
                                                            name="image" type="file" class="hidden" accept="image/*" />
                                                    </label>
                                                    <textarea name="comment" placeholder="Write a reply..."
                                                        class="w-full px-3 py-2 border rounded-md ml-2"></textarea>
                                                </div>
                                                <input type="hidden" name="parent_comment_id"
                                                    value="<?= $comment_row['comment_id'] ?>">
                                                <input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
                                                <button type="submit"
                                                    class="px-4 py-2 text-white bg-blue-500 rounded-md">Send</button>
                                            </form>
                                        </div>

                                        <!-- Display Replies Hierarchically -->
                                        <?php
                                        // Fetch replies for the specific comment
                                        $replies_sql = "SELECT r.comment_id, r.comment, r.image_url, u.user_name, r.created_at 
                                        FROM comments r 
                                        JOIN users u ON r.user_id = u.user_id 
                                        WHERE r.parent_comment_id = " . $comment_row['comment_id'];

                                        $replies_result = $conn->query($replies_sql);
                                        ?>
                                        <?php while ($reply_row = $replies_result->fetch_assoc()): ?>
                                                <div id="replies-<?= $comment_row['comment_id'] ?>" class="hidden mt-2">
                                                <div class="flex justify-between">
                                                    <div>
                                                        <span
                                                            class="font-bold text-gray-800"><?= htmlspecialchars($reply_row['user_name']) ?>:</span>
                                                        <p class="text-gray-700"><?= htmlspecialchars($reply_row['comment']) ?></p>
                                                        <?php if ($reply_row['image_url']): ?>
                                                            <button onclick="openImageModal(<?= $reply_row['comment_id'] ?>, 'reply')"
                                                                class="text-blue-500 hover:underline text-sm mt-2">View Image</button>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class="text-gray-500 text-sm">
                                                        <?= date('F j, Y, g:i a', strtotime($reply_row['created_at'])) ?>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endwhile; ?>
                                    </div>
                                    <div class="text-gray-500 text-sm">
                                        <?= date('F j, Y, g:i a', strtotime($comment_row['created_at'])) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>

        <div class="text-center mt-10">
            <a href="login.php" class="text-white bg-blue-800 px-10 py-1 rounded-lg hover:bg-white hover:border-2 hover:border-blue-800 hover:text-blue-800" >View more...</a>
        </div>

        </section>


    <!-- Modal Structure -->

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20  bg-white">

    <div class="text-center mb-5">
        <h1 class="font-medium text-4xl">Services</h1>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-5 md:mx-16 mx-5">

    <div class="bg-white rounded-lg shadow-md shadow-blue-400 overflow-hidden border-2 border-blue-800 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500 hover:scale-105">

        <div class="relative group">
           <img src="image/post.avif" alt="Image 1 description" class="w-full h-60 object-cover">
             <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity duration-300 flex items-center justify-center">
              <p class="text-white text-xl font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Post your Problem</p>
            </div>
        </div>
        <div class="px-6 pb-2">
            <h1 class="text-right text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">01</h1>
        </div>
        <h1 class="text-center text-xl font-bold">POST YOUR PROBLEM </h1>
           <p class="p-4 text-gray-600"> Describe your problem or question in detail. Attach relevant files
           if necessary.</p>
    </div>

    <div class="bg-white rounded-lg shadow-md shadow-blue-400 overflow-hidden border-2 border-blue-800 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500 hover:scale-105">
       <div class="relative group">
           <img src="image/res.png" alt="Image 1 description" class="w-full h-60 object-cover">
             <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity duration-300 flex items-center justify-center">
              <p class="text-white text-xl font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Get Expert Responses</p>
            </div>
        </div>
        <div class="px-6 pb-2">
            <h1 class="text-right text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">02</h1>
        </div>
        <h1 class="text-center text-xl font-bold">GET EXPERT RESPONSE</h1>
           <p class="p-4 text-gray-600">Our experts will review your problem and provide detailed
           solutions or explanations.</p>
       </div>


       <div class="bg-white rounded-lg shadow-md shadow-blue-400 overflow-hidden border-2 border-blue-800 transition-all duration-300 hover:shadow-lg hover:shadow-blue-500 hover:scale-105">
             <div class="relative group">
           <img src="image/6.jpg" alt="Image 1 description" class="w-full h-60 object-cover">
             <div class="absolute inset-0 bg-black opacity-0 group-hover:opacity-50 transition-opacity duration-300 flex items-center justify-center">
              <p class="text-white text-xl font-medium opacity-0 group-hover:opacity-100 transition-opacity duration-300">Schedule Appointments</p>
            </div>
        </div>
        <div class="px-6 pb-2">
            <h1 class="text-right text-8xl font-bold bg-gradient-to-r from-blue-700 via-indigo-500 to-green-400 text-transparent bg-clip-text">03</h1>
        </div>
        <h1 class="text-center text-xl font-bold">SCHEDULE APPOINTMENTS</h1>
           <p class="p-4 text-gray-600">  Our experts will review your problem and provide detailed
           solutions or explanations.</p>
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
