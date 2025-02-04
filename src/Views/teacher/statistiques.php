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
    $data = ['erreur' => 'Impossible de charger les statistiques'];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: '#6366f1',
                        light: '#a5b4fc',
                        dark: '#4338ca'
                    }
                }
            }
        }
    }
    </script>
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        ::-webkit-scrollbar {
            width: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 4px;
        }
    </style>
</head>
<body class="bg-gray-50 antialiased">
    <div class="flex min-h-screen">
        <div class="w-72 bg-gradient-to-b from-primary to-primary-dark text-white shadow-2xl">
            <div class="p-6 border-b border-white/20 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-3xl font-bold tracking-wider">
                    <span class="text-white">Youde</span><span class="text-yellow-300">my</span>
                </h1>
            </div>

            <nav class="mt-10 space-y-2 px-4">
                <a href="home.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-book w-6 text-blue-300"></i>
                    <span>Mes Cours</span>
                </a>
                <a href="statistiques.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg bg-white/10 text-yellow-300">
                    <i class="fas fa-chart-line w-6"></i>
                    <span>Statistiques</span>
                </a>
                <a href="studentsList.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-users w-6 text-blue-300"></i>
                    <span>Liste des Étudiants</span>
                </a>
                <div class="my-4 border-t border-white/10"></div>


                <a href="../auth/login.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg 
              bg-red-500/20 hover:bg-red-500/30 
              text-red-300 hover:text-red-200
              transition-all duration-300 
              group">
                    <i class="fas fa-sign-out-alt w-6 transform group-hover:-translate-x-1 transition-transform"></i>
                    <span class="font-medium">Déconnexion</span>
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col bg-gray-100">
        <header class="bg-white shadow-md border-b border-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-40">
    <h2 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">
        Statistiques
    </h2>
    
    <div class="flex items-center space-x-3">
        <div class="avatar online">
            <div class="w-12 h-12 rounded-full ring-2 ring-primary/30 ring-offset-2">
                <img 
                    src="https://ui-avatars.com/api/?name=<?= urlencode($teacherName) ?>" 
                    alt="<?= htmlspecialchars($teacherName) ?>"
                    class="rounded-full object-cover"
                />
            </div>
        </div>
        <div>
            <span class="text-gray-800 font-semibold text-base block">
                <?= htmlspecialchars($teacherName) ?>
            </span>
            <span class="text-gray-500 text-xs">Enseignant</span>
        </div>
    </div>
</header>

            <main class="p-6 animate-fade-in">
                <?php if (isset($data['erreur'])): ?>
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
                        <strong class="font-bold">Erreur !</strong>
                        <span class="block sm:inline"><?= htmlspecialchars($data['erreur']) ?></span>
                    </div>
                <?php endif; ?>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-primary">
                        <div class="flex justify-between items-center">
                            <div>
                                <h3 class="text-gray-500 text-sm uppercase mb-2">Total Cours</h3>
                                <p class="text-3xl font-bold text-primary"><?= $data['totalCourses'] ?? 0 ?></p>
                            </div>
                            <div class="bg-primary/10 rounded-full p-3">
                                <i class="fas fa-book text-primary text-2xl"></i>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500">
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
                                <i class="fas fa-graduation-cap text-primary mr-3"></i>
                                Top 3 Cours
                            </h2>
                        </div>
                        <div class="p-6">
                            <?php foreach ($data['top3Courses'] as $index => $cours): ?>
                                <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg mb-4 
                                    transition duration-300 hover:bg-primary/10">
                                    <div class="flex items-center">
                                        <span class="text-2xl font-bold text-gray-300 mr-4">
                                            <?= $index + 1 ?>
                                        </span>
                                        <div>
                                            <h3 class="font-semibold text-gray-800">
                                                <?= htmlspecialchars($cours['title']) ?>
                                            </h3>
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <i class="fas fa-users mr-2 text-primary"></i>
                                                <?= $cours['enrollments'] ?> Étudiants
                                            </p>
                                        </div>
                                    </div>
                                    <div class="bg-primary/10 text-primary px-3 py-1 rounded-full text-sm">
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
            </main>
        </div>
    </div>
</body>
</html>