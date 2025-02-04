<?php
require_once "../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;
use App\Models\Student\MyCoursesModel;

session_start();
$course = new CourseController();
$courses = $course->index();
$categoryController = new CategoryController();
$MyCoursesModel = new MyCoursesModel();

    

try {
   
 if (isset($_SESSION['user_id'])) {
    $student = $MyCoursesModel->getStudentByUserId($_SESSION['user_id']);
    
    if ($student !== null) {
        $studentName = $student->getUser()->getName();
        
        $studentInitial = strtoupper(substr($studentName, 0, 1));
    }
    }
    
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
    $keyword = filter_input(INPUT_POST, 'q', FILTER_SANITIZE_STRING); 
    $categoryId = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_NUMBER_INT);
    
    $categories = $categoryController->getCategories();
    
    $result = $course->search($keyword, $categoryId, $page);
    $courses = $result['courses'];
 
 } catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
 }
?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Catalogue de Cours</title>

    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#a435f0',
                    secondary: '#1c1d1f',
                }
            }
        }
    }
    </script>

    <style>
    .nav-hover:hover {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }

    .course-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .course-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body class="bg-gray-50">
<nav class="fixed w-full bg-white/90 backdrop-blur-md shadow-md z-50 border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <a href="" class="flex items-center group">
                    <i class="fas fa-graduation-cap text-primary text-2xl mr-2 
                              transition-transform group-hover:rotate-12"></i>
                    <span class="text-2xl font-bold text-transparent bg-clip-text 
                                 bg-gradient-to-r from-primary to-purple-600 
                                 group-hover:from-purple-600 group-hover:to-primary 
                                 transition-all duration-300">
                        Youdemy
                    </span>
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-6">
                <a href="../index.php" class="text-secondary hover:text-primary 
                                               transition-all duration-300 
                                               flex items-center 
                                               group">
                    <i class="fas fa-home mr-2 
                              group-hover:text-primary 
                              transition-colors"></i>
                    Accueil
                </a>
                <a href="courseCatalog.php" class="text-secondary hover:text-primary 
                                                   transition-all duration-300 
                                                   flex items-center 
                                                   group">
                    <i class="fas fa-book-open mr-2 
                              group-hover:text-primary 
                              transition-colors"></i>
                    Catalogue des Cours
                </a>
                <a href="myCourses.php" class="text-secondary hover:text-primary 
                                               transition-all duration-300 
                                               flex items-center 
                                               group">
                    <i class="fas fa-graduation-cap mr-2 
                              group-hover:text-primary 
                              transition-colors"></i>
                    Mes Cours
                </a>
            </div>

            <div class="hidden md:flex items-center space-x-4">
                <div class="relative group">
                    <form method="POST" action="" class="relative">
                        <input 
                            type="text" 
                            name="q" 
                            value="<?= htmlspecialchars($keyword ?? '') ?>"
                            placeholder="Rechercher un cours..."
                            class="pl-10 pr-4 py-2 
                                   bg-gray-100 
                                   border-2 border-transparent 
                                   rounded-full 
                                   focus:border-primary 
                                   focus:ring-2 focus:ring-primary/30 
                                   transition-all duration-300 
                                   w-64"
                        >
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 
                                  text-gray-500 group-focus-within:text-primary 
                                  transition-colors duration-300"></i>
                    </form>
                </div>

                <div class="flex items-center space-x-3">
                    <div class="avatar online">
                        <div class="w-12 h-12 rounded-full ring-2 ring-primary/30 ring-offset-2">
                            <img 
                                src="https://ui-avatars.com/api/?name=<?= urlencode($studentName) ?>" 
                                alt="<?= htmlspecialchars($studentName) ?>"
                                class="rounded-full object-cover"
                            />
                        </div>
                    </div>
                    <div>
                        <span class="text-gray-800 font-semibold text-base block">
                            <?= htmlspecialchars($studentName) ?>
                        </span>
                        <span class="text-gray-500 text-xs">Étudiant</span>
                    </div>
                </div>
            </div>

            <div class="md:hidden">
                <button class="text-primary hover:text-secondary transition-colors" id="mobile-menu-button">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>
