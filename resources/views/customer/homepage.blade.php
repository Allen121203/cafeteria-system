<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>RET Cafeteria - CLSU</title>

    <!-- Tailwind CSS script, including custom configuration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'clsu-green': '#00462E',
                        'ret-green-light': '#057C3C',
                        'cafeteria-orange': '#FB3E05',
                        'ret-dark': '#1F2937',
                        'menu-orange': '#EA580C',
                        'menu-dark': '#131820',
                    },
                    fontFamily: {
                        fugaz: ['"Fugaz One"', 'sans-serif'],
                        damion: ['"Damion"', 'cursive'],
                        poppins: ['"Poppins"', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Fugaz+One&family=Damion&display=swap" rel="stylesheet" />
</head>
<body class="font-poppins bg-gray-200">

    <!-- Header Section -->
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
            <div class="flex items-center space-x-4">
            <img src="{{ asset('images/Ret Cafeteria Logo.png') }}" alt="RET Cafeteria Logo" class="h-12 w-auto" />
            </div>

            <nav class="hidden md:flex space-x-8 text-ret-dark font-poppins font-medium">
                <a href="#home" class="hover:text-ret-green-light">Home</a>
                <a href="#about" class="text-gray-600 hover:text-ret-green-light">About</a>
                <a href="#menu" class="text-gray-600 hover:text-ret-green-light">Menu</a>
                <a href="#contact" class="text-gray-600 hover:text-ret-green-light">Contact Us</a>
                <a href="#reservation" class="text-gray-600 hover:text-ret-green-light flex items-center">
                    Reservation
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </a>
            </nav>

            <div class="flex items-center space-x-4 text-sm text-gray-600 font-poppins">
                <span>Hi, Name</span>
                <div class="relative">
                    <img src="https://placehold.co/24x24/CCCCCC/333333?text=N" alt="Notifications" class="w-6 h-6" />
                </div>
                <div class="w-8 h-8 bg-gray-600 rounded-full text-white flex items-center justify-center font-medium">
                    <img src="images/clsu-logo.png" alt="Notifications" class="w-6 h-6" />
                </div>
            </div>
        </div>
    </header>

        <!-- Hero Section -->
    <section id="home" class="relative py-20 bg-white text-black">
        <div class="relative z-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col-reverse lg:flex-row gap-10 items-center">
            <div class="flex-2 text-center">
                <h1 class="text-5xl md:text-7xl font-bold mb-8 leading-tight">
                    <span class="text-clsu-green font-fugaz text-8xl">CLSU</span>
                    <span class="text-ret-green-light font-fugaz text-8xl"> RET</span>
                    <br>
                    <span class="text-cafeteria-orange font-damion text-9xl">Cafeteria</span>
                </h1>
                <p class="text-2xl mb-8 font-poppins italic opacity-80">Official Food Caterer of the University. Also offers food catering services for special occasions.</p>
                <p class="text-base mb-8 font-poppins italic opacity-70">Your meal, your way—fast, fresh, and convenient. Book Now!</p>
                <button class="bg-clsu-green px-8 py-3 rounded-lg font-poppins font-semibold text-white text-base hover:bg-green-700 transition duration-300">
                    Reserve Now
                </button>
            </div>

            <div class="flex-1 relative flex justify-center lg:justify-end">
                <div class="relative w-96 h-96">
                    <img src="https://placehold.co/384x384/0000FF/FFFFFF?text=Blue+Curve" alt="Blue curve" class="absolute inset-0 w-full h-full object-contain -z-10" />
                    <img src="https://placehold.co/384x384/FF0000/FFFFFF?text=Food+Plate" alt="Food plate" class="absolute inset-0 w-full h-full object-contain" />
                </div>
            </div>
        </div>
    </section>
    
    <!-- About Us Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div class="bg-ret-dark text-white p-8 rounded-lg shadow-lg font-poppins">
                <h2 class="text-4xl font-bold mb-4">About us</h2>
                <h3 class="text-4xl font-bold mb-6">CLSU RET Cafeteria</h3>
                <p class="text-base mb-6">At CLSU RET Cafeteria, we take pride in serving fresh, delicious, and high-quality meals to the CLSU community...</p>
                <p class="text-base mb-8">Beyond daily meals, we also offer professional catering services for special occasions...</p>
                <button class="bg-clsu-green px-6 py-3 rounded-lg font-semibold text-white text-base hover:bg-green-700 transition duration-300">
                    See more
                </button>
            </div>

            <div class="flex justify-center">
                <img src="https://placehold.co/320x320/000000/FFFFFF?text=Building" alt="Cafeteria Building" class="w-80 h-80 object-contain" />
            </div>
        </div>
    </section>

    <!-- Menus Section -->
    <section id="menu" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-poppins text-center mb-16">
            <h2 class="text-4xl font-bold text-ret-dark mb-4">Menus</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-0">
            <!-- Vegetable & Salads -->
            <div class="bg-ret-dark text-white overflow-hidden shadow-lg aspect-square">
                <img src="images/breakfast.png" alt="Vegetables & Salads" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-4xl font-bold mb-2">Vegetables & Salads</h3>
                    <p class="text-base text-gray-300">Fresh vegetables and fruits.</p>
                </div>
            </div>

            <!-- Sandwiches & Snacks -->
            <div class="bg-ret-green-light text-white overflow-hidden shadow-lg aspect-square">
                <img src="images/Sandwich.png" alt="Sandwiches & Snacks" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-4xl font-bold mb-2">Sandwiches & Snacks</h3>
                    <p class="text-base text-gray-300">Ideal for in-between meals.</p>
                </div>
            </div>

            <!-- Rice Meals & Main Courses -->
            <div class="bg-cafeteria-orange text-white overflow-hidden shadow-lg aspect-square">
                <img src="images/Adobo.png" alt="Rice Meals & Main Courses" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-4xl font-bold mb-2">Rice Meals & Main Courses</h3>
                    <p class="text-base text-gray-300">Served with rice, featuring Filipino specialty.</p>
                </div>
            </div>

            <!-- Desserts & Beverages -->
            <div class="bg-clsu-green text-white overflow-hidden shadow-lg aspect-square">
                <img src="images/Juice.png" alt="Desserts & Beverages" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-4xl font-bold mb-2">Desserts & Beverages</h3>
                    <p class="text-base text-gray-300">Sweet treats and variety of drinks.</p>
                </div>
            </div>

            <!-- Soups & Side Dishes -->
            <div class="bg-menu-orange text-white overflow-hidden shadow-lg aspect-square">
                <img src="images/tinola.webp" alt="Soups & Side Dishes" class="h-48 w-full object-cover" />
                <div class="p-6">
                    <h3 class="text-4xl font-bold mb-2">Soups & Side Dishes</h3>
                    <p class="text-base text-gray-300">Warm and flavorful broths.</p>
                </div>
            </div>

            <!-- And Much More -->
            <div class="bg-menu-dark text-white overflow-hidden shadow-lg flex flex-col items-center justify-center p-6 aspect-square">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-16 h-16 mb-4 text-white">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
                <h3 class="text-4xl font-bold mb-2">And Much More</h3>
            </div>
        </div>
    </section>

    <!-- Best Seller Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center mb-16 font-poppins">
            <h2 class="text-4xl font-bold text-ret-dark mb-4">Best Seller</h2>
            <p class="text-xl text-gray-600">Most-Ordered Meals</p>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-2 gap-8 font-poppins">
            <div class="bg-ret-dark text-white rounded-lg p-8 shadow-lg">
                <h3 class="text-4xl font-bold mb-2">Standard Menu</h3>
                <p class="text-base text-gray-300 mb-4">Breakfast</p>
                <h4 class="text-3xl font-bold text-cafeteria-orange mb-6">Menu 1</h4>
                <ul class="space-y-2 text-gray-300 list-disc list-inside marker:text-cafeteria-orange">
                    <li>Longanisa with Slice Tomato</li>
                    <li>Fried Egg Sunny Side Up</li>
                    <li>Rice</li>
                    <li>Tea/Coffee</li>
                    <li>Bottled Water</li>
                </ul>
            </div>

            <div class="bg-ret-dark text-white rounded-lg p-8 shadow-lg">
                <h3 class="text-4xl font-bold mb-2">Special Menu</h3>
                <p class="text-base text-gray-300 mb-4">Lunch</p>
                <h4 class="text-3xl font-bold text-cafeteria-orange mb-6">Menu 3</h4>
                <ul class="space-y-2 text-gray-300 list-disc list-inside marker:text-cafeteria-orange">
                    <li>Sinigang na Hipon</li>
                    <li>Fried Chicken</li>
                    <li>Gising-gising</li>
                    <li>Sliced Fruits</li>
                    <li>Rice</li>
                    <li>Bottled Water</li>
                </ul>
            </div>
        </div>
    </section>

    <!-- Reserve Your Spot Section -->
    <section id="reservation" class="py-20 bg-black relative overflow-hidden text-center text-white">
        <div class="absolute inset-0 opacity-20" style="background-image: url('https://placehold.co/1920x1080/000000/FFFFFF?text=Spices+Background');"></div>
        
        <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 font-poppins">
            <h2 class="text-4xl font-bold mb-6">Reserve Your Spot at RET Cafeteria</h2>
            <p class="text-xl mb-8">Don't miss out. Reserve ahead and roll up when it's time to eat.</p>
            <button class="bg-clsu-green px-8 py-3 rounded-lg font-semibold text-white text-base hover:bg-green-700 transition duration-300">
                Reserve Now
            </button>
        </div>
    </section>

    <!-- Customer Support Section -->
    <section id="contact" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center font-poppins">
            <h2 class="text-4xl font-bold text-ret-dark mb-4">Customer Support</h2>
            <p class="text-xl text-gray-600 mb-12">Have a question? We're here to help!</p>

            <div class="flex flex-col lg:flex-row items-center justify-between space-y-8 lg:space-y-0 lg:space-x-8">
                <div class="bg-white border border-gray-200 rounded-lg p-8 shadow-lg w-full lg:w-1/2">
                    <div class="flex items-center mb-4 relative">
                        <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                            </svg>
                        </div>
                    </div>
                    <h3 class="text-xl font-bold text-ret-dark mb-2">Contact Us →</h3>
                    <p class="text-gray-600">Reach out to our team for any additional queries</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer Section -->
    <footer class="bg-ret-dark text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-8 font-poppins">
            <div>
                <h3 class="text-xl font-bold mb-4">Information</h3>
                <p class="text-gray-300">RET Blgd. CLSU, Muñoz, Nueva Ecija, Philippines</p>
                <p class="text-gray-300">Call us now: 0927 719 7639</p>
                <p class="text-gray-300">Email: RETCafeteria@clsu2.edu.ph</p>
            </div>

            <div class="text-center">
                <div class="flex items-center justify-center space-x-2 mb-4">
                    <div>
                        <span class="text-clsu-green font-fugaz text-6xl">CLSU</span>
                        <span class="text-ret-green-light font-fugaz text-6xl"> RET</span>
                        <span class="text-cafeteria-orange font-damion text-6xl italic"> Cafeteria</span>
                    </div>
                </div>
                <div class="flex items-center justify-center space-x-2 text-gray-300 text-sm mb-2">
                    <span>🍴</span>
                    <span>SINCE 1995</span>
                    <span>🥄</span>
                </div>
            </div>

            <div>
                <h3 class="text-xl font-bold mb-4">Socials</h3>
                <div class="flex items-center space-x-2">
                    <img src="https://placehold.co/24x24/000000/FFFFFF?text=T" alt="Twitter" class="w-6 h-6 text-blue-400" />
                    <a href="#" class="text-gray-300 hover:text-white">CLSU RET Cafeteria</a>
                </div>
            </div>
        </div>

        <div class="border-t border-gray-600 mt-8 pt-8 text-center text-gray-300 text-sm">
            &copy;2025 All Rights Copyright CLSU RET Cafeteria. Design & Developed By BSIT.
        </div>
    </footer>

    <!-- JavaScript for smooth scrolling -->
    <script>
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if(target) {
                    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }
            });
        });
    </script>
</body>
</html>
