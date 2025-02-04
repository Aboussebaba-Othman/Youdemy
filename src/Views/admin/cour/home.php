<?php
require_once "../../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;
use App\Controllers\Admin\CategoryController;

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
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: 'rgb(51, 44, 192)',
                        light: '#c661f3',
                        dark: '#7a2cc0'
                    },
                    secondary: {
                        DEFAULT: '#1c1d1f',
                        light: '#2c2d33'
                    }
                }
            }
        }
    }
    </script>
    <style>
    ::-webkit-scrollbar {
        width: 8px;
    }

    ::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    ::-webkit-scrollbar-thumb {
        background: #a435f0;
        border-radius: 4px;
    }

    ::-webkit-scrollbar-thumb:hover {
        background: #7a2cc0;
    }
    </style>
</head>

<body class="bg-gray-50 antialiased">
    <div class="flex h-screen overflow-hidden">
    <div class="w-64 bg-white shadow-xl border-r overflow-y-auto flex flex-col h-screen">
            <div class="p-6 border-b border-gray-200 sticky top-0 bg-white z-10">
                <h1 class="text-2xl font-bold text-primary">Youdemy Admin</h1>
            </div>

            <nav class="mt-2 flex-grow">
                <?php 
         $currentPath = basename(dirname($_SERVER['PHP_SELF']));

         $menuItems = [
            ['icon' => 'tachometer-alt', 'label' => 'Tableau de Bord', 'link' => '../home.php', 'path' => 'home'],
            ['icon' => 'users', 'label' => 'Gestion Utilisateurs', 'link' => '../user/home.php', 'path' => 'user'],
            ['icon' => 'book', 'label' => 'Gestion Cours', 'link' => '../cour/home.php', 'path' => 'cour'],
            ['icon' => 'tags', 'label' => 'Gestion Tags', 'link' => '../tag/home.php', 'path' => 'tag'],
            ['icon' => 'folder', 'label' => 'Gestion Catégories', 'link' => '../categorie/home.php', 'path' => 'categorie'],
            ['icon' => 'user-check', 'label' => 'Validation Enseignants', 'link' => '../validation/home.php', 'path' => 'validation']
         ];

         foreach ($menuItems as $item):
            $isActive = $currentPath === $item['path'];
         ?>
                <a href="<?= $item['link'] ?>" class="block px-6 py-3 transition-all duration-300 <?= $isActive 
                ? 'bg-primary text-white font-medium border-l-4 border-yellow-400' 
                : 'text-gray-700 hover:bg-primary/5 hover:text-primary' ?>">
                    <i class="fas fa-<?= $item['icon'] ?> w-6 mr-3"></i>
                    <span><?= $item['label'] ?></span>
                </a>
                <?php endforeach; ?>
            </nav>

            <div class="border-t border-gray-200"></div>

            <a href="../../auth/login.php"
                class="px-6 py-4 flex items-center text-red-500 hover:bg-red-50 transition-colors duration-300 group">
                <i
                    class="fas fa-sign-out-alt w-6 mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span class="font-medium">Déconnexion</span>
            </a>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-secondary">Gestion des Cours</h2>

                    <div class="flex items-center space-x-4">
                        <div class="relative">
                            <button class="text-gray-500 hover:text-primary">
                                <i class="fas fa-bell"></i>
                            </button>
                            <span class="absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                        </div>

                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center mr-2">
                                <span class="text-primary text-sm font-bold">A</span>
                            </div>
                            <span class="text-secondary">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <div class="p-6 bg-white shadow-sm">
                <div class="flex space-x-4">
                    <form method="POST" class="flex-grow flex space-x-4">
                        <div class="relative flex-grow">
                            <input type="text" name="q" value="<?= htmlspecialchars($keyword ?? '') ?>"
                                placeholder="Rechercher des cours..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:outline-none">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>
                        <button type="submit"
                            class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                            Rechercher
                        </button>
                    </form>
                    <form method="POST">
                    <select name="category" onchange="this.form.submit()" class="px-4 py-2 border rounded-lg">
                        <option value="">Toutes les catégories</option>
                        <?php foreach($categories as $category): ?>
                        <option value="<?= $category['id'] ?>"
                            <?= (isset($_POST['category']) && $_POST['category'] == $category['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($category['title']) ?> (<?= $category['course_count'] ?>)
                        </option>
                        <?php endforeach; ?>
                    </select>
                    </form>
                </div>
            </div>

            <div class="p-6 overflow-y-auto flex-grow">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php if (isset($courses) && is_array($courses) && count($courses) > 0): ?>
                    <?php foreach ($courses as $course): ?>
                    <div class="bg-white rounded-xl border shadow-md hover:shadow-lg transition group">
                        <div class="h-48 bg-gray-200 rounded-t-xl relative overflow-hidden">
                            <?php if (!empty($course['image'])): ?>
                            <img src="<?= htmlspecialchars($course['image']) ?>"
                                alt="<?= htmlspecialchars($course['title']) ?>"
                                class="w-full h-full object-cover group-hover:scale-110 transition">
                            <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fas fa-book text-gray-400 text-4xl"></i>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="p-5">
                            <h3 class="text-lg font-bold mb-2 text-secondary truncate">
                                <?= htmlspecialchars($course['title']) ?>
                            </h3>
                            <p class="text-gray-600 text-sm mb-3">
                                Par: <span class="text-primary font-semibold">
                                    <?= htmlspecialchars($course['teacher_name']) ?>
                                </span>
                            </p>
                            <div class="flex items-center justify-between">
                                <span class="inline-block bg-primary/10 text-primary text-xs px-3 py-1 rounded-full">
                                    <?= htmlspecialchars($course['category_name']) ?>
                                </span>

                                <a href="?action=deleteCourse&id=<?= $course['id'] ?>"
                                    class="text-red-500 hover:text-red-700 transition"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?');">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-span-3 text-center py-8">
                        <i class="fas fa-book-open text-gray-400 text-5xl mb-4"></i>
                        <p class="text-gray-600">Aucun cours n'est disponible pour le moment.</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Pagination -->
            <div class="flex justify-center p-4 bg-white border-t">
                <?php if($result['currentPage'] > 1): ?>
                <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $result['currentPage'] - 1 ?>"
                    class="px-4 py-2 mx-1 bg-gray-200 rounded-lg hover:bg-primary hover:text-white transition">
                    Précédent
                </a>
                <?php endif; ?>

                <?php for($i = 1; $i <= $result['totalPages']; $i++): ?>
                <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $i ?>"
                    class="px-4 py-2 mx-1 rounded-lg <?= $i == $result['currentPage'] ? 'bg-primary text-white' : 'bg-gray-200' ?> hover:bg-primary hover:text-white transition">
                    <?= $i ?>
                </a>
                <?php endfor; ?>

                <?php if($result['currentPage'] < $result['totalPages']): ?>
                <a href="?keyword=<?= urlencode($keyword ?? '') ?>&page=<?= $result['currentPage'] + 1 ?>"
                    class="px-4 py-2 mx-1 bg-gray-200 rounded-lg hover:bg-primary hover:text-white transition">
                    Suivant
                </a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>