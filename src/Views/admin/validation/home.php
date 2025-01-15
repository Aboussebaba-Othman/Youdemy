<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion Administrative</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <!-- Structure commune -->
    <div class="flex h-screen">
        <!-- Sidebar (identique pour toutes les pages) -->
        <div class="w-64 bg-white shadow-md">
            <div class="p-5 border-b">
                <h1 class="text-2xl font-bold text-blue-600">Youdemy Admin</h1>
            </div>
            <nav class="mt-4">
            
                <a href="../home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
                    
                <a href="../user/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="../cour/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="../tag/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="../categorie/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="../validation/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <!-- Contenu Principal -->
        <div class="flex-1 overflow-auto">
            <!-- Top Navigation -->
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold">Pages de Gestion</h2>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="flex items-center">
                            <img src="/api/placeholder/32/32" alt="Admin" class="w-8 h-8 rounded-full mr-2">
                            <span class="text-gray-700">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Pages de Gestion -->
            <div class="p-6 space-y-8">
                <section id="teachers" class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">Validation des Enseignants</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <!-- Demande d'enseignant -->
                            <div class="border rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center">
                                        <img src="/api/placeholder/48/48" alt="Teacher" class="w-12 h-12 rounded-full">
                                        <div class="ml-4">
                                            <h4 class="font-semibold">Marie Martin</h4>
                                            <p class="text-sm text-gray-500">Experte en Design UX/UI</p>
                                        </div>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                            Approuver
                                        </button>
                                        <button class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                            Refuser
                                        </button>
                                    </div>
                                </div>
                                <div class="mt-4">
                                    <h5 class="font-semibold text-sm mb-2">Expérience</h5>
                                    <p class="text-sm text-gray-600">
                                        10 ans d'expérience en design UX/UI, anciennement chez Google et Facebook.
                                        Spécialisée dans la conception d'interfaces mobiles.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</body>
</html>