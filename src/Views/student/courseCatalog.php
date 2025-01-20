<?php
require_once "../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;

try {

    $course = new CourseController();
    $courses = $course->index();
    $categoryController = new CategoryController();
    $categories = $categoryController->getCategories();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

?>

<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Online Learning Platform</title>
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
</head>

<body class="bg-white">
    <nav class="fixed w-full bg-base-300 shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="flex items-center transition-transform hover:scale-105">
                        <i class="fas fa-graduation-cap text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-primary font-serif font-bold">Youdemy</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center flex-1 px-8">

                    <div class="flex-1 px-8">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher un cours..."
                                class="w-full pl-10 pr-4 py-2 bg-base-200 border border-accent rounded-full focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                            <i class="fas fa-search absolute left-3 top-3 text-accent"></i>
                        </div>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                <button
                        class="px-4 py-2 text-secondary hover:text-primary transition-colors duration-200 hover:bg-base-200 rounded-md"><a
                            href="">Mes course</a></button>
                    <button
                        class="px-4 py-2 text-secondary hover:text-primary transition-colors duration-200 hover:bg-base-200 rounded-md"><a
                            href="auth/login.php">Login</a></button>
                    <button
                        class="px-6 py-2 bg-primary text-base-300 rounded-full hover:bg-secondary transition-colors duration-200 shadow-md hover:shadow-lg"><a
                            href="auth/register.php">Register</a></button>
                </div>

                <div class="md:hidden">
                    <button class="text-primary hover:text-secondary transition-colors" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section id="catalog" class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Available Courses
                </h2>
                <p class="text-gray-600 max-w-xl mx-auto">
                    Explore our collection of courses designed to help you develop
                    new skills and achieve your professional goals.
                </p>
            </div>

            <?php if (isset($courses) && is_array($courses) && count($courses) > 0): ?>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-xl border border-gray-100 overflow-hidden">
                    <?php if (!empty($course['image'])): ?>
                    <div class="relative">
                        <img src="<?= htmlspecialchars($course['image']) ?>"
                            alt="<?= htmlspecialchars($course['title']) ?>" class="w-full h-48 object-cover">
                        <div class="absolute top-4 right-4 bg-white/80 px-3 py-1 rounded-full">
                            <span class="text-xs font-medium text-gray-700">
                                <?= htmlspecialchars($course['category_name'] ?? 'General') ?>
                            </span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="p-5">
                        <div class="mb-4">
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">
                                <?= htmlspecialchars($course['title']) ?>
                            </h3>
                            <div class="flex items-center text-gray-600 text-sm">
                                <i class="fas fa-chalkboard-teacher text-blue-500 mr-2"></i>
                                <?= htmlspecialchars($course['teacher_name'] ?? 'Undefined Instructor') ?>
                            </div>
                        </div>

                        <p class="text-gray-600 text-sm mb-4 line-clamp-3">
                            <?= htmlspecialchars($course['short_description'] ?? $course['description']) ?>
                        </p>

                        <div class="flex items-center text-gray-600 text-sm mb-4">
                            <i class="fas fa-users text-blue-500 mr-2"></i>
                            <?= $course['students_count'] ?? 0 ?> Students
                        </div>

                        <a href="courseDetail.php?id=<?= $course['id'] ?>" class="w-full text-center block bg-blue-500 text-white 
                                       px-4 py-2 rounded-lg text-sm 
                                       hover:bg-blue-600 transition">
                            View Details
                        </a>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <?php else: ?>
            <div class="text-center bg-white p-12 rounded-xl shadow-md">
                <i class="fas fa-graduation-cap text-5xl text-blue-500 mb-4"></i>
                <p class="text-xl text-gray-700 mb-2">
                    No courses available at the moment
                </p>
                <p class="text-gray-500">
                    Stay tuned, new courses are coming soon!
                </p>
            </div>
            <?php endif; ?>
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