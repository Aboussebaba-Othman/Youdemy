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
                echo json_encode(['status' => 'success', 'message' => 'Category deleted successfully']);
                exit;
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to delete category']);
                exit;
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
    <title>Youdemy - Gestion des Catégories</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
        background: rgb(51, 44, 192);
        border-radius: 4px;
    }

    .category-card:hover {
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
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
            <header class="bg-white shadow-sm border-b sticky top-0 z-20">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Catégories</h2>

                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-primary relative">
                            <i class="fas fa-bell"></i>
                            <span
                                class="absolute -top-2 -right-2 h-4 w-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">3</span>
                        </button>

                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center mr-2">
                                <span class="text-primary text-sm font-bold">A</span>
                            </div>
                            <span class="text-gray-700">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6 flex justify-between items-center">
                    <h3 class="text-xl font-semibold text-gray-800">Liste des Catégories</h3>
                    <button onclick="openModal('addCategoryModal')"
                        class="flex items-center px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
                        <i class="fas fa-plus mr-2"></i> Nouvelle Catégorie
                    </button>
                </div>

                <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
                <div class="mb-4">
                    <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative"
                        role="alert">
                        <?= htmlspecialchars($_SESSION['success']) ?>
                        <?php unset($_SESSION['success']); ?>
                    </div>
                    <?php endif; ?>

                    <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <?= htmlspecialchars($_SESSION['error']) ?>
                        <?php unset($_SESSION['error']); ?>
                    </div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 ">
                    <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                    <div class="bg-white  rounded-xl shadow-md p-6 border">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-800">
                                <?= htmlspecialchars($category['title']) ?>
                            </h3>
                            <div class="flex space-x-2">
                                <button
                                    onclick="openModalEdit('editCategoryModal', <?= $category['id'] ?>, '<?= htmlspecialchars($category['title']) ?>')"
                                    class="text-primary hover:text-primary-dark transition">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="" method="POST" class="inline delete-form">
                                    <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                    <input type="hidden" name="delete" value="1">
                                    <button type="submit" class="text-red-500 hover:text-red-700 transition">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-sm text-gray-600">
                                <?= $category['course_count'] ?? 0 ?> cours
                            </span>
                            <span class="text-xs text-primary bg-primary/10 px-2 py-1 rounded-full">
                                Active
                            </span>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-span-full text-center py-12">
                        <i class="fas fa-folder-open text-gray-400 text-6xl mb-4"></i>
                        <p class="text-gray-600">Aucune catégorie n'a été trouvée</p>
                    </div>
                    <?php endif; ?>
                </div>
            </main>
        </div>
    </div>

    <div id="addCategoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-96 p-6 rounded-lg shadow-lg animate-fadeIn">
            <h2 class="text-xl font-bold mb-4">Add New Category</h2>
            <form action="" method="POST">
                <div class="mb-4">
                    <label for="categoryName" class="block text-gray-700 font-medium mb-2">Category Name</label>
                    <input type="text" id="categoryName" name="nom_category"
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                        placeholder="Enter category name" required>
                </div>
                <div class="flex justify-end space-x-4">
                    <button type="button"
                        class="px-4 py-2 bg-gray-300 rounded-lg hover:bg-gray-400 transition duration-300"
                        onclick="closeModal('addCategoryModal')">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-300">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div id="editCategoryModal"
        class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white w-96 p-6 rounded-lg shadow-lg">
            <h2 class="text-xl font-bold mb-4">Edit Category</h2>
            <form action="" method="POST">
                <input type="hidden" name="id" id="editCategoryId">
                <div class="mb-4">
                    <label for="editCategoryName" class="block text-gray-700 font-medium mb-2">Category Name</label>
                    <input type="text" id="editCategoryName" name="nom_category"
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
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData(this);

                    fetch('', {
                            method: 'POST',
                            body: formData
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'success') {
                                Swal.fire(
                                    'Deleted!',
                                    data.message,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire(
                                    'Error!',
                                    data.message,
                                    'error'
                                );
                            }
                        });
                }
            });
        });
    });

    <?php if (isset($_SESSION['success'])): ?>
    Swal.fire({
        icon: 'success',
        title: '<?= $_SESSION['success'] ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '<?= $_SESSION['error'] ?>',
        timer: 3000,
        showConfirmButton: false
    });
    <?php unset($_SESSION['error']); ?>
    <?php endif; ?>
    </script>

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