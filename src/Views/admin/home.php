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
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#a435f0',
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
            ['icon' => 'tachometer-alt', 'label' => 'Tableau de Bord', 'link' => '../admin/home.php', 'path' => 'home'],
            ['icon' => 'users', 'label' => 'Gestion Utilisateurs', 'link' => '../admin/user/home.php', 'path' => 'user'],
            ['icon' => 'book', 'label' => 'Gestion Cours', 'link' => '../admin/cour/home.php', 'path' => 'cour'],
            ['icon' => 'tags', 'label' => 'Gestion Tags', 'link' => '../admin/tag/home.php', 'path' => 'tag'],
            ['icon' => 'folder', 'label' => 'Gestion Catégories', 'link' => '../admin/categorie/home.php', 'path' => 'categorie'],
            ['icon' => 'user-check', 'label' => 'Validation Enseignants', 'link' => '../admin/validation/home.php', 'path' => 'validation']
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

            <a href="../auth/login.php"
                class="px-6 py-4 flex items-center text-red-500 hover:bg-red-50 transition-colors duration-300 group">
                <i
                    class="fas fa-sign-out-alt w-6 mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span class="font-medium">Déconnexion</span>
            </a>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-secondary">Tableau de Bord</h2>
                    
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

            <div class="p-6 grid grid-cols-4 gap-6">
                <?php 
                $stats = [
                    ['label' => 'Total Cours', 'value' => $data['total_courses'], 'icon' => 'book', 'color' => 'blue'],
                    ['label' => 'Utilisateurs', 'value' => $data['total_users'], 'icon' => 'users', 'color' => 'green'],
                    ['label' => 'Enseignants', 'value' => $data['total_teachers'], 'icon' => 'chalkboard-teacher', 'color' => 'purple'],
                    ['label' => 'Étudiants', 'value' => $data['total_students'], 'icon' => 'graduation-cap', 'color' => 'yellow']
                ];

                foreach ($stats as $stat):
                ?>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition border-l-4 border-<?= $stat['color'] ?>-500">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500 mb-2"><?= $stat['label'] ?></h3>
                            <p class="text-3xl font-bold text-secondary"><?= $stat['value'] ?></p>
                        </div>
                        <i class="fas fa-<?= $stat['icon'] ?> text-<?= $stat['color'] ?>-500 text-4xl opacity-50"></i>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>

            <div class="p-6 grid grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-md col-span-2">
                    <h3 class="text-xl font-semibold mb-4 text-secondary">Top 3 Enseignants</h3>
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
                            <tr class="border-b hover:bg-primary/5 transition">
                                <td class="p-3"><?= htmlspecialchars($teacher['teacher_name']) ?></td>
                                <td class="p-3 text-center"><?= $teacher['total_courses'] ?></td>
                                <td class="p-3 text-center"><?= $teacher['total_students'] ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md">
                    <h3 class="text-xl font-semibold mb-4 text-secondary">Top Catégories</h3>
                    <div class="space-y-3">
                        <?php foreach ($data['top_category'] as $category): ?>
                        <div class="flex justify-between items-center bg-gray-100 rounded-lg p-3 hover:bg-primary/10 transition">
                            <div class="flex items-center">
                                <span class="w-2 h-2 rounded-full mr-3 bg-primary"></span>
                                <span class="font-medium text-secondary">
                                    <?= htmlspecialchars($category['category_name']) ?>
                                </span>
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