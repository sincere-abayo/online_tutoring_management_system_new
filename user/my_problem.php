<?php
include 'db.php'; // Include the database connection
include 'session.php'; // Include the session
include 'navbar.php'; // Include the navigation bar

// Fetch problems 
$sort = $_GET['sort'] ?? 'all';
$user_id = $_SESSION['user_id'];
$sql = "SELECT p.problem_id, p.description, p.email, p.contact, p.status, u.user_name, pc.category_name, pc.category_id 
        FROM problems p
        JOIN users u ON p.user_id = u.user_id
        JOIN problem_categories pc ON p.category_id = pc.category_id
        WHERE p.user_id = $user_id";

if ($sort == 'pending') {
    $sql .= " AND p.status = 'pending'";
} elseif ($sort == 'solved') {
    $sql .= " AND p.status = 'solved'";
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
// return category
$problem_category_sql= "SELECT * from problem_categories";
$problem_category_result = mysqli_query($conn, $problem_category_sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expert Dashboard - Online Tutoring Platform</title>
    <script src="../js/tailwind.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

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

<body>
    <div class=" md:mx-10 mx-5 my-8 px-4">

    <div class=" py-1">
        <h2 class="text-2xl font-bold mb-6 text-center bg-white p-3">Problems You Post</h2>


    <div class="flex justify-between items-center ">
        <form action="" method="get" class="mb-6">
            <label for="sort" class="block font-bold mb-2">Sort By</label>
            <select id="sort" name="sort" class="px-3 py-2 w-96 border focus:outline-none  rounded-md" onchange="this.form.submit()">
                <option value="all" <?= $sort == 'all' ? 'selected' : '' ?>>All Problems</option>
                <option value="pending" <?= $sort == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="solved" <?= $sort == 'solved' ? 'selected' : '' ?>>Solved</option>
            </select>
            
        </form>

        <div class="">

        <a href="problem.php" class="text-white bg-blue-800 px-5 py-1.5 rounded-md">Post Problem</a>
     </div>
    </div>

        </div>
    

       <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-5">
        <?php if (mysqli_num_rows($result) > 0): ?>
    <?php while ($row = $result->fetch_assoc()): ?>
        <div class="bg-white shadow-md rounded-lg p-4">

            <p class="text-green-500 font-semibold mb-4 success_<?php echo $row['problem_id'] ?>">
               
            </p>

            <h3 class="text-lg  text-blue-800 font-bold"><?= htmlspecialchars($row['category_name']) ?></h3>
            <p class="text-black"><?= htmlspecialchars($row['description']) ?></p>
            <p class="text-black"> <span class="font-bold" >   Email: </span> <?= htmlspecialchars($row['email']) ?></p>
            <p class="text-black"> <span class="font-bold" >   Contact: </span> <?= htmlspecialchars($row['contact']) ?></p>
                    <p class="text-black"> <span class="font-bold" >   Status: </span> <?= htmlspecialchars($row['status']) ?></p>
                    <?php if ($row['status'] !== 'solved'): ?>
                        <button onclick="confirmSolvedProblem(<?= $row['problem_id'] ?>)" class="mt-2 px-4 py-2 bg-green-500 text-white rounded-full hover:bg-green-600 transition duration-300">
                            <i class="fas fa-check mr-2"></i>Confirm Solved
                        </button>
                    <?php endif; ?>

            <div class="mt-4 flex flex-wrap items-center gap-4">
    <?php
    $poster_name = $row['user_name'];
    $problem = $row['description'];
    $contact_number = preg_replace('/[^0-9]/', '', $row['contact']);
    $message = urlencode("Hi " . $poster_name . ", I saw your problem: '" . $problem . "', and I would like to help you with it.");
    $whatsappLink = "https://wa.me/" . $contact_number . "?text=" . $message;
    
    // Count comments
    $comments_count_sql = "SELECT COUNT(*) as count FROM comments WHERE problem_id = " . $row['problem_id'] . " AND parent_comment_id IS NULL";
    $comments_count_result = $conn->query($comments_count_sql);
    $comments_count = $comments_count_result->fetch_assoc()['count'];

    // Count images
    $images_count_sql = "SELECT COUNT(*) as count FROM problem_images WHERE problem_id = " . $row['problem_id'];
    $images_count_result = $conn->query($images_count_sql);
    $images_count = $images_count_result->fetch_assoc()['count'];
    ?>
    <?php if ($comments_count > 0): ?>
        <button onclick="toggleComments(<?= $row['problem_id'] ?>)" class="px-4 py-2 bg-blue-100 text-blue-700 rounded-full hover:bg-blue-200 transition duration-300">
            <i class="far fa-comment mr-2"></i>Comments (<?= $comments_count ?>)
        </button>
    <?php endif; ?>

    <?php if ($images_count > 0): ?>
        <button onclick="openImageModal(<?= $row['problem_id'] ?>, 'problem')" class="px-4 py-2 bg-green-100 text-green-700 rounded-full hover:bg-green-200 transition duration-300">
            <i class="far fa-image mr-2"></i>Images (<?= $images_count ?>)
        </button>
    <?php endif; ?>

    <!-- Update Button -->
    <button onclick="openUpdateModal(<?= $row['problem_id'] ?>)" class="px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full hover:bg-yellow-200 transition duration-300">
        <i class="fas fa-edit mr-2"></i>Update
    </button>

 <!-- Delete Button -->
<button onclick="deleteProblem(<?= $row['problem_id'] ?>)" class="px-4 py-2 bg-red-100 text-red-700 rounded-full hover:bg-red-200 transition duration-300">
    <i class="fas fa-trash mr-2"></i>Delete
</button>
</div>

<!-- Update Modal -->
<div id="updateModal_<?= $row['problem_id'] ?>" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <h3 class="text-lg font-bold mb-4">Update Problem</h3>
     
        <form id="updateForm_<?= $row['problem_id'] ?>" onsubmit="updateProblem(event, <?= $row['problem_id'] ?>)">
    <div class="mb-4">
        <label for="category_<?= $row['problem_id'] ?>" class="block text-sm font-medium text-gray-700">Category</label>
        <?php
        $problem_category_sql = "SELECT * FROM problem_categories";
        $problem_category_result = mysqli_query($conn, $problem_category_sql);
        ?>
        <select id="category_<?= $row['problem_id'] ?>" name="category_id" class="mt-1 block w-full px-4 py-3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
            <?php while ($category = mysqli_fetch_assoc($problem_category_result)): ?>
                <option value="<?= $category['category_id'] ?>" <?= $row['category_id'] == $category['category_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($category['category_name']) ?>
                </option>
            <?php endwhile; ?>
        </select>
    </div>

    <div class="mb-4">
        <label for="description_<?= $row['problem_id'] ?>" class="block text-sm font-medium text-gray-700">Description</label>
        <textarea id="description_<?= $row['problem_id'] ?>" name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required><?= htmlspecialchars($row['description']) ?></textarea>
    </div>
   
    <div class="mb-4">
        <label for="phone_<?= $row['problem_id'] ?>" class="block text-sm font-medium text-gray-700">Phone Contact</label>
        <input type="text" id="phone_<?= $row['problem_id'] ?>" name="contact" value="<?= htmlspecialchars($row['contact']) ?>" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>
        <input type="hidden" name="problem_id" value="<?= htmlspecialchars($row['problem_id']) ?>">
    </div>

    

    <div class="flex justify-end">
        <button type="button" onclick="closeUpdateModal(<?= $row['problem_id'] ?>)" class="mr-2 px-4 py-2 bg-gray-200 text-gray-800 rounded-md hover:bg-gray-300">Cancel</button>
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Update</button>
    </div>
</form>


    </div>
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
                        <button disabled class="like-button <?= $like_color ?> hover:text-blue-500 transition duration-300 ease-in-out flex items-center">
    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
    </svg>
    <?php if ($likes > 0): ?><span class="font-semibold" id="comment<?php echo $comment_row['comment_id'] ?>"><?php echo $likes ?></span><?php endif; ?>
    <span class="ml-1"><?php echo $likes === 1 ? 'Like' : 'Likes' ?></span>
</button>
                    <!-- </form> -->
                    
                    
                    <span class="text-gray-500 text-xs">• <?= time_ago($comment_row['created_at']) ?> •</span>
                </div>

                <div class="mt-4">
                    <div id="reply-form-<?= $comment_row['comment_id'] ?>" class="hidden mt-4 bg-gray-50 p-4 rounded-lg shadow-md">
                   
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
    <?php else: ?>
        <div class="bg-red-100 p-4 w-full border-l-2 border-red-500">
             <p class="text-red-600 text-center text-2xl">No posted problems found.</p>
        </div>
        
    <?php endif; ?>


</div>

    </div>

    <!-- Modal Structure -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg max-w-3xl w-full max-h-[90vh] p-6 overflow-auto relative " id="modal-problem">
            <h2 class="text-2xl font-bold mb-4">Images</h2>
            <div id="modal-images" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4"></div>
            <button onclick="closeImageModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        <div class="bg-white rounded-lg max-w-3xl w-full p-6 relative" id="modal-comment">
        <h2 class="text-2xl font-bold mb-4">Images</h2>

        <img id="modalImage" src="" alt="Comment Image" class="w-full h-auto">
        <button onclick="closeImageModal()" class="absolute top-4 right-4 text-gray-600 hover:text-gray-800">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
    </div>
  

    <script>
        function toggleComments(problemId) {
            const commentsSection = document.getElementById(`comments-${problemId}`);
            commentsSection.classList.toggle('hidden');
        }
        function toggleReply(commentId) {
            const replyElement = document.getElementById(`replies-${commentId}`);
            if (replyElement.classList.contains('hidden')) {
                replyElement.classList.remove('hidden');
            } else {
                replyElement.classList.add('hidden');
            }
        }


        function toggleAddComment(problemId) {
            const commentForm = document.getElementById(`add-comment-${problemId}`);
            commentForm.classList.toggle('hidden');
        }

        function toggleReplyForm(commentId) {
            const replyForm = document.getElementById(`reply-form-${commentId}`);
            replyForm.classList.toggle('hidden');
        }

        function openImageModal(id, type) {
            const modal = document.getElementById('imageModal');
            const modalComment = document.getElementById('modal-comment').classList.add('hidden');
            const modalProblem = document.getElementById('modal-problem').classList.remove('hidden');
            
            const modalImages = document.getElementById('modal-images');

            // Clear existing images
            modalImages.innerHTML = '';

            // Determine the correct parameter name based on the type
    const paramName = type === 'reply' ? 'reply_id' : 
                      type === 'comment' ? 'comment_id' : `${type}_id`;

            // Fetch images from the server
            fetch(`fetch_images.php?${paramName}=${id}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        console.error('Error:', data.error);
                        modalImages.innerHTML = `<p class="text-red-500">Error: ${data.error}</p>`;
                    } else if (data.length === 0) {
                        modalImages.innerHTML = '<p>No images found.</p>';
                    } else {
                        data.forEach(image => {
                            const imgElement = document.createElement('img');
                            imgElement.src = image.url;
                            imgElement.className = 'w-full h-auto rounded-lg mb-2';
                            modalImages.appendChild(imgElement);
                        });
                    }
                    modal.classList.remove('hidden');
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalImages.innerHTML = `<p class="text-red-500">An error occurred while fetching images.</p>`;
                    modal.classList.remove('hidden');
                });
        }

        function openImageModal1(imageUrl) {
            // console.log(imageUrl)
            const modalProblem = document.getElementById('modal-problem').classList.add('hidden');
            const modalComment = document.getElementById('modal-comment').classList.remove('hidden');


    const modal = document.getElementById('imageModal');
     const modalImage = document.getElementById('modalImage');
     modalImage.src = imageUrl;
     modal.classList.remove('hidden');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
}


        
        function toggleReplies(commentId) {
        const repliesSection = document.getElementById(`replies-${commentId}`);
        repliesSection.classList.toggle('hidden');
    }
    
    function likeComment(commentId) {
    fetch('likes.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `comment_id=${commentId}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const likeButton = document.querySelector(`button[onclick="likeComment(${commentId})"]`);
            const likeCountElement = document.getElementById(`comment${commentId}`);
            
            if (data.action === 'like') {
                likeButton.classList.remove('text-gray-600');
                likeButton.classList.add('text-blue-500');
                if (likeCountElement) {
                    likeCountElement.textContent = parseInt(likeCountElement.textContent) + 1;
                } else {
                    const newSpan = document.createElement('span');
                    newSpan.id = `comment${commentId}`;
                    newSpan.className = 'font-semibold';
                    newSpan.textContent = '1';
                    likeButton.insertBefore(newSpan, likeButton.querySelector('span.ml-1'));
                }
            } else {
                likeButton.classList.remove('text-blue-500');
                likeButton.classList.add('text-gray-600');
                if (likeCountElement) {
                    const currentLikes = parseInt(likeCountElement.textContent);
                    if (currentLikes === 1) {
                        likeCountElement.remove();
                    } else {
                        likeCountElement.textContent = currentLikes - 1;
                    }
                }
            }

            // Update the "Like" or "Likes" text
            const likeTextElement = likeButton.querySelector('span.ml-1');
            if (likeTextElement) {
                const currentLikes = parseInt(likeCountElement?.textContent) || 0;
                likeTextElement.textContent = currentLikes === 1 ? 'Like' : 'Likes';
            }
        } else {
            console.error('Like failed:', data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}
function submitComment(event, formId) {
    event.preventDefault();
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    const problemId = formId.split('_')[1];
    const messageDiv = document.getElementById(`commentMessage_${problemId}`);

    fetch('add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = '<p class="text-green-500">Comment added successfully!</p>';
            form.reset();
            // reload after 2 sec
            setTimeout(() => {
                location.reload();
            }, 2000);
            // Optionally, update the UI to show the new comment here
        } else {
            messageDiv.innerHTML = `<p class="text-red-500">Error: ${data.error}</p>`;
        }
    })
    .catch(error => {
        messageDiv.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
    });
}

function submitReply(event, formId) {
    event.preventDefault();
    const form = document.getElementById(formId);
    const formData = new FormData(form);
    const commentId = formId.split('_')[1];
    const messageDiv = document.getElementById(`replyMessage_${commentId}`);

    fetch('add_comment.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            messageDiv.innerHTML = '<p class="text-green-500">Reply added successfully!</p>';
            form.reset();
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
            messageDiv.innerHTML = `<p class="text-red-500">Error: ${data.error}</p>`;
        }
    })
    .catch(error => {
        messageDiv.innerHTML = `<p class="text-red-500">Error: ${error.message}</p>`;
    });
}
function openUpdateModal(problemId) {
    document.getElementById(`updateModal_${problemId}`).classList.remove('hidden');
}

function closeUpdateModal(problemId) {
    document.getElementById(`updateModal_${problemId}`).classList.add('hidden');
}

function updateProblem(event, problemId) {
    event.preventDefault();
    const form = document.getElementById(`updateForm_${problemId}`);
    const formData = new FormData(form);
  
    fetch('update_problem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeUpdateModal(problemId);
            // Optionally, update the UI to reflect the changes
            document.querySelector(`.success_${problemId}`).innerHTML="Problem updated successfully!";
            setTimeout(() => {
                location.reload();
            }, 2000);
        } else {
           
            document.querySelector(`.success_${problemId}`).innerHTML="Error updating problem!";

            console.error('Error updating problem:', data.error);
        }
    })
    .catch(error => {
        alert('An error occurred while updating the problem.');
        console.error('Error:', error);
    });
}

