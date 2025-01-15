<?php
session_start();
require_once "../../../../vendor/autoload.php";
require_once __DIR__ . '/../../../Models/Admin/TagModel.php';

use App\Models\Admin\TagModel;
use App\Controllers\Admin\TagController;

$tagModel = new TagModel();
$tagController = new TagController($tagModel);

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
                    <h2 class="text-xl font-semibold">Gestion des Tags</h2>
                </div>
            </header>

            <div class="p-6">
                <?php if (isset($_SESSION['success_message'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <?= $_SESSION['success_message'] ?>
                        <?php unset($_SESSION['success_message']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error_message'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <?= $_SESSION['error_message'] ?>
                        <?php unset($_SESSION['error_message']); ?>
                    </div>
                <?php endif; ?>

                <section class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">Créer des Tags</h3>
                    </div>
                    
                    <div class="p-4">
                        <form action="<?= htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST" class="mb-4">
                            <input type="hidden" name="action" value="createMultipleTags">
                            <div class="flex gap-2">
                                <input 
                                    type="text" 
                                    name="tags" 
                                    placeholder="Entrez plusieurs tags séparés par des virgules" 
                                    class="flex-1 border rounded-lg px-4 py-2"
                                    required
                                >
                                <button 
                                    type="submit" 
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700"
                                >
                                    Ajouter
                                </button>
                            </div>
                        </form>

                        <div class="flex flex-wrap gap-2 mt-4">
                            <?php foreach ($tags as $tag): ?>
                                <span class="px-3 py-1 bg-gray-100 rounded-full text-sm flex items-center">
                                    <?= htmlspecialchars($tag['title']); ?>
                                    <a 
                                    href="<?= htmlspecialchars($_SERVER['PHP_SELF'] . "?action=deleteTag&id=" . $tag['id']); ?>"                                        onclick="return confirm('Voulez-vous vraiment supprimer ce tag ?');"
                                        class="ml-2 text-red-500 hover:text-red-700"
                                    >
                                        <i class="fas fa-times"></i>
                                    </a>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>
