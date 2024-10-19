<?php
include 'db.php'; // Include the database connection
include 'navbar.php'; // Include the navigation bar

// Fetch problems 
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'all';
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
    </style>
</head>

<body>
    <div class="container mx-auto my-8 px-4">
        <h2 class="text-2xl font-bold mb-6">Posted Problems</h2>
        <form action="" method="get" class="mb-6">
            <label for="sort" class="block font-bold mb-2">Sort By</label>
            <select id="sort" name="sort" class="px-3 py-2 border rounded-md" onchange="this.form.submit()">
                <option value="all" <?= $sort == 'all' ? 'selected' : '' ?>>All Problems</option>
                <option value="pending" <?= $sort == 'pending' ? 'selected' : '' ?>>Pending</option>
                <option value="solved" <?= $sort == 'solved' ? 'selected' : '' ?>>Solved</option>
            </select>
        </form>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="bg-white shadow-md rounded-lg p-4">
                    <h3 class="text-lg font-bold"><?= htmlspecialchars($row['category_name']) ?></h3>
                    <p class="text-gray-700"><?= htmlspecialchars($row['description']) ?></p>
                    <p class="text-gray-600">Email: <?= htmlspecialchars($row['email']) ?></p>
                    <p class="text-gray-600">Contact: <?= htmlspecialchars($row['contact']) ?></p>
                    <p class="text-gray-600">Status: <?= htmlspecialchars($row['status']) ?></p>

                    <div class="mt-4">
                        <?php
                        $poster_name = $row['user_name'];
                        $problem = $row['description'];
                        $contact_number = preg_replace('/[^0-9]/', '', $row['contact']);
                        $message = urlencode("Hi " . $poster_name . ", I saw your problem: '" . $problem . "', and I would like to help you with it.");
                        $whatsappLink = "https://wa.me/" . $contact_number . "?text=" . $message;
                        ?>
                        <button onclick="toggleComments(<?= $row['problem_id'] ?>)"
                            class="text-blue-500 hover:underline">View Comments</button>
                        <button onclick="openImageModal(<?= $row['problem_id'] ?>, 'problem')"
                            class="text-blue-500 hover:underline">View Images</button>
                        <button onclick="toggleAddComment(<?= $row['problem_id'] ?>)"
                            class="text-blue-500 hover:underline ml-4">Add Comment</button>
                        <a href="<?= $whatsappLink ?>" target="_blank" class="text-green-500 hover:text-green-600">
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
    </div>

    <!-- Modal Structure -->
    <div id="imageModal" class="fixed inset-0 bg-black bg-opacity-75 hidden flex items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full h-screen p-4 overflow-auto">
            <h2 class="text-xl font-bold mb-4">Images</h2>
            <div id="modal-images" class="grid grid-cols-1 md:grid-cols-3 gap-4"></div>
            <button onclick="closeImageModal()" class="mt-4 px-4 py-2 bg-red-500 text-white rounded-md">Close</button>
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
            const modalImages = document.getElementById('modal-images');

            // Clear existing images
            modalImages.innerHTML = '';

            // Determine the correct parameter name based on the type
            const paramName = type === 'reply' ? 'reply_id' : `${type}_id`;

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


        function closeImageModal() {
            const modal = document.getElementById('imageModal');
            modal.classList.add('hidden');
        }
    </script>
</body>

</html>