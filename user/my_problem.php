<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>My Problems - Online Tutoring Platform</title>
<script src="../js/tailwind.js
      " > </script>
          <link href="styles.css" rel="stylesheet" />
    <script>
        function toggleComments(element) {
            const comments = element.previousElementSibling;
            comments.classList.toggle("hidden");
            element.textContent = comments.classList.contains("hidden") ? "View Comments" : "Hide Comments";
        }

        function toggleCommentInput(element) {
            const commentInput = element.previousElementSibling;
            commentInput.classList.toggle("hidden");
            element.textContent = commentInput.classList.contains("hidden") ? "Add Comment" : "Hide Comment Input";
        }
    </script>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="container mx-auto mt-8 px-4 flex-grow">
        <h2 class="text-2xl font-bold mb-6">My Posted Problems</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

            <!-- Problem Example 1 -->
            <div class="bg-white shadow-md rounded-lg p-4 mt-4">
                <h3 class="text-lg font-bold mb-2">Physics</h3>
                <p class="text-gray-700 mb-2">
                    Looking for assistance with quantum mechanics concepts.
                </p>
                <p class="text-gray-600 mb-2">
                    Email: student2@example.com, Contact: 987-654-3210
                </p>
                <p class="text-gray-600 mb-2">
                    Status: solved
                </p>

                <!-- Comments Section -->
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
                    <div class="hidden mb-2">
                        <strong>User4:</strong> I had a similar issue; feel free to ask if you need more info.
                    </div>
                    <button onclick="toggleComments(this)" class="text-blue-500 hover:underline mb-2">View Comments</button>

                    <!-- Add Comment Input -->
                    <div class="hidden mb-4">
                        <form action="/add_comment" method="post">
                            <textarea name="comment" placeholder="Add your comment here..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mb-2"></textarea>
                            <input type="hidden" name="problem_id" value="2" />
                            <button type="submit"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8l6 3" />
                                </svg>
                                Send
                            </button>
                        </form>
                    </div>
                    <button onclick="toggleCommentInput(this)" class="text-blue-500 hover:underline mb-2">Add Comment</button>
                </div>
            </div>

            <!-- Problem Example 2 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-bold mb-2">Mathematics</h3>
                <p class="text-gray-700 mb-2">
                    Need help with calculus integration techniques.
                </p>
                <p class="text-gray-600 mb-2">
                    Email: student1@example.com, Contact: 123-456-7890
                </p>
                <p class="text-gray-600 mb-2">
                    Status: pending
                </p>

                <!-- Comments Section -->
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
                    <div class="hidden mb-2">
                        <strong>User2:</strong> I can help with integration. Let's connect!
                    </div>
                    <div class="hidden mb-2">
                        <strong>User3:</strong> Check out this resource on calculus.
                    </div>
                    <button onclick="toggleComments(this)" class="text-blue-500 hover:underline mb-2">View Comments</button>

                    <!-- Add Comment Input -->
                    <div class="hidden mb-4">
                        <form action="/add_comment" method="post">
                            <textarea name="comment" placeholder="Add your comment here..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mb-2"></textarea>
                            <input type="hidden" name="problem_id" value="1" />
                            <button type="submit"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8l6 3" />
                                </svg>
                                Send
                            </button>
                        </form>
                    </div>
                    <button onclick="toggleCommentInput(this)" class="text-blue-500 hover:underline mb-2">Add Comment</button>
                </div>
            </div>

            <!-- Problem Example 1 -->
            <div class="bg-white shadow-md rounded-lg p-4 mt-4">
                <h3 class="text-lg font-bold mb-2">Physics</h3>
                <p class="text-gray-700 mb-2">
                    Looking for assistance with quantum mechanics concepts.
                </p>
                <p class="text-gray-600 mb-2">
                    Email: student2@example.com, Contact: 987-654-3210
                </p>
                <p class="text-gray-600 mb-2">
                    Status: solved
                </p>

                <!-- Comments Section -->
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
                    <div class="hidden mb-2">
                        <strong>User4:</strong> I had a similar issue; feel free to ask if you need more info.
                    </div>
                    <button onclick="toggleComments(this)" class="text-blue-500 hover:underline mb-2">View Comments</button>

                    <!-- Add Comment Input -->
                    <div class="hidden mb-4">
                        <form action="/add_comment" method="post">
                            <textarea name="comment" placeholder="Add your comment here..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mb-2"></textarea>
                            <input type="hidden" name="problem_id" value="2" />
                            <button type="submit"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8l6 3" />
                                </svg>
                                Send
                            </button>
                        </form>
                    </div>
                    <button onclick="toggleCommentInput(this)" class="text-blue-500 hover:underline mb-2">Add Comment</button>
                </div>
            </div>

            <!-- Problem Example 2 -->
            <div class="bg-white shadow-md rounded-lg p-4">
                <h3 class="text-lg font-bold mb-2">Mathematics</h3>
                <p class="text-gray-700 mb-2">
                    Need help with calculus integration techniques.
                </p>
                <p class="text-gray-600 mb-2">
                    Email: student1@example.com, Contact: 123-456-7890
                </p>
                <p class="text-gray-600 mb-2">
                    Status: pending
                </p>

                <!-- Comments Section -->
                <div class="mt-4">
                    <h4 class="font-semibold text-gray-700 mb-2">Comments</h4>
                    <div class="hidden mb-2">
                        <strong>User2:</strong> I can help with integration. Let's connect!
                    </div>
                    <div class="hidden mb-2">
                        <strong>User3:</strong> Check out this resource on calculus.
                    </div>
                    <button onclick="toggleComments(this)" class="text-blue-500 hover:underline mb-2">View Comments</button>

                    <!-- Add Comment Input -->
                    <div class="hidden mb-4">
                        <form action="/add_comment" method="post">
                            <textarea name="comment" placeholder="Add your comment here..."
                                class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500 mb-2"></textarea>
                            <input type="hidden" name="problem_id" value="1" />
                            <button type="submit"
                                class="flex items-center justify-center px-4 py-2 text-sm font-medium text-white bg-blue-500 border border-transparent rounded-md hover:bg-blue-600 focus:outline-none focus-visible:ring-2 focus-visible:ring-offset-2 focus-visible:ring-blue-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v8l6 3" />
                                </svg>
                                Send
                            </button>
                        </form>
                    </div>
                    <button onclick="toggleCommentInput(this)" class="text-blue-500 hover:underline mb-2">Add Comment</button>
                </div>
            </div>

        </div>
    </div>
</body>
</html>
