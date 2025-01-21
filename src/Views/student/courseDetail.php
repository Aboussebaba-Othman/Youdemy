<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Controllers\Student\CourseDetailController;


    $courseId = $_GET['id'] ?? null;


    $controller = new CourseDetailController();
    $data = $controller->getCourseDetails($courseId);

    $course = $data['course'];
    $teacher = $data['teacher'];
    $skills = $data['skills'];
    $isLoggedIn = $data['isLoggedIn'];
    $isEnrolled = $data['isEnrolled'];


?>
<!DOCTYPE html>
<html lang="fr" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - <?= htmlspecialchars($course['title']) ?></title>
    
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
        .course-detail-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .course-detail-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .skill-badge {
            transition: transform 0.3s ease;
        }
        .skill-badge:hover {
            transform: scale(1.05);
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
    </nav>
    <section class="container mx-auto px-4 py-24">
        <div class="course-detail-card bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="bg-gradient-to-r from-primary to-secondary text-white p-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-4xl font-bold mb-4">
                            <?= htmlspecialchars($course['title']) ?>
                        </h1>
                        <div class="flex items-center space-x-3">
                            <span class="bg-white/20 px-4 py-2 rounded-full text-sm font-semibold">
                                <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                            <span class="bg-green-500 px-4 py-2 rounded-full text-sm font-semibold">
                                Niveau: Intermédiaire
                            </span>
                        </div>
                    </div>
                    
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 p-8">
                <div>
                    <img 
                        src="<?= htmlspecialchars($course['image']) ?>" 
                        alt="<?= htmlspecialchars($course['title']) ?>" 
                        class="w-full h-96 object-cover rounded-2xl shadow-lg transform transition hover:scale-105"
                    >
                </div>

                <div>
                    <div class="mb-8">
                        <h2 class="text-2xl font-semibold mb-4 text-gray-800">Description</h2>
                        <p class="text-gray-600 leading-relaxed">
                            <?= nl2br(htmlspecialchars($course['description'])) ?>
                        </p>
                    </div>

                    <div class="bg-gray-50 p-6 rounded-2xl mb-8">
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Instructeur</h3>
                        <div class="flex items-center">
                            <div class="w-20 h-20 rounded-full bg-primary text-white flex items-center justify-center text-3xl mr-6">
                                <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold text-gray-900"><?= htmlspecialchars($teacher['name']) ?></h4>
                                <p class="text-gray-600 text-sm flex items-center">
                                    <i class="fas fa-envelope mr-2"></i>
                                    <?= htmlspecialchars($teacher['email']) ?>
                                </p>
                                <p class="text-gray-600 text-sm">
                                    <?= htmlspecialchars($teacher['specialty']) ?>
                                </p>
                            </div>
                        </div>
                    </div>

                    
                    <div>
                        <h3 class="text-xl font-semibold mb-4 text-gray-800">Compétences Acquises</h3>
                        <div class="flex flex-wrap gap-3">
                            <?php foreach ($skills as $skill): ?>
                                <span class="skill-badge bg-blue-100 text-blue-800 px-4 py-2 rounded-full text-sm font-medium hover:bg-blue-200">
                                    <?= htmlspecialchars($skill) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            
            <div class="p-8 border-t border-gray-200">
                <?php if ($isLoggedIn): ?>
                    <?php if ($isEnrolled): ?>
                        <button disabled class="w-full bg-green-500 text-white py-4 rounded-lg cursor-not-allowed flex items-center justify-center">
                            <i class="fas fa-check mr-3"></i>
                            Déjà inscrit
                        </button>
                    <?php else: ?>
                        <form action="enroll.php" method="POST">
                            <input type="hidden" name="course_id" value="<?= $course['id'] ?>">
                            <button type="submit" class="w-full bg-primary text-white py-4 rounded-lg hover:bg-secondary transition duration-300 flex items-center justify-center">
                                <i class="fas fa-graduation-cap mr-3"></i>
                                S'inscrire au cours
                            </button>
                        </form>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/auth/login.php" class="w-full bg-primary text-white py-4 rounded-lg hover:bg-secondary transition duration-300 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-3"></i>
                        Connectez-vous pour vous inscrire
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
</body>
</html>