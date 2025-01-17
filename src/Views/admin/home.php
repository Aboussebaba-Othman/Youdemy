<?php
require_once "../../../vendor/autoload.php";
use App\Controllers\Admin\CourseController;

try {

    $adminController = new CourseController();

    $data = $adminController->getDashboardData();

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Tableau de Bord Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/chart.js@4.0.1/dist/chart.umd.min.js" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-md">
            <div class="p-5 border-b">
                <h1 class="text-2xl font-bold text-blue-600">Youdemy Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
                <a href="user/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="cour/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="tag/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="categorie/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="validation/home.php" class="flex items-center px-4 py-3 hover:bg-gray-700">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-y-auto p-8">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Tableau de Bord</h2>
                <div class="flex items-center">
                    <input type="text" placeholder="Rechercher..." class="px-4 py-2 border rounded-lg mr-4">
                    <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center mr-2">
                                <span class="text-gray-600 text-sm">A</span>
                            </div>
                            <span class="text-gray-700">Admin</span>
                        </div>
                </div>
            </header>

            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Total Cours</h3>
                            <p class="text-2xl font-bold"><?= $data['total_courses'] ?></p>
                        </div>
                        <i class="fas fa-book text-blue-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Utilisateurs</h3>
                            <p class="text-2xl font-bold"><?= $data['total_users'] ?></p>
                        </div>
                        <i class="fas fa-users text-green-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Enseignants</h3>
                            <p class="text-2xl font-bold"><?= $data['total_teachers'] ?></p>
                        </div>
                        <i class="fas fa-chalkboard-teacher text-purple-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Étudiants</h3>
                            <p class="text-2xl font-bold"><?= $data['total_students'] ?></p>
                        </div>
                        <i class="fas fa-euro-sign text-yellow-500 text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Top 3 Enseignants</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-2 text-left">Nom</th>
                                <th class="p-2 text-center">Cours</th>
                                <th class="p-2 text-center">Étudiants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['top_teachers'] as $teacher): ?>
                                <tr>
                                    <td class="p-2"><?= htmlspecialchars($teacher['teacher_name']) ?></td>
                                    <td class="p-2 text-center"><?= $teacher['total_courses'] ?></td>
                                    <td class="p-2 text-center"><?= $teacher['total_students'] ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
