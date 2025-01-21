<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Controllers\Student\CourseDetailController;
    $courseId = $_GET['id'] ;
    $controller = new CourseDetailController();
    $data = $controller->getCourseDetails($courseId);
    $course = $data['course'];         
    $content = $data['content'];       
    $teacher = $data['teacher'];       
    $skills = $data['skills'];       
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
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
                    <div class="relative">
                        <input 
                            type="text" 
                            placeholder="Rechercher un cours..." 
                            class="pl-8 pr-4 py-2 bg-gray-100 border border-gray-200 rounded-full focus:outline-none focus:ring-2 focus:ring-primary/30 transition-all"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

                    <div class="flex items-center space-x-3">
                        <a href="../auth/login.php" class="px-4 py-2 text-secondary hover:text-primary nav-hover">
                            Connexion
                        </a>
                        <a href="../auth/register.php" class="px-6 py-2 bg-primary text-white rounded-full hover:bg-secondary glow-hover transition-colors">
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
    </nav>    <main class="container mx-auto px-4 py-24">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="p-6 border-b border-gray-200">
                <h1 class="text-3xl font-bold text-gray-800">
                    <?= htmlspecialchars($course['title']) ?>
                </h1>
                <div class="mt-2 text-gray-600">
                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                        <?= htmlspecialchars($course['category_name']) ?>
                    </span>
                </div>
            </div>

            <div class="p-6">
                <?php if ($content): ?>
                    <?= $content->render() ?>
                <?php else: ?>
                    <div class="alert alert-warning">
                        <p>Contenu non disponible.</p>
                    </div>
                <?php endif; ?>

                <div class="mt-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Description</h2>
                    <p class="text-gray-600">
                        <?= nl2br(htmlspecialchars($course['description'])) ?>
                    </p>
                </div>

                <div class="mt-8 bg-gray-50 p-4 rounded-lg">
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Instructeur</h3>
                    <div class="flex items-center">
                        <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl mr-4">
                            <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                        </div>
                        <div>
                            <h4 class="text-lg font-semibold"><?= htmlspecialchars($teacher['name']) ?></h4>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($teacher['email']) ?></p>
                            <p class="text-gray-600 text-sm"><?= htmlspecialchars($teacher['specialty']) ?></p>
                        </div>
                    </div>
                </div>

                <div class="mt-8">
                    <h3 class="text-xl font-semibold mb-3 text-gray-800">Compétences Acquises</h3>
                    <div class="flex flex-wrap gap-2">
                        <?php foreach ($skills as $skill): ?>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                <?= htmlspecialchars($skill) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>
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
</body>
</html>