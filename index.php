

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






    <div class=" md:mx-10 mx-5 my-8 px-4">
        <h2 class="text-2xl font-bold mb-6">Posted Problems</h2>

        <div class=" ">
        <form action="" method="get" class="mb-6">
            <label for="sort" class="block font-bold mb-2 ">Sort By</label>
            <select id="sort" name="sort" class="px-3 py-2 w-96 border focus:outline-none  rounded-md border-2" onchange="this.form.submit()">
                <option value="all" <?= $sort == 'all' ? 'selected' : '' ?>>All Problems</option>
                <option value="pending" <?= $sort == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="solved" <?= $sort == 'solved' ? 'selected' : '' ?>>Solved</option>
            </select>
        </form>
        </div>

       <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white shadow-md rounded-lg p-4">
            <h3 class="text-lg  text-blue-800 font-bold"><?= htmlspecialchars($row['category_name']) ?></h3>
            <p class="text-black"><?= htmlspecialchars($row['description']) ?></p>
            <p class="text-black"> <span class="font-medium" >  Email:  </span> <?= htmlspecialchars($row['email']) ?></p>
            <p class="text-black"> <span class="font-medium" >  Contact: </span>  <?= htmlspecialchars($row['contact']) ?></p>
            <p class="text-black"> <span class="font-medium" >  Status: </span>  <?= htmlspecialchars($row['status']) ?></p>

            <div class="mt-4 flex flex-wrap items-center gap-4">
                <?php
                $poster_name = $row['user_name'];
                $problem = $row['description'];
                $contact_number = preg_replace('/[^0-9]/', '', $row['contact']);
                $message = urlencode("Hi " . $poster_name . ", I saw your problem: '" . $problem . "', and I would like to help you with it.");
                $whatsappLink = "https://wa.me/" . $contact_number . "?text=" . $message;

                // Count the number of comments for this problem
                $comments_count_sql = "SELECT COUNT(*) as count FROM comments WHERE problem_id = " . $row['problem_id'] . " AND parent_comment_id IS NULL";
                $comments_count_result = $conn->query($comments_count_sql);
                $comments_count = $comments_count_result->fetch_assoc()['count'];

                // Count the number of images for this problem
                $images_count_sql = "SELECT COUNT(*) as count FROM problem_images WHERE problem_id = " . $row['problem_id'];
                $images_count_result = $conn->query($images_count_sql);
                $images_count = $images_count_result->fetch_assoc()['count'];
                ?>
             

             
              <a href="login.php">  <button  class="px-4 py-2 bg-purple-100 text-purple-700 rounded-full hover:bg-purple-200 transition duration-300">
                    <i class="fas fa-plus mr-2"></i>Add Comment
                </button> </a>
                <a href="<?= $whatsappLink ?>" target="_blank" class="px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition duration-300">
                    <i class="fab fa-whatsapp mr-2"></i>WhatsApp Me
                </a>
            </div>
            <!-- Add Comment Section (Initially hidden) -->
            <div id="add-comment-<?= $row['problem_id'] ?>" class="hidden mt-4"> 
    <form id="commentForm_<?= $row['problem_id']; ?>" onsubmit="submitComment(event, 'commentForm_<?= $row['problem_id']; ?>')" enctype="multipart/form-data">
        <div class="flex items-center mb-2">
            <label for="file-input-<?= $row['problem_id'] ?>" class="cursor-pointer">
                <i class="fa-solid fa-image text-gray-500 hover:text-blue-500 text-xl"></i>
                <input id="file-input-<?= $row['problem_id'] ?>" name="image" type="file" class="hidden" accept="image/*" />
            </label>
            <textarea name="comment" placeholder="Add your comment here..." class="w-full px-3 py-2 border rounded-md ml-2"></textarea>
        </div>
        <input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
        <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md">Send</button>
    </form>
    <div id="commentMessage_<?= $row['problem_id'] ?>" class="mt-2 text-sm"></div> <!-- This div is for displaying messages -->
