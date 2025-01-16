<?php
require_once "../../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;

$course = new CourseController();
$courses = $course->index();

if (isset($_GET['action']) && $_GET['action'] === 'deleteCourse' && isset($_GET['id'])) {
    $courseId = intval($_GET['id']);
    try {
        $course->deleteControllerCourse($courseId);
        $_SESSION['success_message'] = "Cours supprimé avec succès";
    } catch (Exception $e) {
        $_SESSION['error_message'] = "Erreur lors de la suppression du cours : " . $e->getMessage();
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
                <a href="../home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
                <a href="../user/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="../cour/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="../tag/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="../categorie/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="../validation/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-auto">
            <header class="bg-white shadow">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold">Pages de Gestion</h2>
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
                            class="flex-grow px-4 py-2 border rounded-lg"
                        >
                        <select class="px-4 py-2 border rounded-lg">
                            <option>Toutes les catégories</option>
                            <option>Développement Web</option>
                            <option>Data Science</option>
                            <option>Design</option>
                        </select>
                        <select class="px-4 py-2 border rounded-lg">
                            <option>Tous les niveaux</option>
                            <option>Débutant</option>
                            <option>Intermédiaire</option>
                            <option>Avancé</option>
                        </select>
                    </div>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php 
            if (isset($courses) && is_array($courses) && count($courses) > 0):
                foreach ($courses as $course):
            ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="h-48 bg-gray-200 relative">
                        <?php if (!empty($course['image'])): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>" 
                                 alt="<?= htmlspecialchars($course['title']) ?>"
                                 class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-4xl"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="p-4">
                        <h3 class="text-lg font-semibold mb-2">
                            <?= htmlspecialchars($course['title']) ?>
                        </h3>
                        <p class="text-gray-600 text-sm mb-2">
                            Par: <span class="text-red-900"><?= htmlspecialchars($course['teacher_name']) ?></span>
                        </p>
                        <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                            <?= htmlspecialchars($course['category_name']) ?>
                        </span>
                        
                        <div class="mt-4 flex justify-end space-x-2">
                            <a href="edit.php?id=<?= $course['id'] ?>" 
                               class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">
                               <i class="fas fa-edit"></i>
                            </a>
                            <a href="?action=deleteCourse&id=<?= $course['id'] ?>" 
                               class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600"
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
