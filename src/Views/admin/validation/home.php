<?php
session_start();
require_once "../../../../vendor/autoload.php";

use App\Controllers\Admin\UserController;

try {
    $userController = new UserController();
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $userController->handleValidation();
    }
    
    $pendingTeachers = $userController->getPendingTeachers();
} catch (Exception $e) {
    error_log("Error loading pending teachers: " . $e->getMessage());
    $pendingTeachers = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Validation des Enseignants</title>
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
        
        .teacher-card:hover {
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
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
                    <h2 class="text-xl font-semibold text-gray-800">Validation des Enseignants</h2>
                    
                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-primary relative">
                            <i class="fas fa-bell"></i>
                            <span class="absolute -top-2 -right-2 h-4 w-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">3</span>
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
                <?php if (isset($_SESSION['success']) || isset($_SESSION['error'])): ?>
                    <div class="mb-4">
                        <?php if (isset($_SESSION['success'])): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
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

                <section class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 border-b">
                        <h3 class="text-xl font-semibold text-gray-800">Enseignants en attente de validation</h3>
                    </div>
                    
                    <div class="p-6">
                        <?php if (empty($pendingTeachers)): ?>
                            <div class="text-center py-12">
                                <i class="fas fa-user-graduate text-gray-400 text-6xl mb-4"></i>
                                <p class="text-gray-600">Aucun enseignant en attente de validation</p>
                            </div>
                        <?php else: ?>
                            <div class="space-y-6">
                                <?php foreach ($pendingTeachers as $teacher): ?>
                                    <div class=" bg-gray-50 rounded-xl p-5 border hover:shadow-md transition">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-16 h-16 rounded-full bg-primary/20 flex items-center justify-center">
                                                    <span class="text-primary font-bold text-lg">
                                                        <?= strtoupper(substr($teacher['name'], 0, 1)); ?>
                                                    </span>
                                                </div>
                                                <div class="ml-6">
                                                    <h4 class="text-lg font-semibold text-gray-800">
                                                        <?= htmlspecialchars($teacher['name']); ?>
                                                    </h4>
                                                    <p class="text-sm text-gray-600">
                                                        <?= htmlspecialchars($teacher['specialty']); ?>
                                                    </p>
                                                    <p class="text-sm text-gray-500">
                                                        <?= htmlspecialchars($teacher['email']); ?>
                                                    </p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-4">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?= $teacher['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" 
                                                            class="flex items-center px-4 py-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition">
                                                        <i class="fas fa-check mr-2"></i> Approuver
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?= $teacher['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" 
                                                            class="flex items-center px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition">
                                                        <i class="fas fa-times mr-2"></i> Refuser
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </section>
            </main>
        </div>
    </div>
</body>
</html>