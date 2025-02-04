<?php 
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../../vendor/autoload.php";

use App\Controllers\Teacher\TeacherController;
use App\Controllers\Teacher\CourseController;
use App\Models\Teacher\CourseModel;

try {
    $courseController = new CourseController();
    $data = $courseController->getHomeData();
    $courseModel = new CourseModel();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['course_id'])) {
            $courseController->deleteCourse($_POST['course_id']);
        } else {
            $courseController->handleAddCourse();
        }
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
    if (isset($_SESSION['user_id'])) {
        $teacher = $courseModel->getTeacherByUserId($_SESSION['user_id']);
        
        if ($teacher !== null) {
            $teacherName = $teacher->getUser()->getName();
            
            $teacherInitial = strtoupper(substr($teacherName, 0, 1));
        }
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
    <!-- Add this in the head section -->
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#6366f1',
                        light: '#a5b4fc',
                        dark: '#4338ca'
                    }
                }
            }
        }
    }
    </script>
    <style>
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #6366f1;
        border-radius: 4px;
    }

    .student-row {
        transition: all 0.3s ease;
    }

    .student-row:hover {
        background-color: #f5f5ff;
    }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <?php if (isset($_SESSION['error'])): ?>
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
        <?= htmlspecialchars($_SESSION['error']) ?>
        <?php unset($_SESSION['error']); ?>
    </div>
    <?php endif; ?>
    <div class="flex h-screen overflow-hidden">
        <div class="w-72 bg-gradient-to-b from-primary to-primary-dark text-white shadow-2xl">
            <div class="p-6 border-b border-white/20 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-3xl font-bold tracking-wider">
                    <span class="text-white">Youde</span><span class="text-yellow-300">my</span>
                </h1>
            </div>

            <nav class="mt-10 space-y-2 px-4">
                <a href="home.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg bg-white/10 text-yellow-300">
                    <i class="fas fa-book w-6 text-blue-300"></i>
                    <span>Mes Cours</span>
                </a>
                <a href="statistiques.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-chart-line w-6 text-blue-300"></i>
                    <span>Statistiques</span>
                </a>
                <a href="studentsList.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-users w-6"></i>
                    <span>Liste des Étudiants</span>
                </a>


                <div class="my-4 border-t border-white/10"></div>


                <a href="../auth/login.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg 
              bg-red-500/20 hover:bg-red-500/30 
              text-red-300 hover:text-red-200
              transition-all duration-300 
              group">
                    <i class="fas fa-sign-out-alt w-6 transform group-hover:-translate-x-1 transition-transform"></i>
                    <span class="font-medium">Déconnexion</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="bg-white shadow-md border-b border-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <h2
                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">
                    Mes Courses
                </h2>

                <div class="flex items-center space-x-3">
                    <div class="avatar online">
                        <div class="w-12 h-12 rounded-full ring-2 ring-primary/30 ring-offset-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($teacherName) ?>"
                                alt="<?= htmlspecialchars($teacherName) ?>" class="rounded-full object-cover" />
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-800 font-semibold text-base block">
                            <?= htmlspecialchars($teacherName) ?>
                        </span>
                        <span class="text-gray-500 text-xs">Enseignant</span>
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
                            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 flex flex-col">
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

                            <div class="p-6 flex-grow flex flex-col">
                                <h3 class="text-xl font-bold text-gray-900 mb-2">
                                    <?= htmlspecialchars($course['title']) ?>
                                </h3>
                                <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                                    <?= htmlspecialchars(substr($course['description'], 0, 130)) . 
                    (strlen($course['description']) > 120 ? '...' : '') ?>
                                </p>

                                <div class="flex items-center justify-between mb-4 mt-auto">
                                    <div class="flex items-center space-x-2">
                                        <div class="p-2 rounded-full bg-blue-50">
                                            <i class="fas fa-users text-blue-500"></i>
                                        </div>
                                        <span class="text-sm text-gray-600">
                                            <?= $course['student_count'] ?> étudiants
                                        </span>
                                    </div>
                                    <div class="flex text-yellow-400">
                                        <?php for($i = 0; $i < 5; $i++): ?>
                                        <i class="fas fa-star text-sm"></i>
                                        <?php endfor; ?>
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-2 pt-4 border-t">
                                    <a href="edit_course.php?id=<?= $course['id'] ?>"
                                        class="inline-flex items-center justify-center px-4 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit mr-2"></i>
                                        Modifier
                                    </a>

                                    <form action="" method="post" class="inline">
                                        <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition-colors">
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
        <div class="bg-white w-full max-w-3xl rounded-2xl shadow-2xl p-8 max-h-[90vh] overflow-y-auto m-4 relative">
            <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-100">
                <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                    <i class="fas fa-plus-circle text-primary mr-4"></i>
                    Créer un Nouveau Cours
                </h2>
                <button id="closeModalBtn" class="text-gray-500 hover:text-gray-900 transition-colors">
                    <i class="fas fa-times text-2xl"></i>
                </button>
            </div>

            <form action="" method="post" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Titre du Cours *</label>
                        <div class="relative">
                            <i class="fas fa-book absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="text" name="title" required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                            transition duration-300" placeholder="Ex: Développement Web Complet">
                        </div>
                    </div>

                    <div>
                        <label class="block text-gray-700 font-semibold mb-2">Catégorie *</label>
                        <div class="relative">
                            <i
                                class="fas fa-layer-group absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <select name="category_id" required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                            transition duration-300">
                                <option value="">Sélectionnez une catégorie</option>
                                <?php foreach ($data['categories'] as $category): ?>
                                <option value="<?= htmlspecialchars($category['id']) ?>">
                                    <?= htmlspecialchars($category['title']) ?>
                                </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold mb-2">Description *</label>
                    <textarea name="description" required rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg 
                    focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                    transition duration-300" placeholder="Décrivez brièvement votre cours..."></textarea>
                </div>

                <div class="space-y-4">
                    <label class="block text-gray-700 font-semibold mb-2">Type de Contenu du Cours *</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="contentType" value="video" class="form-radio text-primary"
                                required>
                            <span class="ml-2 text-gray-700">Vidéo</span>
                        </label>
                        <label class="flex items-center cursor-pointer">
                            <input type="radio" name="contentType" value="document" class="form-radio text-primary">
                            <span class="ml-2 text-gray-700">Document</span>
                        </label>
                    </div>

                    <div id="videoContent" class="hidden mt-4">
                        <div class="relative">
                            <i
                                class="fas fa-video absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="url" name="videoUrl" class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                            transition duration-300" placeholder="URL de la vidéo (YouTube, Vimeo)">
                        </div>
                        <p class="text-sm text-gray-500 mt-2">Collez l'URL de votre vidéo YouTube ou Vimeo</p>
                    </div>

                    <div id="documentContent" class="hidden space-y-4 mt-4">

                        <textarea name="documentContent" rows="10" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg 
                        focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                        transition duration-300" placeholder="Contenu du document..."></textarea>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="block text-gray-700 font-semibold">Image de Couverture *</label>
                    <div class="flex items-center space-x-4">
                        <div class="relative flex-1">
                            <i
                                class="fas fa-image absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                            <input type="url" name="coverImageUrl" placeholder="Entrez l'URL de l'image de couverture"
                                required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                            focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                            transition duration-300">
                        </div>
                    </div>
                    <div id="imagePreview" class="hidden mt-4">
                        <img src="" alt="Aperçu" class="w-full h-64 object-cover rounded-lg shadow-md">
                    </div>
                    <p class="text-sm text-gray-500">Format recommandé : 1280x720px, JPG ou PNG</p>
                </div>

                <div class="space-y-4">
                    <label class="block text-gray-700 font-semibold mb-2">Tags</label>
                    <div class="flex flex-wrap gap-3">
                        <?php foreach ($data['tags'] as $tag): ?>
                        <label class="inline-flex items-center bg-gray-100 px-3 py-1.5 rounded-full cursor-pointer">
                            <input type="checkbox" name="tags[]" value="<?= htmlspecialchars($tag['id']) ?>"
                                class="form-checkbox text-primary mr-2">
                            <span class="text-sm text-gray-700"><?= htmlspecialchars($tag['title']) ?></span>
                        </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                    <button type="button" id="cancelAddCourse" class="px-6 py-2.5 bg-gray-100 text-gray-800 rounded-lg 
                    hover:bg-gray-200 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-primary text-white rounded-lg 
                    hover:bg-primary-dark transition-colors">
                        Créer le Cours
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const coverImageInput = document.querySelector('input[name="coverImageUrl"]');
        const imagePreview = document.getElementById('imagePreview');
        const imagePreviewImg = imagePreview.querySelector('img');


        // Image Preview
        coverImageInput.addEventListener('input', function() {
            if (this.value) {
                imagePreviewImg.src = this.value;
                imagePreview.classList.remove('hidden');
            } else {
                imagePreview.classList.add('hidden');
            }
        });


    });
    </script>

    <script>
    // Content type handling
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

    // Modal handling
    const addCourseModal = document.getElementById('addCourseModal');
    const addCourseBtn = document.querySelector('[data-modal-target="addCourseForm"]');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const cancelAddCourseBtn = document.getElementById('cancelAddCourse');

    function openModal() {
        addCourseModal.classList.remove('hidden');
        addCourseModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModal() {
        addCourseModal.classList.remove('flex');
        addCourseModal.classList.add('hidden');
        document.body.style.overflow = '';
        document.querySelector('form').reset();
    }

    addCourseBtn.addEventListener('click', openModal);
    closeModalBtn.addEventListener('click', closeModal);
    cancelAddCourseBtn.addEventListener('click', closeModal);

    // Form validation
    function validateForm(formData) {
        const requiredFields = {
            'title': 'Le titre',
            'category_id': 'La catégorie',
            'description': 'La description',
            'contentType': 'Le type de contenu',
            'coverImageUrl': "L'image de couverture"
        };

        const errors = [];

        Object.entries(requiredFields).forEach(([field, label]) => {
            if (!formData.get(field)) {
                errors.push(`${label} est requis`);
            }
        });

        return errors;
    }

    // Form submission handling
    document.querySelector('form[method="post"]').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const errors = validateForm(formData);

        if (errors.length > 0) {
            Swal.fire({
                title: 'Champs requis',
                html: errors.join('<br>'),
                icon: 'warning',
                confirmButtonText: 'OK',
                confirmButtonColor: '#3085d6'
            });
            return;
        }

        Swal.fire({
            title: 'Création en cours...',
            text: 'Veuillez patienter...',
            allowOutsideClick: false,
            showConfirmButton: false,
            willOpen: () => {
                Swal.showLoading();
            }
        });

        this.submit();
    });

    // Delete course handling
    document.querySelectorAll('form').forEach(form => {
        if (form.querySelector('input[name="course_id"]')) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Êtes-vous sûr?',
                    text: "Cette action est irréversible!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Oui, supprimer!',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        }
    });


    // Modal close on outside click and escape key
    addCourseModal.addEventListener('click', (e) => {
        if (e.target === addCourseModal) {
            closeModal();
        }
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !addCourseModal.classList.contains('hidden')) {
            closeModal();
        }
    });
    </script>
</body>

</html>