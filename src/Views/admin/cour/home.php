<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-md rounded-lg">

            <!-- Navigation -->
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

            <!-- Header Section -->
            <div class="p-6 border-b flex justify-between items-center">
                <h2 class="text-2xl font-bold text-gray-800">Liste des Cours</h2>
            </div>

            <!-- Search and Filter Section -->
            <div class="p-4 bg-gray-50 border-b">
                <div class="flex space-x-4">
                    <input 
                        type="text" 
                        placeholder="Rechercher des cours..." 
                        class="flex-grow px-4 py-2 border rounded-lg"
                    >
                    <select class="px-4 py-2 border rounded-lg">
                        <option>Toutes les catégories</option>
                        <option>Développement Web</option>
                        <option>Data Science</option>
                        <option>Design</option>
                    </select>
                    <select class="px-4 py-2 border rounded-lg">
                        <option>Tous les niveaux</option>
                        <option>Débutant</option>
                        <option>Intermédiaire</option>
                        <option>Avancé</option>
                    </select>
                </div>
            </div>

            <!-- Courses Grid -->
            <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Course Card 1 -->
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img 
                            src="https://via.placeholder.com/350x200" 
                            alt="Course Image" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="absolute top-2 right-2 flex space-x-2">
                            <button class="bg-blue-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-blue-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-red-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Python pour Débutants</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Par Jean Dupont</span>
                            <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs">
                                Développement
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center text-yellow-500">
                                <i class="fas fa-star"></i>
                                <span class="ml-2">4.5 (1250 notes)</span>
                            </div>
                            <span class="font-bold text-blue-600">49.99 €</span>
                        </div>
                    </div>
                </div>

                <!-- Course Card 2 -->
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img 
                            src="https://via.placeholder.com/350x200" 
                            alt="Course Image" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="absolute top-2 right-2 flex space-x-2">
                            <button class="bg-blue-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-blue-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-red-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">JavaScript Avancé</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Par Marie Martin</span>
                            <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-xs">
                                Web
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center text-yellow-500">
                                <i class="fas fa-star"></i>
                                <span class="ml-2">4.7 (2100 notes)</span>
                            </div>
                            <span class="font-bold text-blue-600">79.99 €</span>
                        </div>
                    </div>
                </div>

                <!-- Course Card 3 -->
                <div class="bg-white border rounded-lg shadow-md overflow-hidden">
                    <div class="relative">
                        <img 
                            src="https://via.placeholder.com/350x200" 
                            alt="Course Image" 
                            class="w-full h-48 object-cover"
                        >
                        <div class="absolute top-2 right-2 flex space-x-2">
                            <button class="bg-blue-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-blue-600">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="bg-red-500 text-white p-2 rounded-full w-10 h-10 flex items-center justify-center hover:bg-red-600">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">Design UX/UI Complet</h3>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-gray-600">Par Pierre Dubois</span>
                            <span class="bg-indigo-100 text-indigo-800 px-2 py-1 rounded-full text-xs">
                                Design
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <div class="flex items-center text-yellow-500">
                                <i class="fas fa-star"></i>
                                <span class="ml-2">4.9 (950 notes)</span>
                            </div>
                            <span class="font-bold text-blue-600">99.99 €</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pagination -->
            <div class="p-6 flex justify-between items-center border-t">
                <span class="text-gray-600">Affichage de 1-9 sur 45 cours</span>
                <div class="flex space-x-2">
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">
                        <i class="fas fa-chevron-left mr-2"></i>Précédent
                    </button>
                    <button class="px-4 py-2 border rounded hover:bg-gray-100">
                        Suivant<i class="fas fa-chevron-right ml-2"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Optional: Delete Confirmation Modal -->
        <div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
            <div class="bg-white p-6 rounded-lg shadow-xl">
                <h3 class="text-xl font-bold mb-4">Confirmer la suppression</h3>
                <p class="mb-6">Êtes-vous sûr de vouloir supprimer ce cours ?</p>
                <div class="flex justify-end space-x-4">
                    <button class="px-4 py-2 bg-gray-200 rounded">Annuler</button>
                    <button class="px-4 py-2 bg-red-500 text-white rounded">Supprimer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Modal Interactions -->
    <script>
        document.querySelectorAll('.fa-trash').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('deleteModal').classList.remove('hidden');
                document.getElementById('deleteModal').classList.add('flex');
            });
        });

        document.querySelectorAll('#deleteModal button').forEach(button => {
            button.addEventListener('click', () => {
                document.getElementById('deleteModal').classList.remove('flex');
                document.getElementById('deleteModal').classList.add('hidden');
            });
        });
    </script>
</body>
</html>
