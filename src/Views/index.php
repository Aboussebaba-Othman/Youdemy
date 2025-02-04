<?php
require_once "../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;

session_start();
$course = new CourseController();
$courses = $course->index();
$statistics = $course->getDashboardData();

 try {
    $categoryController = new CategoryController();
    
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
                    <a href="index.php" class="flex items-center transition-transform hover:scale-105">
                        <i class="fas fa-graduation-cap text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-primary font-serif font-bold">Youdemy</span>
                    </a>
                </div>

                <form method="POST" action="" class="hidden md:flex items-center flex-1 px-8">
                    <div class="flex-1 px-8">
                        <div class="relative">
                            <input type="text" name="q" placeholder="Rechercher un cours..."
                                value="<?= htmlspecialchars($result['keyword'] ?? '') ?>"
                                class="w-full pl-10 pr-4 py-2 bg-base-200 border border-accent rounded-full">
                            <button type="submit" name="search" class="absolute right-3 top-2">
                                <i class="fas fa-search text-accent"></i>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="hidden md:flex items-center space-x-4">
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

    <div class="hero min-h-screen"
        style="background-image: url(https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">Welcome to Youdemy</h1>
                <p class="mb-5">Revolutionize your learning experience with our interactive and personalized online
                    courses.</p>
                <a href="auth/login.php"><button class="btn btn-primary">Get Started</button></a>
            </div>
        </div>
    </div>

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
    <div class="bg-white rounded-2xl overflow-hidden border border-gray-200 shadow-md flex flex-col">
        <div class="relative">
            <img 
                src="<?= htmlspecialchars($course['image']) ?>" 
                alt="<?= htmlspecialchars($course['title']) ?>"
                class="w-full h-56 object-cover"
            >
            <span class="absolute top-4 right-4 
                         bg-primary text-white 
                         px-3 py-1 rounded-full 
                         text-sm font-medium">
                <?= htmlspecialchars($course['category_name']) ?>
            </span>
        </div>

        <div class="p-6 flex flex-col flex-grow">
            <h3 class="text-xl font-bold text-secondary mb-3 line-clamp-2">
                <?= htmlspecialchars($course['title']) ?>
            </h3>

            <p class="text-gray-600 text-sm mb-4 line-clamp-3 flex-grow">
                <?= htmlspecialchars(substr($course['description'], 0, 150) . '...') ?>
            </p>

            <div class="flex items-center space-x-3 mb-3">
                <i class="fas fa-chalkboard-teacher text-primary"></i>
                <p class="text-sm text-gray-600">
                    <?= htmlspecialchars($course['teacher_name']) ?>
                </p>
            </div>

            <div class="flex items-center space-x-3 mb-4">
                <i class="fas fa-users text-primary"></i>
                <span class="text-sm text-gray-600">
                    <?= $course['student_count'] ?? 0 ?> étudiants inscrits
                </span>
            </div>

            <div class="mt-auto pt-4 border-t">
                <a 
                    href="auth/login.php?id=<?= $course['id'] ?>"
                    class="block w-full text-center 
                           px-6 py-3 
                           bg-primary text-white 
                           rounded-lg 
                           transition duration-300 
                           ease-in-out 
                           hover:bg-primary-dark 
                           flex items-center 
                           justify-center 
                           space-x-2"
                >
                    <span>Voir détails</span>
                    <svg 
                        xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor"
                    >
                        <path 
                            stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M14 5l7 7m0 0l-7 7m7-7H3"
                        />
                    </svg>
                </a>
            </div>
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

    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <?php if (!empty($categories)): ?>
            <h2 class="text-3xl font-bold mb-8 text-center">Course Categories</h2>

            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                <?php foreach ($categories as $category): ?>
                <div class="btn btn-outline"><?= htmlspecialchars($category['title']) ?></div>
                <?php endforeach; ?>
            </div>

            <?php endif; ?>
        </div>

    </section>

    <section class="py-12 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Why Choose Youdemy?</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <i class="fas fa-graduation-cap text-4xl mb-4 text-primary"></i>
                        <h3 class="card-title">Expert Instructors</h3>
                        <p>Learn from industry professionals and experienced educators.</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <i class="fas fa-clock text-4xl mb-4 text-primary"></i>
                        <h3 class="card-title">Flexible Learning</h3>
                        <p>Study at your own pace, anytime and anywhere.</p>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body items-center text-center">
                        <i class="fas fa-certificate text-4xl mb-4 text-primary"></i>
                        <h3 class="card-title">Certificates</h3>
                        <p>Earn recognized certificates upon course completion.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-12 bg-gray-100">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Student Reviews</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <p>"Youdemy has transformed my career. The courses are top-notch and the instructors are
                            amazing!"</p>
                        <div class="flex items-center mt-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/initials/svg?seed=JD" alt="John Doe" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold">John Doe</p>
                                <p class="text-sm">Web Developer</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <p>"The flexibility of Youdemy allowed me to learn new skills while working full-time. Highly
                            recommended!"</p>
                        <div class="flex items-center mt-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/initials/svg?seed=JS" alt="Jane Smith" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold">Jane Smith</p>
                                <p class="text-sm">Data Analyst</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card bg-base-100 shadow-xl">
                    <div class="card-body">
                        <p>"As an instructor, I love how Youdemy empowers me to share my knowledge with students
                            worldwide."</p>
                        <div class="flex items-center mt-4">
                            <div class="avatar">
                                <div class="w-12 rounded-full">
                                    <img src="https://api.dicebear.com/6.x/initials/svg?seed=MB" alt="Mike Brown" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="font-bold">Mike Brown</p>
                                <p class="text-sm">Marketing Expert</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-secondary text-white relative overflow-hidden">
    <div class="container mx-auto px-4 relative z-10">
        <h2 class="text-4xl font-bold mb-12 text-center">
            <span class="text-primary">Youdemy</span> en Chiffres
        </h2>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-users text-3xl text-primary"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2 text-primary" id="studentCount">
                    <?= number_format($statistics['total_students']) ?>
                </h3>
                <p class="text-lg text-gray-300">Étudiants</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book-open text-3xl text-primary"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2 text-primary" id="courseCount">
                    <?= number_format($statistics['total_courses']) ?>
                </h3>
                <p class="text-lg text-gray-300">Cours Disponibles</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-chalkboard-teacher text-3xl text-primary"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2 text-primary" id="teacherCount">
                    <?= number_format($statistics['total_teachers']) ?>
                </h3>
                <p class="text-lg text-gray-300">Instructeurs</p>
            </div>

            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-6 text-center transform hover:scale-105 transition-all duration-300">
                <div class="w-16 h-16 bg-primary/20 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-folder text-3xl text-primary"></i>
                </div>
                <h3 class="text-4xl font-bold mb-2 text-primary" id="categoryCount">
                    <?= number_format($statistics['total_category']) ?>
                </h3>
                <p class="text-lg text-gray-300">Catégories</p>
            </div>
        </div>
    </div>
</section>

    <section class="py-12 bg-primary text-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold mb-4">Ready to Start Learning?</h2>
            <p class="mb-8">Join thousands of students and transform your skills today!</p>
            <button class="btn btn-secondary btn-lg">Sign Up Now</button>
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