</div>

              <!-- Comments Section (Initially hidden) -->
              <div id="comments-<?= $row['problem_id'] ?>" class="hidden mt-4">
              <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
              <div class="comments-section mt-4">
                  <?php
                  // Fetch top-level comments
                  $comments_sql = "SELECT c.comment_id, c.comment, c.image_url, u.user_name, u.profile_image, c.created_at, u.user_id FROM comments c 
                                 JOIN users u ON c.user_id = u.user_id 
                                 WHERE c.problem_id = " . $row['problem_id'] . " AND c.parent_comment_id IS NULL";
                  $comments_result = $conn->query($comments_sql);
                
                  
                  ?>
                  <?php while ($comment_row = $comments_result->fetch_assoc()): ?>
    <div class="comment bg-gray-100 p-4 rounded-md mb-4 shadow-sm">
        <div class="flex items-start">
            <img src="../image/<?= htmlspecialchars($comment_row['profile_image']) ?>" alt="Avatar" class="w-10 h-10 rounded-full mr-4">
            <div class="flex-grow">
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-900"><?= htmlspecialchars($comment_row['user_name']) ?></span>
                    <span class="text-gray-500 text-xs"><?= date('Y-m-d H:i:s', strtotime($comment_row['created_at'])) ?></span>
                </div>
                <p class="text-gray-700 mb-3"><?= htmlspecialchars($comment_row['comment']) ?></p>
                
                <div class="flex items-center space-x-4 flex-wrap">
                    <?php if (!empty($comment_row['image_url'])): ?>
                    <button onclick="openImageModal1('<?= $comment_row['image_url'] ?>')" class="text-blue-500 hover:text-blue-600 transition duration-300 ease-in-out flex items-center">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        View Image
                    </button>
                    
                    <?php endif; ?>
                    
                    <!-- <form method="post" class="inline"> -->
                        <?php 
                        $comments_count_sql = "SELECT * FROM likes WHERE comment_id = " . $comment_row['comment_id'];
                        $comments_count_result = $conn->query($comments_count_sql);
                        $likes = $comments_count_result->num_rows;
                        $user_liked = $conn->query("SELECT * FROM likes WHERE comment_id = {$comment_row['comment_id']} AND user_id = {$_SESSION['user_id']}")->num_rows > 0;
$like_color = $user_liked ? 'text-blue-500' : 'text-gray-600';
                    
                    ?>
                        <button onclick="likeComment(<?= $comment_row['comment_id'] ?>)" class="like-button <?= $like_color ?> hover:text-blue-500 transition duration-300 ease-in-out flex items-center">
    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
    </svg>
    <?php if ($likes > 0): ?><span class="font-semibold" id="comment<?php echo $comment_row['comment_id'] ?>"><?php echo $likes ?></span><?php endif; ?>
    <span class="ml-1"><?php echo $likes === 1 ? 'Like' : 'Likes' ?></span>
</button>
                    <!-- </form> -->
                    
                    <button onclick="toggleReplyForm(<?= $comment_row['comment_id'] ?>)" class="text-blue-500 hover:text-blue-600 transition duration-300 ease-in-out text-sm">Reply</button>
                    
                    <span class="text-gray-500 text-xs">• <?= time_ago($comment_row['created_at']) ?> •</span>
                </div>

                <div class="mt-4">
                    <div id="reply-form-<?= $comment_row['comment_id'] ?>" class="hidden mt-4 bg-gray-50 p-4 rounded-lg shadow-md">
                    <form id="replyForm_<?= $comment_row['comment_id']; ?>" onsubmit="submitReply(event, 'replyForm_<?= $comment_row['comment_id']; ?>')" enctype="multipart/form-data" class="space-y-3">
    <div class="flex items-start space-x-2">
        <label for="file-input-reply-<?= $comment_row['comment_id'] ?>" class="cursor-pointer bg-white p-2 rounded-full hover:bg-gray-100 transition duration-300">
            <i class="fa-solid fa-image text-gray-500 hover:text-blue-500 text-xl"></i>
            <input id="file-input-reply-<?= $comment_row['comment_id'] ?>" name="image" type="file" class="hidden" accept="image/*" />
        </label>
        <textarea name="comment" placeholder="Write a reply..." class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-300"></textarea>
    </div>
    <input type="hidden" name="parent_comment_id" value="<?= $comment_row['comment_id'] ?>">
    <input type="hidden" name="problem_id" value="<?= $row['problem_id'] ?>">
    <button type="submit" class="px-4 py-2 text-white bg-blue-500 rounded-md hover:bg-blue-600 transition duration-300 ease-in-out">Send Reply</button>
