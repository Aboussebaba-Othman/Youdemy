<?php
session_start();

require_once "../../../vendor/autoload.php";
use App\Controllers\Teacher\StatisticController;
use App\Models\Teacher\CourseModel;

try {
    $statistiquesController = new StatisticController();
    $data = $statistiquesController->getStatistics();
    $courseModel = new CourseModel();
    if (isset($_SESSION['user_id'])) {
        $teacher = $courseModel->getTeacherByUserId($_SESSION['user_id']);
        
        if ($teacher !== null) {
            $teacherName = $teacher->getUser()->getName();
            
            $teacherInitial = strtoupper(substr($teacherName, 0, 1));
        }
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fade-in {
        animation: fadeIn 0.5s ease-out;
    }

    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }

    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .custom-scrollbar::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar" class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white 
                                 transform transition-transform duration-300 ease-in-out 
                                 md:translate-x-0 -translate-x-full fixed md:relative z-50 h-full 
                                 shadow-2xl custom-scrollbar">
            <div class="p-6 border-b border-blue-700 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-white">Youdemy</h1>
            </div>

            <nav class="mt-6">
                <a href="home.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-book mr-4 text-blue-300 group-hover:text-white"></i>
                    Mes Cours
                </a>
                <a href="statistiques.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-chart-line mr-4 text-blue-300 group-hover:text-white"></i>
                    Statistiques
                </a>
                <a href="#" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-cog mr-4 text-blue-300 group-hover:text-white"></i>
                    Paramètres
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <button id="mobile-menu-toggle" class="md:hidden mr-4 focus:outline-none">
                        <i class="fas fa-bars text-2xl text-gray-600"></i>
                    </button>
                    <h2 class="text-2xl font-semibold text-gray-800">Tableau de Bord</h2>
                </div>

                <div class="relative">
                    <div id="userProfileToggle" class="flex items-center cursor-pointer
                                       hover:bg-gray-100 p-2 rounded-full transition">
                        <span class="mr-3 text-gray-700 font-medium"><?= htmlspecialchars($teacherName) ?></span>
                        <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                            <span
                                class="text-blue-600 text-sm font-bold"><?= htmlspecialchars($teacherInitial) ?></span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto  animate-fade-in custom-scrollbar">
                <div class="container mx-auto px-4 py-8">
                    <?php if (isset($data['erreur'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <strong class="font-bold">Erreur !</strong>
                        <span class="block sm:inline"><?= htmlspecialchars($data['erreur']) ?></span>
                    </div>
                    <?php endif; ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div
                            class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 ">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-gray-500 text-sm uppercase mb-2">Total Cours</h3>
                                    <p class="text-3xl font-bold text-blue-600"><?= $data['totalCourses'] ?? 0 ?></p>
                                </div>
                                <div class="bg-blue-50 rounded-full p-3">
                                    <i class="fas fa-book text-blue-500 text-2xl"></i>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 ">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-gray-500 text-sm uppercase mb-2">Total Étudiants</h3>
                                    <p class="text-3xl font-bold text-green-600"><?= $data['totalStudents'] ?? 0 ?></p>
                                </div>
                                <div class="bg-green-50 rounded-full p-3">
                                    <i class="fas fa-users text-green-500 text-2xl"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-graduation-cap text-blue-500 mr-3"></i>
                                    Top 3 Cours
                                </h2>
                            </div>
                            <div class="p-6">
                                <?php foreach ($data['top3Courses'] as $index => $cours): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg mb-4 
                            transition duration-300 hover:bg-blue-50">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">
                                            <?= $index + 1 ?>
                                        </span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">
                                                <?= htmlspecialchars($cours['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <i class="fas fa-users mr-2 text-blue-500"></i>
                                                <?= $cours['enrollments'] ?> Étudiants
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm">
                                        Populaire
                                    </div>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
                            <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                                <h2 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-user-graduate text-green-500 mr-3"></i>
                                    Top 3 Étudiants
                                </h2>
                            </div>
                            <div class="p-6">
                                <?php foreach ($data['top3Students'] as $index => $etudiant): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg mb-4
                            transition duration-300 hover:bg-green-50">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">
                                            <?= $index + 1 ?>
                                        </span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">
                                                <?= htmlspecialchars($etudiant['name']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <i class="fas fa-book mr-2 text-green-500"></i>
                                                <?= $etudiant['course_count'] ?> Cours
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bg-green-100 text-green-600 px-3 py-1 rounded-full text-sm">
                                        Top Étudiant
                                    </div>
                                </div>
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