function deleteProblem(problemId) {
    event.preventDefault();

    // Fetch the CSRF token if needed (optional)
    const formData = new FormData();
    formData.append('problem_id', problemId);

    fetch('delete_problem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI to reflect the deletion
            document.querySelector(`.success_${problemId}`).innerHTML = "Problem deleted successfully!";
            setTimeout(() => {
                location.reload(); // Reload the page after 2 seconds
            }, 2000);
        } else {
            document.querySelector(`.success_${problemId}`).innerHTML = "Error deleting problem!";
            console.error('Error deleting problem:', data.error);
        }
    })
    .catch(error => {
        alert('An error occurred while deleting the problem.');
        console.error('Error:', error);
    });
}

function confirmSolvedProblem(problemId) {
    event.preventDefault();

    // Fetch the CSRF token if needed (optional)
    const formData = new FormData();
    formData.append('problem_id', problemId);

    fetch('confirm_solved_problem.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Update the UI to reflect the confirmation
            document.querySelector(`.success_${problemId}`).innerHTML = "Problem confirmed as solved!";
            setTimeout(() => {
                location.reload(); // Reload the page after 2 seconds
            }, 2000);
        } else {
            document.querySelector(`.success_${problemId}`).innerHTML = "Error confirming problem as solved!";
            console.error('Error confirming problem as solved:', data.error);
        }
    })
    .catch(error => {
        alert('An error occurred while confirming the problem as solved.');
        console.error('Error:', error);
    });
}


    </script>
</body>

</html>