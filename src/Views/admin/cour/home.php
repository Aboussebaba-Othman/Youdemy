<?php
require_once "../../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;

$course = new CourseController();
$courses = $course->index();

if (isset($_GET['action']) && $_GET['action'] === 'deleteCourse' && isset($_GET['id'])) {
    $courseId = intval($_GET['id']);
    error_log("Attempting to delete course with ID: $courseId");

    try {
        $course->deleteControllerCourse($courseId);
        $_SESSION['success_message'] = "Course deleted successfully.";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Error deleting course: " . $e->getMessage();
    }

    header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
    exit();
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours - Youdemy</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">

    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-md">
            <div class="p-5 border-b">
                <h1 class="text-2xl font-bold text-blue-600">Youdemy Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="../home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
                <a href="../user/home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="../cour/home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="../tag/home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="../categorie/home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="../validation/home.php" class="flex items-center px-4 py-3 hover:bg-gray-100">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold">Gestion des Cours</h2>
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-bell"></i>
                        </button>
                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                <span class="text-gray-600 text-sm">A</span>
                            </div>
                            <span class="text-gray-700">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6">
                <div class="bg-white shadow rounded-lg p-6 mb-6">
                    <div class="flex space-x-4">
                        <input 
                            type="text" 
                            placeholder="Rechercher des cours..." 
                            class="flex-grow px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none"
                        >
                        
                        <select class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option>Toutes les catégories</option>
                            <option>Développement Web</option>
                            <option>Data Science</option>
                            <option>Design</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php 
            if (isset($courses) && is_array($courses) && count($courses) > 0):
                foreach ($courses as $course):
            ?>
                <div class="bg-white rounded-xl shadow-lg transform transition duration-300 hover:scale-105 hover:shadow-2xl">
                    <div class="h-48 bg-gray-200 rounded-t-xl relative overflow-hidden">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                 alt="<?= htmlspecialchars($course['title']) ?>"
                                 class="w-full h-full object-cover transition duration-300 transform hover:scale-110">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-5">
                        <h3 class="text-lg font-bold mb-2 text-gray-800 truncate">
                            <?= htmlspecialchars($course['title']) ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-3">
                            Par: <span class="text-blue-600 font-semibold"><?= htmlspecialchars($course['teacher_name']) ?></span>
                        </p>
                        <div class="flex items-center justify-between">
                            <span class="inline-block bg-blue-100 text-blue-800 text-xs px-3 py-1 rounded-full">
                                <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                            
                            <a href="?action=deleteCourse&id=<?= $course['id'] ?>" 
                               class="text-red-500 hover:text-red-700 transition duration-300"
                               onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                               <i class="fas fa-trash"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <?php
                endforeach;
                else:
                ?>
                <div class="col-span-3 text-center py-8">
                    <i class="fas fa-book-open text-gray-400 text-5xl mb-4"></i>
                    <p class="text-gray-600">Aucun cours n'est disponible pour le moment.</p>
                </div>
                <?php endif; ?>
            </div>
            </div>
        </div>
    </div>

</body>
</html>