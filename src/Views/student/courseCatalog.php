<?php
require_once "../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;

session_start();

try {
    $course = new CourseController();
    $categoryController = new CategoryController();
    
    $page = filter_input(INPUT_GET, 'page', FILTER_SANITIZE_NUMBER_INT) ?? 1;
    $keyword = filter_input(INPUT_POST, 'q', FILTER_SANITIZE_STRING);
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $result = $course->search($keyword, 1); 
    } else {
        $keyword = filter_input(INPUT_GET, 'keyword', FILTER_SANITIZE_STRING);
        $result = $course->search($keyword, $page);
    }
    
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
    <nav class="fixed w-full bg-white shadow-md z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="" class="flex items-center nav-hover">
                        <i class="fas fa-graduation-cap text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-primary font-serif">Youdemy</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="../index.php" class="text-secondary hover:text-primary transition-colors nav-hover">
                        <i class="fas fa-home mr-2"></i>Accueil
                    </a>
                    <a href="courseCatalog.php" class="text-secondary hover:text-primary transition-colors nav-hover">
                        <i class="fas fa-book-open mr-2"></i>Catalogue des Cours
                    </a>
                    <a href="myCourses.php" class="text-secondary hover:text-primary transition-colors nav-hover">
                        <i class="fas fa-graduation-cap mr-2"></i>Mes Cours
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <form method="POST" action="" class="relative">
                        <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>"
                            placeholder="Rechercher un cours..."
                            class="pl-8 pr-4 py-2 bg-gray-100 border border-gray-200 rounded-full">
                        <button type="submit" name="search">
                            <i class="fas fa-search text-gray-400"></i>
                        </button>
                    </form>


                    <div class="flex items-center space-x-3">
                        <a href="../auth/login.php" class="px-4 py-2 text-secondary hover:text-primary nav-hover">
                            Connexion
                        </a>
                        <a href="../auth/register.php"
                            class="px-6 py-2 bg-primary text-white rounded-full hover:bg-secondary glow-hover transition-colors">
                            S'inscrire
                        </a>
                    </div>
                </div>

                <div class="md:hidden">
                    <button class="text-primary hover:text-secondary" id="mobile-menu-button">
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
                <div class="course-card bg-white rounded-xl border border-gray-100 overflow-hidden shadow-lg">
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

                    <div class="p-6">
                        <h3 class="text-xl font-semibold text-gray-800 mb-3">
                            <?= htmlspecialchars($course['title']) ?>
                        </h3>

                        <div class="flex items-center text-gray-600 text-sm mb-3">
                            <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>
                            <?= htmlspecialchars($course['teacher_name'] ?? 'Instructeur non défini') ?>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?= htmlspecialchars($course['short_description'] ?? $course['description']) ?>
                        </p>

                        <div class="flex justify-between items-center mb-4">
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-users text-primary mr-2"></i>
                                <?= $course['students_count'] ?? 0 ?> Étudiants
                            </div>
                            <span class="text-sm font-semibold text-primary">
                                <?= htmlspecialchars($course['price'] ?? 'Gratuit') ?>
                            </span>
                        </div>

                        <a href="courseDetail.php?id=<?= $course['id'] ?>" class="w-full block text-center bg-primary text-white px-4 py-3 rounded-lg 
                                           hover:bg-secondary transition-colors">
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