


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
  </head>
  <body class="bg-gray-900">
    <!-- Navigation -->
    <nav class="bg-gray-900 text-white py-4 border-b border-gray-500 fixed top-0 w-full ">
      <div class="container mx-auto flex justify-between items-center px-4">
        <h1 class="text-2xl font-bold">Online Tutoring Platform</h1>
        <div class="space-x-4">
          <a href="login.php" class="text-sky-400 hover:text-sky-300 transition duration-300">Login</a>
          <a href="signup.php" class="text-sky-400 hover:text-sky-300 transition duration-300">Sign Up</a>
        </div>
      </div>
    </nav>
    <!-- Hero Section -->
    <section class="hero bg-gray-800 text-white py-20 mt-14">
      <div
        class="container mx-auto flex flex-col items-center justify-center text-center px-4"
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

    <!-- How It Works Section -->
    <section id="how-it-works" class="py-20">
      <div class="container mx-auto px-4">
        <h2 class="text-3xl font-bold mb-8 text-center">How It Works</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

          <!-- Step 1 -->
          <div class="border-2 border-sky-400 shadow-sm shadow-gray-300 rounded-lg ">

          <div class="p-6">
            
            <h3 class="text-xl font-bold mb-4 text-sky-300">Post Your Problem</h3>
            <p class="text-gray-400 mb-4">
              Describe your problem or question in detail. Attach relevant files
              if necessary.
            </p>
          </div>
            <img
              src="image/image1.jpg"
              alt="Post Problem"
              class=" h-44 w-full rounded-b-lg" />
          </div>

          <!-- Step 2 -->
          <div class="border-2 border-sky-400 shadow-sm shadow-gray-300 rounded-lg ">

           <div class="p-6">

            <h3 class="text-xl font-bold mb-4 text-center text-sky-300">Get Expert Responses</h3>
            <p class="text-gray-400 mb-4">
              Our experts will review your problem and provide detailed
              solutions or explanations.
            </p>
          </div>

          <img
              src="image/image2.png"
              alt="Post Problem"
              class=" h-44 w-full rounded-b-lg" />
          </div>
          <!-- Step 3 -->
          <div class="border-2 border-sky-400 shadow-sm shadow-gray-300 rounded-lg ">

           <div class="p-6">
            <h3 class="text-xl font-bold mb-4 text-sky-300">Schedule Appointments</h3>
            <p class="text-gray-400 mb-4">
              Schedule video conference appointments with tutors for in-depth
              discussions.
            </p>
           </div>
            <img
              src="image/image3.jpg"
              alt="Schedule Appointment"
              class=" h-44 w-full rounded-b-lg" 
            />
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