</form>
<div id="replyMessage_<?= $comment_row['comment_id']; ?>" class="mt-2 text-sm"></div> <!-- Div for displaying success/error messages -->

                    </div>
                    
                    <?php
                    $reply_count = countReplies($conn, $comment_row['comment_id']);
                    if ($reply_count > 0):
                    ?>
                    <button onclick="toggleReplies(<?= $comment_row['comment_id'] ?>)" class="mt-2 text-blue-500 hover:text-blue-600 transition duration-300 ease-in-out">
                        View Replies (<span id="replays_<?php echo $comment_row['comment_id']?>"><?= $reply_count ?></span>)
                    </button>
                    <?php endif; ?>
                    
                    <div id="replies-<?= $comment_row['comment_id'] ?>" class="replies ml-10 mt-3 hidden">
                        <?php
                        $replies_sql = "SELECT r.comment_id, r.comment, r.image_url, u.user_name, u.profile_image, r.created_at, u.user_id 
                                        FROM comments r 
                                        JOIN users u ON r.user_id = u.user_id 
                                        WHERE r.parent_comment_id = " . $comment_row['comment_id'];
                        $replies_result = $conn->query($replies_sql);
                        while ($reply_row = $replies_result->fetch_assoc()):
                        ?>
                        <div class="reply bg-gray-50 p-3 rounded-md mb-2">
                            <div class="flex items-start">
                                <img src="../image/<?= htmlspecialchars($reply_row['profile_image']) ?>" alt="Avatar" class="w-8 h-8 rounded-full mr-3">
                                <div class="flex-grow">
                                    <div class="flex justify-between items-center mb-1">
                                        <span class="font-semibold text-gray-800"><?= htmlspecialchars($reply_row['user_name']) ?></span>
                                        <span class="text-gray-500 text-xs"><?= date('Y-m-d H:i:s', strtotime($reply_row['created_at'])) ?></span>
                                    </div>
                                    <p class="text-gray-700 text-sm"><?= htmlspecialchars($reply_row['comment']) ?></p>
                                    <div class="mt-3 flex items-center space-x-4">
                                    <?php 
                        $comments_count_sql = "SELECT * FROM likes WHERE comment_id = " . $reply_row['comment_id'];
                        $comments_count_result = $conn->query($comments_count_sql);
                        $likes = $comments_count_result->num_rows;
                        $user_liked = $conn->query("SELECT * FROM likes WHERE comment_id = {$reply_row['comment_id']} AND user_id = {$_SESSION['user_id']}")->num_rows > 0;
$like_color = $user_liked ? 'text-blue-500' : 'text-gray-600';
                    
                    ?>  
                                    <button onclick="likeComment(<?= $reply_row['comment_id'] ?>)" class="like-button <?= $like_color ?> hover:text-blue-500 transition duration-300 ease-in-out flex items-center">
             <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
    </svg>
    <?php if ($likes > 0): ?><span class="font-semibold" id="comment<?php echo $reply_row['comment_id'] ?>"><?php echo $likes ?></span><?php endif; ?>
    <span class="ml-1"><?php echo $likes === 1 ? 'Like' : 'Likes' ?></span>
</button>
                                        <?php if (!empty($reply_row['image_url'])): ?>
                                            <button onclick="openImageModal1('<?= $reply_row['image_url'] ?>')" class="text-blue-500 hover:text-blue-600 transition duration-300 ease-in-out flex items-center">
                                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                View Image
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>

                      
                  </div>
              </div>
          </div>
    <?php endwhile; ?>
</div>


    </div>
                                        </div>
                                        </div>
                              </div>
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
