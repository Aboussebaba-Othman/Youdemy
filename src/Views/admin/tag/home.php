<?php
session_start();
require_once "../../../../vendor/autoload.php";
require_once __DIR__ . '/../../../Models/Admin/TagModel.php';

use App\Models\Admin\TagModel;
use App\Controllers\Admin\TagController;

$tagModel = new TagModel();
$tagController = new TagController($tagModel);

// Handle form submissions and actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'createMultipleTags') {
        if (isset($_POST['tags']) && !empty(trim($_POST['tags']))) {
            $tagsInput = trim($_POST['tags']);
            try {
                $tagController->createMultipleTags($tagsInput);
                $_SESSION['success_message'] = "Tags créés avec succès";
            } catch (Exception $e) {
                $_SESSION['error_message'] = "Erreur lors de la création des tags : " . $e->getMessage();
            }
            header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
            exit();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['action']) && $_GET['action'] === 'deleteTag' && isset($_GET['id'])) {
        $tagId = intval($_GET['id']);
        try {
            $tagController->deleteTag($tagId);
            $_SESSION['success_message'] = "Tag supprimé avec succès";
        } catch (Exception $e) {
            $_SESSION['error_message'] = "Erreur lors de la suppression du tag : " . $e->getMessage();
        }
        header("Location: " . htmlspecialchars($_SERVER['PHP_SELF']));
        exit();
    }
}

$tags = $tagModel->getAllTags();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion des Tags</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom Scrollbar */
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
            background:rgb(51, 44, 192);
        }
    </style>
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

        <div class="flex-1 flex flex-col overflow-hidden">            <header class="bg-white shadow-sm border-b sticky top-0 z-20">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Tags</h2>
                    
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
                <?php if (isset($_SESSION['success_message']) || isset($_SESSION['error_message'])): ?>
                    <div class="mb-4">
                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                                <?= htmlspecialchars($_SESSION['success_message']) ?>
                                <?php unset($_SESSION['success_message']); ?>
                            </div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                                <?= htmlspecialchars($_SESSION['error_message']) ?>
                                <?php unset($_SESSION['error_message']); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 border-b">
                        <h3 class="text-xl font-semibold text-gray-800">Créer des Tags</h3>
                    </div>
                    
                    <div class="p-6">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="mb-6">
                            <input type="hidden" name="action" value="createMultipleTags">
                            <div class="flex space-x-4">
                                <input 
                                    type="text" 
                                    name="tags" 
                                    placeholder="Entrez plusieurs tags séparés par des virgules" 
                                    class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-primary/50 focus:outline-none"
                                    required
                                >
                                <button 
                                    type="submit" 
                                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary-dark transition"
                                >
                                    Ajouter
                                </button>
                            </div>
                        </form>

                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-semibold text-gray-700 mb-4">Tags existants</h4>
                            <div class="flex flex-wrap gap-2">
                                <?php foreach ($tags as $tag): ?>
                                    <span class="px-3 py-1 bg-primary/10 text-primary rounded-full text-sm flex items-center">
                                        <?= htmlspecialchars($tag['title']); ?>
                                        <a 
                                            href="<?= htmlspecialchars($_SERVER['PHP_SELF'] . "?action=deleteTag&id=" . $tag['id']); ?>"                                        
                                            onclick="return confirm('Voulez-vous vraiment supprimer ce tag ?');"
                                            class="ml-2 text-red-500 hover:text-red-700"
                                        >
                                            <i class="fas fa-times"></i>
                                        </a>
                                    </span>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>