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
                    <h2 class="text-xl font-semibold">Gestion des validation</h2>
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
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                        <?php 
                        echo htmlspecialchars($_SESSION['success']);
                        unset($_SESSION['success']);
                        ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <?php 
                        echo htmlspecialchars($_SESSION['error']);
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php endif; ?>

                <section id="pending-teachers" class="bg-white rounded-lg shadow">
                    <div class="p-4 border-b">
                        <h3 class="text-lg font-semibold">Enseignants en attente de validation</h3>
                    </div>
                    <div class="p-4">
                        <div class="space-y-4">
                            <?php if (empty($pendingTeachers)): ?>
                                <p class="text-gray-500 text-center py-4">Aucun enseignant en attente de validation</p>
                            <?php else: ?>
                                <?php foreach ($pendingTeachers as $teacher): ?>
                                    <div class="border rounded-lg p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center">
                                                <div class="w-12 h-12 rounded-full bg-gray-200 flex items-center justify-center">
                                                    <span class="text-gray-600">#<?php echo htmlspecialchars($teacher['id']); ?></span>
                                                </div>
                                                <div class="ml-4">
                                                    <h4 class="font-semibold"><?php echo htmlspecialchars($teacher['name']); ?></h4>
                                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($teacher['specialty']); ?></p>
                                                    <p class="text-sm text-gray-500"><?php echo htmlspecialchars($teacher['email']); ?></p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $teacher['id']; ?>">
                                                    <input type="hidden" name="action" value="approve">
                                                    <button type="submit" 
                                                            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                                                        Approuver
                                                    </button>
                                                </form>
                                                <form method="POST" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $teacher['id']; ?>">
                                                    <input type="hidden" name="action" value="reject">
                                                    <button type="submit" 
                                                            class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700">
                                                        Refuser
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</body>
</html>