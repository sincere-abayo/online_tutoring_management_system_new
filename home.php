  <?php
  ?>

  <!doctype html>
  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
  </head>
  <body class="bg-gray-900">
    <nav class="bg-gray-900 border-b border-gray-500 fixed top-0 w-full shadow-sky-400 shadow-sm transition-shadow duration-300" id="navbar">
        <div class="max-w-6xl mx-auto px-4">
            <div class="flex justify-between">
                <div class="flex space-x-7">
                    <div>
                        <a href="#" class="flex items-center py-4 px-2">
                            <span class="font-semibold text-gray-500 text-lg">Logo</span>
                        </a>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-3">
                    <a href="#" class="py-2 px-2 font-medium text-sky-400 hover:text-gray-500 transition duration-300">Home</a>
                    <a href="#" class="py-2 px-2 font-medium text-sky-400 hover:text-gray-500 transition duration-300">Questions</a>
                    <a href="#" class="py-2 px-2 font-medium text-sky-400 hover:text-gray-500 transition duration-300">Profile</a>
                </div>
                <div class="md:hidden flex items-center">
                    <button class="outline-none mobile-menu-button">
                        <svg class="w-6 h-6 text-gray-500 hover:text-sky-400"
                            x-show="!showMenu"
                            fill="none"
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        <div class="hidden mobile-menu">
            <ul class="">
                <li><a href="#" class="block text-sm px-2 py-4 text-sky-400 hover:bg-gray-500 transition duration-300">Home</a></li>
                <li><a href="#" class="block text-sm px-2 py-4 text-sky-400 hover:bg-gray-500 transition duration-300">Questions</a></li>
                <li><a href="#" class="block text-sm px-2 py-4 text-sky-400 hover:bg-gray-500 transition duration-300">Profile</a></li>
            </ul>
        </div>
    </nav>

    <div class="md:mx-10 mx-5 mt-20">
        <h1 class="text-3xl font-bold mb-6 text-center text-sky-400">Recent Questions</h1>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-gray-900 rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition duration-300">
                <h2 class="text-xl font-bold mb-4 text-sky-200">What is the best way to learn programming?</h2>
                <p class="text-gray-400 mb-4">I'm a beginner and want to start learning programming. What language should I start with, and what resources do you recommend?</p>
                <div class="flex items-center space-x-4">
                    <button class="bg-sky-400 text-gray-900 px-4 py-2 rounded hover:bg-gray-500 hover:text-sky-400 transition duration-300" onclick="openModal()">Comment</button>
                    <button class="bg-gray-500 text-sky-400 px-4 py-2 rounded hover:bg-sky-400 hover:text-gray-900 transition duration-300">Like</button>
                </div>
            </div>
            <div class="bg-gray-900 rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition duration-300">
                <h2 class="text-xl font-bold mb-4 text-sky-200">How do I optimize my website for SEO?</h2>
                <p class="text-gray-400 mb-4">I've built a website, but it's not ranking well on search engines. What are some effective SEO strategies I can implement?</p>
                <div class="flex items-center space-x-4">
                    <button class="bg-sky-400 text-gray-900 px-4 py-2 rounded hover:bg-gray-500 hover:text-sky-400 transition duration-300" onclick="openModal()">Comment</button>
                    <button class="bg-gray-500 text-sky-400 px-4 py-2 rounded hover:bg-sky-400 hover:text-gray-900 transition duration-300">Like</button>
                </div>
            </div>
            <div class="bg-gray-900 rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition duration-300">
                <h2 class="text-xl font-bold mb-4 text-sky-200">What are the benefits of using a CSS framework?</h2>
                <p class="text-gray-400 mb-4">I've heard about CSS frameworks like Bootstrap and Tailwind. Are they worth learning, and what advantages do they offer?</p>
                <div class="flex items-center space-x-4">
                    <button class="bg-sky-400 text-gray-900 px-4 py-2 rounded hover:bg-gray-500 hover:text-sky-400 transition duration-300" onclick="openModal()">Comment</button>
                    <button class="bg-gray-500 text-sky-400 px-4 py-2 rounded hover:bg-sky-400 hover:text-gray-900 transition duration-300">Like</button>
                </div>
            </div>
            <div class="bg-gray-900 rounded-lg shadow-md p-6 mb-6 hover:shadow-lg transition duration-300">
                <h2 class="text-xl font-bold mb-4 text-sky-200">How can I secure my web application?</h2>
                <p class="text-gray-400 mb-4">I'm developing a web application that will handle sensitive user data. What are some essential security measures I should implement?</p>
                <div class="flex items-center space-x-4">
                    <button class="bg-sky-400 text-gray-900 px-4 py-2 rounded hover:bg-gray-500 hover:text-sky-400 transition duration-300" onclick="openModal()">Comment</button>
                    <button class="bg-gray-500 text-sky-400 px-4 py-2 rounded hover:bg-sky-400 hover:text-gray-900 transition duration-300">Like</button>
                </div>
            </div>
        </div>
    </div>

    <div class="md:mx-10 mx-5 mt-20">
        <h2 class="text-3xl font-bold mb-6 text-center text-sky-400">What We Do</h2>
        <div class="bg-gray-900 rounded-lg shadow-md p-6 mb-6">
            <p class="text-gray-400 mb-4">
                We provide a platform for developers and tech enthusiasts to ask questions, share knowledge, and learn from each other. Our community-driven approach allows users to:
            </p>
            <ul class="list-disc list-inside text-gray-400 mb-4">
                <li>Post questions on various programming topics</li>
                <li>Answer questions and help fellow developers</li>
                <li>Engage in discussions about the latest technologies</li>
                <li>Share experiences and best practices</li>
                <li>Build a network of like-minded professionals</li>
            </ul>
            <p class="text-gray-400">
                Whether you're a beginner looking for guidance or an experienced developer wanting to give back to the community, our platform is the perfect place to connect, learn, and grow.
            </p>
        </div>
    </div>

    <div id="signInModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">​</span>
            <div class="inline-block align-bottom bg-gray-900 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-gray-900 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-sky-400" id="modal-title">
                        Sign In Required
                    </h3>
                    <div class="mt-2">
                        <p class="text-sm text-gray-500">
                            Please sign in to comment on this question.
                        </p>
                    </div>
                </div>
                <div class="bg-gray-900 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-sky-400 text-base font-medium text-gray-900 hover:bg-gray-500 hover:text-sky-400 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400 sm:ml-3 sm:w-auto sm:text-sm">
                        Sign In
                    </button>
                    <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-500 shadow-sm px-4 py-2 bg-gray-900 text-base font-medium text-sky-400 hover:bg-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-sky-400 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeModal()">
                        Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function () {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });

        // Modal functions
        function openModal() {
            document.getElementById('signInModal').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('signInModal').classList.add('hidden');
        }

        // Navbar shadow on scroll
        window.addEventListener('scroll', function() {
            var navbar = document.getElementById('navbar');
            if (window.scrollY > 0) {
                navbar.classList.add('shadow-md', 'shadow-sky-400');
            } else {
                navbar.classList.remove('shadow-md', 'shadow-sky-400');
            }
        });
    </script>
  </body>
  </html>

  <?php
  ?>