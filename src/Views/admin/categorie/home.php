<?php
require_once "../../../../vendor/autoload.php";

use App\Controllers\Admin\CategoryController;

session_start();

try {
    $categoryController = new CategoryController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['delete']) && isset($_POST['id'])) {
            $id = intval($_POST['id']);
            if ($categoryController->deleteCategory($id)) {
                $_SESSION['success'] = "Category deleted successfully.";
            } else {
                $_SESSION['error'] = "Failed to delete category.";
            }
        }
        
        if (isset($_POST['nom_category'])) {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $categoryController->updateCategory(
                    trim($_POST['id']), 
                    trim($_POST['nom_category'])
                );
                $_SESSION['success'] = "Category updated successfully";
            } else {
                $categoryController->createCategory(trim($_POST['nom_category']));
                $_SESSION['success'] = "Category created successfully";
            }
            
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit();
        }
    }
    
    
    
    $categories = $categoryController->getCategories();
    
} catch (Exception $e) {
    $_SESSION['error'] = $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion Administrative</title>
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

            <div class="p-6 space-y-8">
            <section id="categories" class="bg-white rounded-lg shadow">
        <div class="p-4 border-b flex justify-between items-center">
            <h3 class="text-lg font-semibold">Gestion des Catégories</h3>
            <a class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700"  onclick="openModal('addCategoryModal')">
                <i class="fas fa-plus mr-2"></i>Nouvelle Catégorie
            </a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative m-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['success'] ?></span>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative m-4" role="alert">
                <span class="block sm:inline"><?= $_SESSION['error'] ?></span>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        
<div class="p-4">
<?php if (!empty($categories)): ?>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <?php foreach ($categories as $category): ?>
            <div class="border rounded-lg p-4">
                <div class="flex justify-between items-center mb-2">
                    <h4 class="font-semibold"><?= htmlspecialchars($category['title']) ?></h4>
                
                    <div class="flex space-x-2">
    <button onclick="openModalEdit('editCategoryModal', <?= $category['id'] ?>, '<?= htmlspecialchars($category['title']) ?>')" 
            class="text-blue-600 hover:text-blue-900">
        <i class="fas fa-edit"></i>
    </button>

    <form action="" method="POST" class="inline" 
          onsubmit="return confirm('Are you sure you want to delete this category?');">
        <input type="hidden" name="id" value="<?= $category['id'] ?>">
        <input type="hidden" name="delete" value="1"> 
        <button type="submit" class="text-red-600 hover:text-red-900">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>

                </div>
                <p class="text-sm text-gray-600"><?= $category['course_count'] ?? 0 ?> courses</p>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
        </div>

        
    </section>
            </div>
        </div>
    </div>
    <div id="addCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white w-96 p-6 rounded-lg shadow-lg animate-fadeIn">
                <h2 class="text-xl font-bold mb-4">Add New Category</h2>
                <form action="" method="POST">
                    <div class="mb-4">
                        <label for="categoryName" class="block text-gray-700 font-medium mb-2">Category Name</label>
                        <input type="text" id="categoryName" name="nom_category" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Enter category name" required>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button type="button" class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition duration-300" onclick="closeModal('addCategoryModal')">Cancel</button>
                        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Save</button>
                    </div>
                </form>
            </div>
        </div>

    <div id="editCategoryModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
        <h2 class="text-xl font-bold mb-4">Edit Category</h2>
        <form action="" method="POST">
            <input type="hidden" name="id" id="editCategoryId">
            <div class="mb-4">
                <label for="editCategoryName" class="block text-gray-700 font-medium mb-2">Category Name</label>
                <input type="text" 
                       id="editCategoryName" 
                       name="nom_category" 
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                       required>
            </div>
            <div class="flex justify-end space-x-4">
                <button type="button" 
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition duration-300" 
                        onclick="closeModal('editCategoryModal')">
                    Cancel
                </button>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>
    </div>

    <script>
        function openModal(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }

        function openModalEdit(modalId) {
            document.getElementById(modalId).classList.remove('hidden');
        }

        function closeModalEdit(modalId) {
            document.getElementById(modalId).classList.add('hidden');
        }
        function openModalEdit(modalId, categoryId, categoryTitle) {
    const modal = document.getElementById(modalId);
    document.getElementById('editCategoryId').value = categoryId;
    document.getElementById('editCategoryName').value = categoryTitle;
    modal.classList.remove('hidden');
}
    </script> 
</body>
</html>