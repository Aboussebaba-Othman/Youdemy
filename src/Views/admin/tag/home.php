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
    <div class="flex h-screen">
        <div class="bg-gray-800 text-white w-64 flex-shrink-0">
            <div class="p-4">
                <h1 class="text-2xl font-bold">Youdemy Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="user.html" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="cour.html" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="tag.html" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="categorie.html" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="validation.html" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-auto">
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

            <div class="p-6 space-y-8">
                <section id="tags" class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">Gestion des Tags</h3>
                    </div>
                    <div class="p-4">
                        <div class="mb-4">
                            <h4 class="text-sm font-semibold mb-2">Ajout en masse</h4>
                            <div class="flex gap-2">
                                <input type="text" placeholder="Entrez plusieurs tags séparés par des virgules"
                                       class="flex-1 border rounded-lg px-4 py-2">
                                <button class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
                                    Ajouter
                                </button>
                            </div>
                        </div>
                        <div class="flex flex-wrap gap-2 mt-4">
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm flex items-center">
                                JavaScript
                                <button class="ml-2 text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                            <span class="px-3 py-1 bg-gray-100 rounded-full text-sm flex items-center">
                                HTML
                                <button class="ml-2 text-red-500 hover:text-red-700">
                                    <i class="fas fa-times"></i>
                                </button>
                            </span>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>

</body>
</html>