     // JavaScript to handle sidebar toggle
     const menuToggle = document.getElementById('menu-toggle');
     const sidebar = document.getElementById('sidebar');
     const mainContent = document.getElementById('main-content');

     menuToggle.addEventListener('click', () => {
         sidebar.classList.toggle('-translate-x-full');
         mainContent.classList.toggle('md:ml-1/5');
     });

     window.addEventListener('resize', () => {
         if (window.innerWidth >= 768) {
             sidebar.classList.remove('-translate-x-full');
             mainContent.classList.remove('md:ml-1/5');
         }
     });