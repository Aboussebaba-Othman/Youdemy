<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../vendor/autoload.php";

use App\Controllers\Teacher\TeacherController;
use App\Controllers\Teacher\CourseController;

try {
    $courseController = new CourseController();
    $data = $courseController->getHomeData();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['course_id'])) {
            $courseController->deleteCourse($_POST['course_id']);
        } else {
            $courseController->handleAddCourse();
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    
} catch (\Exception $e) {
    error_log($e->getMessage());
    $data = [
        'categories' => [],
        'tags' => [],
        'courses' => [],
        'error' => 'Unable to load dashboard data'
    ];
}
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
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <?php if (isset($_SESSION['success'])): ?>
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
        <?= htmlspecialchars($_SESSION['success']) ?>
        <?php unset($_SESSION['success']); ?>
    </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar"
            class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 text-white transform transition-transform duration-300 ease-in-out md:translate-x-0 -translate-x-full fixed md:relative z-50 h-full shadow-2xl">
            <div class="p-6 border-b border-gray-700 flex items-center">
                <i class="fas fa-graduation-cap text-primary text-2xl mr-2"></i>
                <h1 class="text-2xl font-bold text-white">Youdemy</h1>
            </div>

            <nav class="mt-6">
                <a href="home.php" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
                    <i class="fas fa-book mr-4 text-green-400 group-hover:text-white transition"></i>
                    Mes Cours
                </a>
                <a href="statistiques.php" class="block py-3 px-6 hover:bg-gray-700 transition flex items-center group">
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
                    <div id="userProfileToggle" class="flex items-center cursor-pointer 
                                                       hover:bg-gray-100 p-2 rounded-full transition">
                        <span class="mr-3 text-gray-700 font-medium">Jean Dupont</span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 text-sm font-bold">T</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6 animate-fade-in">



                <div class="mt-8">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-2xl font-semibold">Cours Récents</h2>
                        <button data-modal-target="addCourseForm"
                            class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition flex items-center">
                            <i class="fas fa-plus mr-2"></i> Ajouter un Cours
                        </button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        <?php foreach ($data['courses'] as $course): ?>
                        <div
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300">
                            <div class="relative">
                                <img src="<?= htmlspecialchars($course['image']) ?>" class="w-full h-48 object-cover"
                                    alt="<?= htmlspecialchars($course['title']) ?>">
                                <div class="absolute top-0 right-0 m-4">
                                    <span
                                        class="bg-white px-3 py-1 rounded-full text-sm font-medium text-gray-700 shadow">
                                        <?= htmlspecialchars($course['category_name']) ?>
                                    </span>
                                </div>
                            </div>

                            <div class="p-6">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <?= htmlspecialchars($course['title']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4">
                                    <?= htmlspecialchars($course['description']) ?>
                                </p>

                                <div class="flex items-center justify-between mb-4">
                                    <div class="flex items-center space-x-2">
                                        <div class="p-2 rounded-full bg-blue-50">
                                            <i class="fas fa-users text-blue-500"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            <?= $course['student_count'] ?> étudiants
                                        </span>
                                    </div>
                                    <div class="flex items-center">
                                        <div class="flex text-yellow-400">
                                            <?php for($i = 0; $i < 5; $i++): ?>
                                            <i class="fas fa-star text-sm"></i>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex justify-between items-center pt-4 border-t">
                                    <a href="edit_course.php?id=<?= $course['id'] ?>"
                                        class="inline-flex items-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit mr-2"></i>
                                        Modifier
                                    </a>

                                    <form action="" method="post" class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <button type="submit"
                                            class="inline-flex items-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
                                            <i class="fas fa-trash mr-2"></i>
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
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

            <form action="" method="post" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Titre du Cours *</label>
                        <input type="text" name="title" required
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Ex: Développement Web Complet">
                    </div>

                    <div>
                        <label class="block text-gray-700 mb-2">Catégorie *</label>
                        <select name="category_id" required class="w-full px-4 py-2 border rounded-lg">
                            <option value="">Select Category</option>
                            <?php foreach ($data['categories'] as $category): ?>
                            <option value="<?= htmlspecialchars($category['id']) ?>">
                                <?= htmlspecialchars($category['title']) ?>
                            </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 mb-2">Description *</label>
                    <textarea name="description" required rows="4"
                        class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Décrivez brièvement votre cours..."></textarea>
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
                        <input type="url" name="videoUrl"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="URL de la vidéo (YouTube, Vimeo)">
                        <p class="text-sm text-gray-500 mt-1">Collez l'URL de votre vidéo YouTube ou Vimeo</p>
                    </div>

                    <div id="documentContent" class="hidden space-y-4">
                        <input type="text" name="documentTitle"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Titre du document">
                        <textarea name="documentContent" rows="10"
                            class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                            placeholder="Contenu du document..."></textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-medium">Image de Couverture *</label>
                    <div class="flex items-center space-x-2">
                        <input type="url" name="coverImageUrl" placeholder="Entrez l'URL de l'image de couverture"
                            required
                            class="flex-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div id="imagePreview" class="hidden mt-2">
                        <img src="" alt="Aperçu" class="w-full h-48 object-cover rounded-lg">
                    </div>
                    <p class="text-sm text-gray-500">Format recommandé : 1280x720px, JPG ou PNG</p>
                </div>

                <div class="space-y-4">
                    <label class="block text-gray-700 mb-2">Tags</label>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($data['tags'] as $tag): ?>
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['id']) ?>"
                                class="form-checkbox">
                            <span class="ml-2"><?= htmlspecialchars($tag['title']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="flex justify-end space-x-4 pt-4">
                    <button type="button" id="cancelAddCourse"
                        class="px-6 py-2 bg-gray-200 text-gray-800 rounded-lg hover:bg-gray-300 transition-colors">
                        Annuler
                    </button>
                    <button type="submit"
                        class="px-6 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                        Créer le Cours
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
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

    document.getElementById('addCourseForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);

        if (!formData.get('title') || !formData.get('category_id')) {
            alert('Veuillez remplir tous les champs obligatoires');
            return;
        }

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