</nav>
    <section id="catalog" class="py-24 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-bold text-gray-800 mb-4">
                    Catalogue de Cours
                </h2>
                <p class="text-gray-600 max-w-xl mx-auto">
                    Explorez notre collection de cours conçus pour vous aider à développer
                    de nouvelles compétences et à atteindre vos objectifs professionnels.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php if (!empty($courses)): ?>
                <?php foreach ($courses as $course): ?>
                    <div class="course-card bg-white rounded-xl border border-gray-100 overflow-hidden shadow-lg flex flex-col">
    <?php if (!empty($course['image'])): ?>
    <div class="relative">
        <img src="<?= htmlspecialchars($course['image']) ?>"
            alt="<?= htmlspecialchars($course['title']) ?>" class="w-full h-56 object-cover">
        <div class="absolute top-4 right-4 bg-white/80 px-3 py-1 rounded-full">
            <span class="text-xs font-medium text-gray-700">
                <?= htmlspecialchars($course['category_name'] ?? 'Général') ?>
            </span>
        </div>
    </div>
    <?php endif; ?>

    <div class="p-6 flex flex-col flex-grow">
        <h3 class="text-xl font-semibold text-gray-800 mb-3">
            <?= htmlspecialchars($course['title']) ?>
        </h3>

        <div class="flex items-center text-gray-600 text-sm mb-3">
            <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>
            <?= htmlspecialchars($course['teacher_name'] ?? 'Instructeur non défini') ?>
        </div>

        <p class="text-gray-600 text-sm mb-4 flex-grow line-clamp-3">
            <?= htmlspecialchars($course['short_description'] ?? $course['description']) ?>
        </p>

        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center text-gray-600 text-sm">
                <i class="fas fa-users text-primary mr-2"></i>
                <?= $course['student_count'] ?? 0 ?> Étudiants
            </div>
            <span class="text-sm font-semibold text-primary">
                <?= htmlspecialchars($course['price'] ?? 'Gratuit') ?>
            </span>
        </div>

        <a href="courseDetail.php?id=<?= $course['id'] ?>" 
           class="w-full block text-center bg-primary text-white px-4 py-3 rounded-lg 
                  hover:bg-secondary transition-colors mt-auto">
            Voir les détails
        </a>
    </div>
</div>
                <?php endforeach; ?>
                <?php else: ?>

                <div class="col-span-full text-center bg-white p-12 rounded-xl shadow-md">
                    <i class="fas fa-graduation-cap text-5xl text-primary mb-4"></i>
                    <p class="text-xl text-gray-700 mb-2">
                        Aucun cours disponible pour le moment
                    </p>
                    <p class="text-gray-500">
                        Restez à l'écoute, de nouveaux cours arrivent bientôt !
                    </p>
                </div>
                <?php endif; ?>
            </div>
            <div class="flex justify-center items-center gap-4 mt-8">
                <?php if($result['currentPage'] > 1): ?>
                <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $result['currentPage'] - 1 ?>"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 flex items-center">
                    <i class="fas fa-chevron-left mr-2"></i> Précédent
                </a>
                <?php endif; ?>

                <div class="flex gap-2">
                    <?php for($i = 1; $i <= $result['totalPages']; $i++): ?>
                    <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $i ?>"
                        class="px-4 py-2 rounded-lg <?= $i == $result['currentPage'] ? 'bg-primary text-white' : 'bg-gray-200 hover:bg-gray-300' ?>">
                        <?= $i ?>
                    </a>
                    <?php endfor; ?>
                </div>

                <?php if($result['currentPage'] < $result['totalPages']): ?>
                <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $result['currentPage'] + 1 ?>"
                    class="px-4 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 flex items-center">
                    Suivant <i class="fas fa-chevron-right ml-2"></i>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <footer class="bg-secondary text-base-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg  mb-4  font-bold">À propos de Youdemy</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Qui sommes-nous</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Carrières</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Presse</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Tutoriels</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Communauté</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Forum</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Événements</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Partenaires</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-accent text-center">
                <p>&copy; 2023 Youdemy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
    <script>
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        const mobileMenu = document.getElementById('mobile-menu');
        mobileMenu.classList.toggle('hidden');
    });
    </script>
</body>

</html>