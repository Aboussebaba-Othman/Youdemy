<?php
session_start();
require_once "../../../vendor/autoload.php";
use App\Models\Student\MyCoursesModel;
use App\Controllers\Student\CourseDetailController;


$courseId = $_GET['id'] ;
$controller = new CourseDetailController();

$data = $controller->getCourseDetails($courseId);
$enrolledCourses = $data['enrolledCourses'] ?? []; 

$course = $data['course'];         
$content = $data['content'];       
$teacher = $data['teacher'];       
$skills = $data['skills'];


$MyCoursesModel = new MyCoursesModel();

if (isset($_SESSION['user_id'])) {
    $student = $MyCoursesModel->getStudentByUserId($_SESSION['user_id']);
    
    if ($student !== null) {
        $studentName = $student->getUser()->getName();
        $studentInitial = strtoupper(substr($studentName, 0, 1));
    }
}   


?>

<!DOCTYPE html>
<html lang="fr" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Contenu de Cours</title>

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
    .course-content {
        background: linear-gradient(to right, #ffffff, #f8f8ff);
        border-radius: 1rem;
        box-shadow: 0 4px 20px rgba(164, 53, 240, 0.1);
    }

    .course-header {
        background: linear-gradient(120deg, #a435f0, #8435f0);
        padding: 2rem;
        border-radius: 1rem 1rem 0 0;
        color: white;
    }

    .course-description {
        line-height: 1.8;
        color: #4a5568;
    }

    .instructor-card {
        background: linear-gradient(to right, #f9fafb, #f3f4f6);
        border-left: 4px solid #a435f0;
        transition: all 0.3s ease;
    }

    .instructor-card:hover {
        box-shadow: -5px 5px 15px rgba(164, 53, 240, 0.1);
    }

    .skill-badge {
        transition: all 0.3s ease;
        cursor: default;
    }

    .skill-badge:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 10px rgba(164, 53, 240, 0.2);
    }

    /* Style pour la sidebar */
    .sidebar {
        position: sticky;
        top: 6rem;
        height: calc(100vh - 8rem);
        transition: all 0.3s ease;
    }

    .sidebar-header {
        background: linear-gradient(135deg, #a435f0, #8435f0);
    }

    .course-list-item {
        transition: all 0.3s ease;
        border-left: 4px solid transparent;
    }

    .course-list-item:hover {
        border-left-color: #a435f0;
        background-color: rgba(164, 53, 240, 0.05);
    }

    .course-list-item.active {
        border-left-color: #a435f0;
        background-color: rgba(164, 53, 240, 0.1);
    }

    /* Animations */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out forwards;
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

                    </form>


                    <div class="relative">
                        <div class="flex items-center">
                            <div class="avatar online mr-3">
                                <div class="w-10 rounded-full">
                                    <img src="https://ui-avatars.com/api/?name=<?= urlencode($studentName) ?>" />
                                </div>
                            </div>
                            <span class="text-gray-700 font-medium"><?= htmlspecialchars($studentName) ?></span>
                        </div>
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
    <main class="container mx-auto px-4 py-24">
        <div class="flex gap-8">
            <div class="w-3/4 animate-fade-in">
                <div class="course-content">
                    <div class="course-header">
                        <h1 class="text-3xl font-bold mb-4">
                            <?= htmlspecialchars($course['title']) ?>
                        </h1>
                        <span class="inline-flex items-center px-4 py-2 bg-white/20 rounded-full text-sm font-medium">
                            <i class="fas fa-folder-open mr-2"></i>
                            <?= htmlspecialchars($course['category_name']) ?>
                        </span>
                    </div>

                    <div class="p-8 space-y-10">
                        <div class="bg-gray-50 rounded-xl">
                            <?php if ($content): ?>
                            <?= $content->render() ?>
                            <?php else: ?>
                            <div class="flex items-center justify-center p-8 text-gray-500">
                                <i class="fas fa-clock text-4xl mr-4"></i>
                                <p>Contenu en cours de préparation...</p>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="course-description">
                            <h2 class="text-2xl font-semibold mb-6">
                                <i class="fas fa-info-circle text-primary mr-2"></i>
                                Description du cours
                            </h2>
                            <div class="prose prose-lg max-w-none">
                                <?= nl2br(htmlspecialchars($course['description'])) ?>
                            </div>
                        </div>

                        <div class="instructor-card rounded-xl p-6">
                            <h3 class="text-xl font-semibold mb-6">
                                <i class="fas fa-chalkboard-teacher text-primary mr-2"></i>
                                Votre instructeur
                            </h3>
                            <div class="flex items-center">
                                <div
                                    class="w-20 h-20 bg-primary/20 rounded-full flex items-center justify-center text-primary text-3xl font-bold">
                                    <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                                </div>
                                <div class="ml-6">
                                    <h4 class="text-xl font-semibold"><?= htmlspecialchars($teacher['name']) ?></h4>
                                    <p class="text-gray-600"><?= htmlspecialchars($teacher['specialty']) ?></p>
                                    <p class="text-gray-500 mt-2">
                                        <i class="fas fa-envelope text-primary mr-2"></i>
                                        <?= htmlspecialchars($teacher['email']) ?>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div>
                            <h3 class="text-xl font-semibold mb-6">
                                <i class="fas fa-star text-primary mr-2"></i>
                                Compétences à acquérir
                            </h3>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($skills as $skill): ?>
                                <span
                                    class="skill-badge px-4 py-2 bg-primary/10 text-primary rounded-full text-sm font-medium">
                                    <?= htmlspecialchars($skill) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-1/3">
                <div
                    class="bg-white shadow-xl  sticky top-24 border border-gray-100 flex flex-col h-[calc(100vh-120px)] rounded-xl">
                    
                    <div class="course-header ">
                        <h2 class="text-2xl font-bold mb-2">Mes Cours</h2>
                        <div class="flex items-center text-white/90">
                            <i class="fas fa-book-open mr-2"></i>
                            <p class="text-sm">
                                <?= count($enrolledCourses) ?> cours inscrits
                            </p>
                        </div>
                    </div>

                    <div class="flex-1 overflow-y-auto sidebar-scroll min-h-0">
                        <?php if (!empty($enrolledCourses)): ?>
                        <?php foreach ($enrolledCourses as $course): ?>
                        <a href="courseContenu.php?id=<?= $course['id'] ?>"
                            class="block hover:bg-gray-50 transition-all duration-300">
                            <div class="p-4 border-b group
                            <?= $course['id'] == $courseId 
                                ? 'bg-primary/5 border-l-4 border-primary' 
                                : 'border-l-4 border-transparent hover:border-primary/30' ?>">
                                <div class="flex gap-4">
                                    <div
                                        class="w-20 h-20 bg-gray-100 rounded-lg overflow-hidden shadow-sm flex-shrink-0">
                                        <?php if (!empty($course['image'])): ?>
                                        <img src="<?= htmlspecialchars($course['image']) ?>" alt=""
                                            class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                                        <?php else: ?>
                                        <div class="w-full h-full flex items-center justify-center bg-primary/10">
                                            <i class="fas fa-book text-primary text-xl"></i>
                                        </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="flex-1 min-w-0 space-y-2">
                                        <h3 class="font-medium text-gray-900 leading-tight line-clamp-2">
                                            <?= htmlspecialchars($course['title']) ?>
                                        </h3>

                                        <p class="text-sm text-gray-500 flex items-center">
                                            <i class="fas fa-user-tie text-primary/70 mr-2"></i>
                                            <?= htmlspecialchars($course['teacher_name']) ?>
                                        </p>

                                        <div class="flex items-center">
                                            <span
                                                class="px-3 py-1 bg-primary/10 text-primary text-xs rounded-full font-medium">
                                                <?= htmlspecialchars($course['category_name']) ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        <?php endforeach; ?>
                        <?php else: ?>
                        <div class="h-full flex items-center justify-center p-8">
                            <div class="text-center">
                                <div
                                    class="w-20 h-20 bg-primary/10 rounded-full mx-auto flex items-center justify-center mb-4">
                                    <i class="fas fa-book-open text-primary text-2xl"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">Aucun cours inscrit</h3>
                                <p class="text-gray-500 mb-4">Commencez votre parcours d'apprentissage dès maintenant !
                                </p>
                                <a href="courseCatalog.php"
                                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors">
                                    <i class="fas fa-plus mr-2"></i>
                                    Explorer les cours
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>
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