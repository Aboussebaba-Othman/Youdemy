<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar" class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white 
                                 transform transition-transform duration-300 ease-in-out 
                                 md:translate-x-0 -translate-x-full fixed md:relative z-50 h-full 
                                 shadow-2xl custom-scrollbar">
            <div class="p-6 border-b border-blue-700 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-white">Youdemy</h1>
            </div>

            <nav class="mt-6">
                <a href="home.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-book mr-4 text-blue-300 group-hover:text-white"></i>
                    Mes Cours
                </a>
                <a href="statistiques.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-chart-line mr-4 text-blue-300 group-hover:text-white"></i>
                    Statistiques
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-cog mr-4 text-blue-300 group-hover:text-white"></i>
                    Paramètres
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle" class="md:hidden mr-4 focus:outline-none">
                        <i class="fas fa-bars text-2xl text-gray-600"></i>
                    </button>
                    <h2 class="text-2xl font-semibold text-gray-800">Tableau de Bord</h2>
                </div>

                <div class="relative">
                    <div id="userProfileToggle" class="flex items-center cursor-pointer 
                                                       hover:bg-gray-100 p-2 rounded-full transition">
                        <span class="mr-3 text-gray-700 font-medium">Jean Dupont</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 text-sm font-bold">T</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto  animate-fade-in custom-scrollbar">
                <div class="container mx-auto px-4 py-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 
                        hover:shadow-lg transition transform hover:-translate-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-gray-500 text-sm uppercase mb-2">Total Cours</h3>
                                    <p class="text-3xl font-bold text-blue-600">24</p>
                                </div>
                                <div class="bg-blue-50 rounded-full p-3">
                                    <i class="fas fa-book text-blue-500 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 
                        hover:shadow-lg transition transform hover:-translate-y-2">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-gray-500 text-sm uppercase mb-2">Total Étudiants</h3>
                                    <p class="text-3xl font-bold text-green-600">456</p>
                                </div>
                                <div class="bg-green-50 rounded-full p-3">
                                    <i class="fas fa-users text-green-500 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Top 3 Cours</h2>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">1</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Développement Web Complet</h3>
                                            <p class="text-sm text-gray-500">156 Étudiants</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">2</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Data Science Avancé</h3>
                                            <p class="text-sm text-gray-500">124 Étudiants</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">3</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Design UX/UI Moderne</h3>
                                            <p class="text-sm text-gray-500">98 Étudiants</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-6">
                            <div class="flex justify-between items-center mb-6">
                                <h2 class="text-xl font-bold text-gray-800">Top 3 Étudiants</h2>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">1</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Jean Dupont</h3>
                                            <p class="text-sm text-gray-500">Développement Web</p>
                                            <p class="text-xs text-gray-400">jean.dupont@email.com</p>
                                        </div>
                                    </div>
                                    <span class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">
                                        5 Cours
                                    </span>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">2</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Marie Martin</h3>
                                            <p class="text-sm text-gray-500">Data Science</p>
                                            <p class="text-xs text-gray-400">marie.martin@email.com</p>
                                        </div>
                                    </div>
                                    <span class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                        4 Cours
                                    </span>
                                </div>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">3</span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">Pierre Dubois</h3>
                                            <p class="text-sm text-gray-500">Design UX/UI</p>
                                            <p class="text-xs text-gray-400">pierre.dubois@email.com</p>
                                        </div>
                                    </div>
                                    <span class="bg-purple-100 text-purple-600 px-3 py-1 rounded-full text-sm">
                                        3 Cours
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
    document.getElementById('userProfileToggle').addEventListener('click', function() {
        const dropdown = document.getElementById('userDropdown');
        dropdown.classList.toggle('hidden');
    });

    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('userDropdown');
        const profileToggle = document.getElementById('userProfileToggle');

        if (!profileToggle.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.classList.add('hidden');
        }
    });

    document.getElementById('mobile-menu-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('sidebar');
        sidebar.classList.toggle('-translate-x-full');
    });
    </script>
</body>

</html>