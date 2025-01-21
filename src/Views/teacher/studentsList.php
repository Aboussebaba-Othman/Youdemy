<?php
session_start();
require_once "../../../vendor/autoload.php";
use App\Controllers\Teacher\StudentController;

try {
    
    $studentsController = new StudentController();
    $data = $studentsController->getStudentsList();

    $students = $data['students'] ?? [];
    $courses = $data['courses'] ?? [];
    $teacherName = $data['teacherName'] ?? 'Inconnu';
    $teacherInitial = $data['teacherInitial'] ?? 'T';

} catch (\Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "Error: " . $e->getMessage();
    $errorMessage = "Une erreur est survenue lors du chargement des données.";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="flex h-screen overflow-hidden">
        <div id="sidebar" class="w-64 bg-gradient-to-b from-blue-900 to-blue-800 text-white shadow-2xl">
            <div class="p-6 border-b border-blue-700 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-2xl font-bold text-white">Youdemy</h1>
            </div>

            <nav class="mt-6">
                <a href="home.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-book mr-4 text-blue-300"></i> Mes Cours
                </a>
                <a href="statistiques.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-chart-line mr-4 text-blue-300"></i> Statistiques
                </a>
                <a href="studentsList.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center">
                    <i class="fas fa-users text-blue-300 mr-4"></i> Liste des Étudiants
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

            <main class="flex-1 overflow-y-auto p-8">
                <div class="bg-white shadow-md rounded-lg">
                    <div class="bg-gray-50 p-6 border-b border-gray-200 flex justify-between items-center">
                        <h2 class="text-2xl font-bold text-gray-800">Liste des Étudiants</h2>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse border border-gray-200">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom Complet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cours Inscrit</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'Inscription</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (!empty($students)): ?>
                                    <?php foreach ($students as $student): ?>
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 flex items-center">
                                
                                                <?= htmlspecialchars($student['name']) ?>
                                            </td>
                                            <td class="px-6 py-4"><?= htmlspecialchars($student['email']) ?></td>
                                            <td class="px-6 py-4">
                                                <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">
                                                    <?= $courses[$student['course_id']] ?? 'Inconnu' ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4"><?= date('d/m/Y', strtotime($student['enrollment_date'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="4" class="px-6 py-4 text-center">Aucun étudiant trouvé</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>
</html>
