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
                <!-- 1. Gestion Utilisateurs -->
                <section id="users" class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b flex justify-between items-center">
                        <h3 class="text-lg font-semibold">Gestion des Utilisateurs</h3>
                        <div class="flex space-x-2">
                            <input type="text" placeholder="Rechercher un utilisateur..." 
                                   class="border rounded-lg px-4 py-2">
                            <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <table class="min-w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Rôle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200">
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <img src="/api/placeholder/40/40" alt="User" class="w-10 h-10 rounded-full">
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">Jean Dupont</div>
                                                <div class="text-sm text-gray-500">jean@example.com</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">Étudiant</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                            Actif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm">
                                        <div class="flex space-x-2">
                                            <button class="text-blue-600 hover:text-blue-900">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="text-red-600 hover:text-red-900">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                            <button class="text-yellow-600 hover:text-yellow-900">
                                                <i class="fas fa-ban"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>

</body>
</html>