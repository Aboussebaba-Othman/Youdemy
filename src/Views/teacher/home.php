<?php
require_once "../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;
use App\Controllers\Admin\TagController;


    $TagController = new TagController();
    $tags = $TagController->index();
    $categoryController = new CategoryController();
    $categories = $categoryController->getCategories();


?>

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
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }</style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar" class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 text-white transform transition-transform duration-300 ease-in-out md:translate-x-0 -translate-x-full fixed md:relative z-50 h-full shadow-2xl">
            <div class="p-6 border-b border-gray-700 flex items-center">
                <img src="https://via.placeholder.com/50" class="w-10 h-10 rounded-full mr-3" alt="Logo">
                <h1 class="text-2xl font-bold text-white">Youdemy</h1>
            </div>
            
            <nav class="mt-6">
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
                    <i class="fas fa-tachometer-alt mr-4 text-blue-400 group-hover:text-white transition"></i>
                    Tableau de Bord
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
                    <i class="fas fa-book mr-4 text-green-400 group-hover:text-white transition"></i>
                    Mes Cours
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
                    <i class="fas fa-chart-line mr-4 text-purple-400 group-hover:text-white transition"></i>
                    Statistiques
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
                    <i class="fas fa-cog mr-4 text-gray-400 group-hover:text-white transition"></i>
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
                    <div id="userProfileToggle" class="flex items-center cursor-pointer">
                        <span class="mr-3 text-gray-700">Jean Dupont</span>
                        <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="Profile">
                    </div>
                    <div id="userDropdown" class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl hidden">
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Profil</a>
                        <a href="#" class="block px-4 py-2 hover:bg-gray-100">Paramètres</a>
                        <div class="border-t"></div>
                        <a href="#" class="block px-4 py-2 text-red-600 hover:bg-gray-100">Déconnexion</a>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 animate-fade-in">
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Total Cours</h3>
                                <p class="text-3xl font-bold text-blue-600">24</p>
                            </div>
                            <i class="fas fa-book text-3xl text-blue-300"></i>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Étudiants Inscrits</h3>
                                <p class="text-3xl font-bold text-green-600">1,250</p>
                            </div>
                            <i class="fas fa-users text-3xl text-green-300"></i>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-lg shadow-md">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm">Revenus</h3>
                                <p class="text-3xl font-bold text-purple-600">45,000€</p>
                            </div>
                            <i class="fas fa-euro-sign text-3xl text-purple-300"></i>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Cours Récents</h2>
                        <button 
                            data-modal-target="addCourseForm" 
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition flex items-center"
                        >
                            <i class="fas fa-plus mr-2"></i> Ajouter un Cours
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <div class="bg-white rounded-lg shadow-md overflow-hidden transform transition hover:scale-105 hover:shadow-xl">
                            <img src="https://via.placeholder.com/350x200" class="w-full h-48 object-cover" alt="Cours">
                            <div class="p-4">
                                <h3 class="font-bold text-lg">Python pour Débutants</h3>
                                <p class="text-gray-600 text-sm">Apprenez Python de A à Z</p>
                                <div class="mt-4 flex justify-between items-center">
                                    <span class="text-yellow-500">
                                        <i class="fas fa-star"></i> 4.5
                                    </span>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition">
                                        Détails
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <div id="addCourseModal" class="fixed inset-0 bg-black bg-opacity-50 z-50 hidden items-center justify-center">
        <div class="bg-white w-full max-w-2xl rounded-xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto m-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Créer un Nouveau Cours</h2>
                <button id="closeModalBtn" class="text-gray-600 hover:text-gray-900">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form id="addCourseForm" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Titre du Cours *</label>
                        <input 
                            type="text" 
                            name="title" 
                            required 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ex: Développement Web Complet"
                        >
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Catégorie *</label>
                        <?php if (!empty($categories)): ?>
                        <select 
                            name="category_id" 
                            required 
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        >
                        <?php foreach ($categories as $category): ?>
                            <option value="">Sélectionnez une catégorie</option>
                            <option value="1"><?= htmlspecialchars($category['title']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <?php endif; ?>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Description *</label>
                    <textarea 
                        name="description" 
                        required 
                        rows="4" 
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Décrivez brièvement votre cours..."
                    ></textarea>
                </div>

                <div class="space-y-4">
                    <label class="block text-gray-700 mb-2">Type de Contenu du Cours *</label>
                    <div class="flex space-x-4">
                        <label class="flex items-center">
                            <input type="radio" name="contentType" value="video" class="form-radio" required>
                            <span class="ml-2">Vidéo</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="contentType" value="document" class="form-radio">
                            <span class="ml-2">Document</span>
                        </label>
                    </div>

                    <div id="videoContent" class="hidden">
                        <input 
                            type="url" 
                            name="videoUrl"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="URL de la vidéo (YouTube, Vimeo)"
                        >
                        <p class="text-sm text-gray-500 mt-1">Collez l'URL de votre vidéo YouTube ou Vimeo</p>
                    </div>

                    <div id="documentContent" class="hidden space-y-4">
                        <input 
                            type="text"
                            name="documentTitle"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Titre du document"
                        >
                        <textarea 
                            name="documentContent"
                            rows="10"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contenu du document..."
                        ></textarea>
                    </div>
                </div>

                <div class="space-y-2">
    <label class="block text-gray-700 font-medium">Image de Couverture *</label>
    <div class="flex items-center space-x-2">
        <input 
            type="url" 
            name="coverImageUrl" 
            placeholder="Entrez l'URL de l'image de couverture"
            required
            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
    </div>
    <div id="imagePreview" class="hidden mt-2">
        <img src="" alt="Aperçu" class="w-full h-48 object-cover rounded-lg">
    </div>
    <p class="text-sm text-gray-500">Format recommandé : 1280x720px, JPG ou PNG</p>
</div>

                <div>
                    <label class="block text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tags[]" value="javascript" class="form-checkbox">
                            <span class="ml-2">JavaScript</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tags[]" value="react" class="form-checkbox">
                            <span class="ml-2">React</span>
                        </label>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tags[]" value="backend" class="form-checkbox">
                            <span class="ml-2">Backend</span>
                        </label>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-4">
                    <button 
                        type="button" 
                        id="cancelAddCourse"
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit" 
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors"
                    >
                        Créer le Cours
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Gestion des types de contenu
        const contentTypeRadios = document.querySelectorAll('input[name="contentType"]');
        const videoContent = document.getElementById('videoContent');
        const documentContent = document.getElementById('documentContent');

        contentTypeRadios.forEach(radio => {
            radio.addEventListener('change', () => {
                videoContent.classList.add('hidden');
                documentContent.classList.add('hidden');

                if (radio.value === 'video') {
                    videoContent.classList.remove('hidden');
                } else if (radio.value === 'document') {
                    documentContent.classList.remove('hidden');
                }
            });
        });

        // Gestion du modal
        const addCourseModal = document.getElementById('addCourseModal');
        const addCourseBtn = document.querySelector('[data-modal-target="addCourseForm"]');
        const closeModalBtn = document.getElementById('closeModalBtn');
        const cancelAddCourseBtn = document.getElementById('cancelAddCourse');

        function openModal() {
            addCourseModal.classList.remove('hidden');
            addCourseModal.classList.add('flex');
        }

        function closeModal() {
            addCourseModal.classList.remove('flex');
            addCourseModal.classList.add('hidden');
            document.getElementById('addCourseForm').reset();
        }

        addCourseBtn.addEventListener('click', openModal);
        closeModalBtn.addEventListener('click', closeModal);
        cancelAddCourseBtn.addEventListener('click', closeModal);

        // Gestion du formulaire
        document.getElementById('addCourseForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            // Validation
            if (!formData.get('title') || !formData.get('category_id')) {
                alert('Veuillez remplir tous les champs obligatoires');
                return;
            }

            // Envoi des données
            console.log('Données du cours à envoyer:', Object.fromEntries(formData));
            closeModal();
        });
    </script>
    <script>
        const userProfileToggle = document.getElementById('userProfileToggle');
        const userDropdown = document.getElementById('userDropdown');

        userProfileToggle.addEventListener('click', () => {
            userDropdown.classList.toggle('hidden');
        });

        document.addEventListener('click', (event) => {
            if (!userProfileToggle.contains(event.target)) {
                userDropdown.classList.add('hidden');
            }
        });
    </script>
</body>
</html>