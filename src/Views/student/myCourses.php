<?php
session_start();
require_once "../../../vendor/autoload.php";
use App\Controllers\Student\MyCoursesController;
use App\Models\Student\MyCoursesModel;
    $controller = new MyCoursesController();
    $MyCoursesModel = new MyCoursesModel();
    $enrolledCourses = $controller->getEnrolledCourses($_SESSION['user_id']);
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
    <title>Youdemy - Mes Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

    .glow-hover:hover {
        box-shadow: 0 0 15px rgba(164, 53, 240, 0.3);
    }
    </style>
</head>

<body class="bg-gray-50">
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
        <p><?= htmlspecialchars($_SESSION['success_message']) ?></p>
    </div>
    <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
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
                        <input type="text" placeholder="Rechercher un cours..."
                            class="pl-8 pr-4 py-2 bg-gray-100 border border-gray-200 rounded-full focus:outline-none  focus:ring-primary/30 transition-all">
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400"></i>
                    </div>

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

    <main class="container mx-auto px-4 py-24 space-y-8">
        <div class="flex justify-between items-center">
            <h1 class="text-4xl font-bold text-secondary flex items-center">
                <i class="fas fa-graduation-cap text-primary mr-4"></i>
                Mes Cours
            </h1>
            <div class="flex space-x-4">
                <a href="courseCatalog.php" class="btn btn-primary">
                    <i class="fas fa-plus mr-2"></i>
                    Ajouter un Cours
                </a>
            </div>
        </div>

        <?php if (isset($error)): ?>
        <div class="alert alert-error shadow-lg">
            <div>
                <i class="fas fa-exclamation-triangle"></i>
                <span><?= htmlspecialchars($error) ?></span>
            </div>
        </div>
        <?php endif; ?>

        <?php if (empty($enrolledCourses)): ?>
        <div class="hero bg-base-200 rounded-xl">
            <div class="hero-content text-center">
                <div class="max-w-md">
                    <div class="w-48 h-48 mx-auto mb-6 bg-primary/10 rounded-full 
                            flex items-center justify-center animate-pulse">
                        <i class="fas fa-book-open text-6xl text-primary"></i>
                    </div>
                    <h2 class="text-2xl font-semibold text-secondary mb-4">
                        Aucun cours pour le moment
                    </h2>
                    <p class="text-gray-600 mb-6">
                        Explorez notre catalogue et commencez votre parcours d'apprentissage !
                    </p>
                    <a href="courseCatalog.php" class="btn btn-primary">
                        Découvrir des Cours
                    </a>
                </div>
            </div>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($enrolledCourses as $course): ?>
            <div class="card bg-white shadow-lg hover:shadow-xl transition-shadow duration-300 
                     group">
                <figure class="relative">
                    <img src="<?= htmlspecialchars($course['image']) ?>" alt="<?= htmlspecialchars($course['title']) ?>"
                        class="w-full h-48 object-cover group-hover:opacity-90 transition-opacity">
                    <div class="absolute top-4 right-4 bg-primary text-white px-3 py-1 rounded-full text-sm">
                        <?= htmlspecialchars($course['category_name']) ?>
                    </div>
                </figure>

                <div class="card-body">
                    <h2 class="card-title text-secondary">
                        <?= htmlspecialchars($course['title']) ?>
                    </h2>
                    <p class="text-gray-600 line-clamp-2">
                        <?= htmlspecialchars($course['description']) ?>
                    </p>

                    <div class="space-y-2 my-4">
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-user-tie text-primary mr-2"></i>
                            <?= htmlspecialchars($course['teacher_name']) ?>
                        </div>
                        <div class="flex items-center text-sm text-gray-600">
                            <i class="fas fa-calendar text-primary mr-2"></i>
                            Inscrit le <?= date('d/m/Y', strtotime($course['enrollment_date'])) ?>
                        </div>
                    </div>

                    <div class="w-full bg-gray-200 rounded-full h-2.5 mb-4">
                        <div class="bg-primary h-2.5 rounded-full" style="width: <?= rand(10, 100) ?>%"></div>
                    </div>

                    <div class="card-actions justify-between space-x-2">
                        <a href="courseContenu.php?id=<?= $course['id'] ?>" class="flex-1 px-4 py-3 rounded-lg 
               bg-primary/10 text-primary 
               hover:bg-primary/20 
               transition-all duration-300 
               flex items-center justify-center 
               font-semibold 
               transform 
               focus:outline-none  focus:ring-primary/50">
                            <i class="fas fa-play mr-2 text-primary"></i>
                            Continuer
                        </a>
                        <button
                            onclick="confirmUnenroll(<?= $course['id'] ?>, '<?= htmlspecialchars(addslashes($course['title'])) ?>')"
                            class="flex-1 px-4 py-3 rounded-lg 
               bg-red-50 text-red-600 
               hover:bg-red-100 
               transition-all duration-300 
               flex items-center justify-center 
               font-semibold 
               transform 
               focus:outline-none  focus:ring-red-300">
                            <i class="fas fa-times mr-2 text-red-600"></i>
                            Annuler
                        </button>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
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

    <script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuButton.addEventListener('click', () => {
        alert('Mobile menu functionality to be implemented');
    });
    </script>

    <script>
    function confirmUnenroll(courseId, courseTitle) {
        Swal.fire({
            title: 'Êtes-vous sûr ?',
            html: `Vous êtes sur le point d'annuler votre inscription au cours <br><b>"${courseTitle}"</b>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#a435f0',
            confirmButtonText: 'Oui, annuler',
            cancelButtonText: 'Non, garder',
            reverseButtons: true,
            focusCancel: true,
            customClass: {
                popup: 'rounded-xl',
                title: 'text-xl',
                htmlContainer: 'text-gray-600',
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = 'unenroll.php';

                const courseIdInput = document.createElement('input');
                courseIdInput.type = 'hidden';
                courseIdInput.name = 'course_id';
                courseIdInput.value = courseId;
                form.appendChild(courseIdInput);

                const titleInput = document.createElement('input');
                titleInput.type = 'hidden';
                titleInput.name = 'course_title';
                titleInput.value = courseTitle;
                form.appendChild(titleInput);

                document.body.appendChild(form);
                form.submit();
            }
        });
    }
    </script>
   <?php if (isset($_SESSION['enrollment_success'])): ?>
<script>
Swal.fire({
    title: 'Inscription Réussie!',
    html: `Vous êtes maintenant inscrit au cours<br><b class="text-primary">"<?= htmlspecialchars($_SESSION['course_title'] ?? '') ?>"</b>`,
    icon: 'success',
    confirmButtonText: 'Super!',
    confirmButtonColor: '#a435f0',
    timer: 3000,
    timerProgressBar: true,
    position: 'top',  
    showConfirmButton: false,
    toast: true,     
    width: '24rem', 
    customClass: {
        popup: 'rounded-xl shadow-xl',
        title: 'text-lg',
        htmlContainer: 'text-sm'
    },
    showClass: {
        popup: 'animate__animated animate__fadeInDown'
    },
    hideClass: {
        popup: 'animate__animated animate__fadeOutUp'
    }
});
</script>
<?php 
unset($_SESSION['enrollment_success']);
unset($_SESSION['course_title']);
endif; 
?>

    <?php if (isset($_SESSION['enrollment_error'])): ?>
    <script>
    Swal.fire({
        title: 'Erreur!',
        text: '<?= htmlspecialchars($_SESSION['enrollment_error']) ?>',
        icon: 'error',
        confirmButtonText: 'OK',
        confirmButtonColor: '#a435f0'
    });
    </script>
    <?php 
    unset($_SESSION['enrollment_error']);
endif; 
?>
    <?php if (isset($_SESSION['unenroll_success'])): ?>
    <script>
    Swal.fire({
        title: '<?= htmlspecialchars($_SESSION['unenroll_success']['title']) ?>',
        text: '<?= htmlspecialchars($_SESSION['unenroll_success']['message']) ?>',
        icon: 'success',
        confirmButtonColor: '#a435f0',
        timer: 3000,
        timerProgressBar: true,
        toast: true,
        position: 'top-end',
        showConfirmButton: false
    });
    </script>
    <?php 
    unset($_SESSION['unenroll_success']);
endif; 
?>

    <?php if (isset($_SESSION['unenroll_error'])): ?>
    <script>
    Swal.fire({
        title: '<?= htmlspecialchars($_SESSION['unenroll_error']['title']) ?>',
        text: '<?= htmlspecialchars($_SESSION['unenroll_error']['message']) ?>',
        icon: 'error',
        confirmButtonColor: '#a435f0'
    });
    </script>
    <?php 
    unset($_SESSION['unenroll_error']);
endif; 
?>
</body>

</html>