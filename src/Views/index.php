<?php
require_once "../../vendor/autoload.php";
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
                            <input type="text" placeholder="Rechercher un cours..." class="w-full pl-10 pr-4 py-2 bg-base-200 border border-accent rounded-full focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                            <i class="fas fa-search absolute left-3 top-3 text-accent"></i>
                        </div>
                    </div>
                </div>
    
                <div class="hidden md:flex items-center space-x-4">
                    <button class="px-4 py-2 text-secondary hover:text-primary transition-colors duration-200 hover:bg-base-200 rounded-md"><a href="auth/login.php">Login</a></button>
                    <button class="px-6 py-2 bg-primary text-base-300 rounded-full hover:bg-secondary transition-colors duration-200 shadow-md hover:shadow-lg"><a href="auth/register.php">Register</a></button>
                </div>
    
                <div class="md:hidden">
                    <button class="text-primary hover:text-secondary transition-colors" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <div class="hero min-h-screen" style="background-image: url(https://images.unsplash.com/photo-1524178232363-1fb2b075b655?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80);">
        <div class="hero-overlay bg-opacity-60"></div>
        <div class="hero-content text-center text-neutral-content">
            <div class="max-w-md">
                <h1 class="mb-5 text-5xl font-bold">Welcome to Youdemy</h1>
                <p class="mb-5">Revolutionize your learning experience with our interactive and personalized online courses.</p>
                <button class="btn btn-primary">Get Started</button>
            </div>
        </div>
    </div>

    <section id="catalog" class="py-20">
    <div class="container mx-auto px-4">
        <?php if (isset($courses) && is_array($courses) && count($courses) > 0): ?>
            <h2 class="text-3xl font-bold mb-8 text-center">Popular Courses</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white shadow-lg rounded-lg overflow-hidden ">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?=$course['image']?>" 
                                 alt="<?= htmlspecialchars($course['title']) ?>" 
                                 class="w-full">
                        <?php endif; ?>
                        
                        <div class="p-4">
                            <h3 class="font-bold text-lg mb-2"><?= htmlspecialchars($course['title']) ?></h3>
                            <p class="text-gray-600 mb-4"><?= htmlspecialchars($course['description']) ?></p>
                            <a href="#" class="text-blue-600 font-bold">Learn More &rarr;</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-center">No courses available at the moment.</p>
        <?php endif; ?>
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
                        <p>"Youdemy has transformed my career. The courses are top-notch and the instructors are amazing!"</p>
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
                        <p>"The flexibility of Youdemy allowed me to learn new skills while working full-time. Highly recommended!"</p>
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
                        <p>"As an instructor, I love how Youdemy empowers me to share my knowledge with students worldwide."</p>
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

    <section class="py-12 bg-secondary text-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold mb-8 text-center">Youdemy in Numbers</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                <div>
                    <p class="text-4xl font-bold">100,000+</p>
                    <p class="text-xl mt-2">Students</p>
                </div>
                <div>
                    <p class="text-4xl font-bold">1,000+</p>
                    <p class="text-xl mt-2">Courses</p>
                </div>
                <div>
                    <p class="text-4xl font-bold">500+</p>
                    <p class="text-xl mt-2">Expert Instructors</p>
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
                <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i class="fab fa-facebook"></i></a>
                <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i class="fab fa-instagram"></i></a>
                <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i class="fab fa-linkedin"></i></a>
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