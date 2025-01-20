<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Controllers\Student\DetailCourseController;

try {
    $courseId = $_GET['id'] ?? null;
    if (!$courseId) {
        throw new Exception("Course ID not provided");
    }

    $controller = new DetailCourseController();
    $data = $controller->getCourseDetails($courseId);

    $course = $data['course'];
    $teacher = $data['teacher'];
    $skills = $data['skills'];
    $isLoggedIn = $data['isLoggedIn'];
    $isEnrolled = $data['isEnrolled'];

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    header("Location: /error.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> - Détails du Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 class="text-3xl font-bold mb-2">
                            <?= htmlspecialchars($course['title']) ?>
                        </h1>
                        <span class="bg-white/20 px-3 py-1 rounded-full">
                            <?= htmlspecialchars($course['category_name']) ?>
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 p-6">
                <div>
                    <img src="<?= htmlspecialchars($course['image']) ?>"
                         alt="<?= htmlspecialchars($course['title']) ?>"
                         class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>

                <div>
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Description</h2>
                    <p class="text-gray-600 mb-6">
                        <?= nl2br(htmlspecialchars($course['description'])) ?>
                    </p>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800">Instructeur</h3>
                        <div class="flex items-center">
                            <div class="w-16 h-16 rounded-full bg-blue-500 flex items-center justify-center text-white text-2xl mr-4">
                                <?= strtoupper(substr($teacher['name'], 0, 1)) ?>
                            </div>
                            <div>
                                <h4 class="text-lg font-semibold"><?= htmlspecialchars($teacher['name']) ?></h4>
                                <p class="text-gray-600 text-sm"><?= htmlspecialchars($teacher['email']) ?></p>
                                <p class="text-gray-600 text-sm"><?= htmlspecialchars($teacher['specialty']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-800">Compétences Acquises</h3>
                        <div class="flex flex-wrap gap-2">
                            <?php foreach ($skills as $skill): ?>
                                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                    <?= htmlspecialchars($skill) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t">
                <?php if ($isLoggedIn): ?>
                    <?php if ($isEnrolled): ?>
                        <button disabled class="w-full bg-green-500 text-white py-3 rounded-lg cursor-not-allowed">
                            <i class="fas fa-check mr-3"></i>
                            Déjà inscrit
                        </button>
                    <?php else: ?>
                        <button onclick="enrollCourse(<?= $course['id'] ?>)" 
                                class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300">
                            <i class="fas fa-graduation-cap mr-3"></i>
                            S'inscrire au cours
                        </button>
                    <?php endif; ?>
                <?php else: ?>
                    <a href="/auth/login.php" 
                       class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition duration-300 flex items-center justify-center">
                        <i class="fas fa-sign-in-alt mr-3"></i>
                        Connectez-vous pour vous inscrire
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    
</body>
</html>