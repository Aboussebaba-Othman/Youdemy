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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .hover-lift:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
    }
    </style>
</head>

<body class="bg-gray-50 font-sans">
    <div class="flex h-screen">
        <div class="w-64 bg-white shadow-md">
            <div class="p-5 border-b">
                <h1 class="text-2xl font-bold text-blue-600">Youdemy Admin</h1>
            </div>
            <nav class="mt-4">
                <a href="" class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-tachometer-alt mr-3"></i>
                    <span>Tableau de Bord</span>
                </a>
                <a href="user/home.php"
                    class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-users w-6"></i>
                    <span>Gestion Utilisateurs</span>
                </a>
                <a href="cour/home.php"
                    class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-book w-6"></i>
                    <span>Gestion Cours</span>
                </a>
                <a href="tag/home.php"
                    class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-tags w-6"></i>
                    <span>Gestion Tags</span>
                </a>
                <a href="categorie/home.php"
                    class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-folder w-6"></i>
                    <span>Gestion Catégories</span>
                </a>
                <a href="validation/home.php"
                    class="flex items-center px-4 py-3 hover:bg-blue-50 text-gray-700 hover:text-blue-600">
                    <i class="fas fa-user-check w-6"></i>
                    <span>Validation Enseignants</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 overflow-y-auto p-8 bg-gray-50">
            <header class="flex justify-between items-center mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Tableau de Bord</h2>
                <div class="flex items-center">
                    <input type="text" placeholder="Rechercher..."
                        class="px-4 py-2 border rounded-lg mr-4 focus:ring-2 focus:ring-blue-500 focus:outline-none">
                    <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span class="text-blue-600 text-sm font-bold">A</span>
                        </div>
                        <span class="text-gray-700 font-medium">Admin</span>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-4 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-md hover-lift border-l-4 border-blue-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 mb-2">Total Cours</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $data['total_courses'] ?></p>
                        </div>
                        <i class="fas fa-book text-blue-500 text-4xl opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover-lift border-l-4 border-green-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 mb-2">Utilisateurs</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $data['total_users'] ?></p>
                        </div>
                        <i class="fas fa-users text-green-500 text-4xl opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover-lift border-l-4 border-purple-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 mb-2">Enseignants</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $data['total_teachers'] ?></p>
                        </div>
                        <i class="fas fa-chalkboard-teacher text-purple-500 text-4xl opacity-50"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover-lift border-l-4 border-yellow-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 mb-2">Étudiants</h3>
                            <p class="text-3xl font-bold text-gray-800"><?= $data['total_students'] ?></p>
                        </div>
                        <i class="fas fa-graduation-cap text-yellow-500 text-4xl opacity-50"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md col-span-2">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Top 3 Enseignants</h3>
                    <table class="w-full">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="p-3 text-left text-gray-600">Nom</th>
                                <th class="p-3 text-center text-gray-600">Cours</th>
                                <th class="p-3 text-center text-gray-600">Étudiants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($data['top_teachers'] as $teacher): ?>
                            <tr class="border-b hover:bg-gray-50 transition">
                                <td class="p-3"><?= htmlspecialchars($teacher['teacher_name']) ?></td>
                                <td class="p-3 text-center"><?= $teacher['total_courses'] ?></td>
                                <td class="p-3 text-center"><?= $teacher['total_students'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Top Catégories</h3>
                    <div class="space-y-3">
                        <?php foreach ($data['top_category'] as $category): ?>
                        <div class="flex justify-between items-center bg-gray-100 rounded-lg p-3">
                            <div class="flex items-center">
                                <span class="w-2 h-2 rounded-full mr-3 
                                        <?php 
                                        $colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500', 'bg-yellow-500', 'bg-red-500'];
                                        echo $colors[array_rand($colors)];
                                        ?>">
                                </span>
                                <span
                                    class="font-medium text-gray-700"><?= htmlspecialchars($category['category_name']) ?></span>
                            </div>
                            <span class="text-gray-600"><?= $category['total_courses'] ?> cours</span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

</html>