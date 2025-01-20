<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Controllers\Teacher\CourseController;

try {
    $courseController = new CourseController();
    
    $courseId = $_GET['id'] ?? null;
    if (!$courseId) {
        throw new \Exception("ID du cours non spécifié");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($courseController->updateCourse($courseId, $_POST)) {
            $_SESSION['success'] = "Cours mis à jour avec succès";
            header('Location: home.php');
            exit;
        }
    }

    $course = $courseController->getCourse($courseId);
    $data = $courseController->getHomeData();

} catch (\Exception $e) {
    $_SESSION['error'] = $e->getMessage();
    header('Location: home.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le Cours - Youdemy</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div class="w-64 bg-gradient-to-b from-gray-800 to-gray-900 text-white">
            <div class="p-6 border-b border-gray-700 flex items-center">
                <img src="https://via.placeholder.com/50" class="w-10 h-10 rounded-full mr-3" alt="Logo">
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
                <a href="studentsList.php" class="block py-3 px-6 hover:bg-blue-700 transition flex items-center group">
                    <i class="fas fa-users text-blue-300 mr-4 group-hover:text-white"></i>
                    Liste des Étudiants
                </a>
            </nav>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-md p-4 flex justify-between items-center">
                <div class="flex items-center">
                    <a href="home.php" class="mr-4 text-gray-600 hover:text-gray-900">
                        <i class="fas fa-arrow-left text-xl"></i>
                    </a>
                    <h2 class="text-2xl font-semibold text-gray-800">Modifier le Cours</h2>
                </div>

                <div class="relative">
                    <div class="flex items-center">
                        <span class="mr-3 text-gray-700">Jean Dupont</span>
                        <img src="https://via.placeholder.com/40" class="rounded-full w-10 h-10" alt="Profile">
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-xl shadow-lg p-6 max-w-4xl mx-auto">
                    <form action="" method="post" class="space-y-6">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Titre du Cours *</label>
                                <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>"
                                    required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-medium mb-2">Catégorie *</label>
                                <select name="category_id" required
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500">
                                    <?php foreach ($data['categories'] as $category): ?>
                                    <option value="<?= $category['id'] ?>"
                                        <?= $category['id'] == $course['category_id'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($category['title']) ?>
                                    </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-medium mb-2">Description *</label>
                            <textarea name="description" required rows="4"
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"><?= htmlspecialchars($course['description']) ?></textarea>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-gray-700 font-medium mb-2">Type de Contenu *</label>
                            <div class="flex space-x-6">
                                <label class="flex items-center">
                                    <input type="radio" name="contentType" value="video"
                                        <?= strpos($course['content'], 'http') !== false ? 'checked' : '' ?>
                                        class="form-radio text-blue-600">
                                    <span class="ml-2">Vidéo</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="contentType" value="document"
                                        <?= strpos($course['content'], 'http') === false ? 'checked' : '' ?>
                                        class="form-radio text-blue-600">
                                    <span class="ml-2">Document</span>
                                </label>
                            </div>

                            <div id="videoContent"
                                class="<?= strpos($course['content'], 'http') !== false ? '' : 'hidden' ?>">
                                <input type="url" name="videoUrl"
                                    value="<?= strpos($course['content'], 'http') !== false ? htmlspecialchars($course['content']) : '' ?>"
                                    <?= strpos($course['content'], 'http') !== false ? 'required' : '' ?>
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="URL de la vidéo (YouTube, Vimeo)">
                                <p class="text-sm text-gray-500 mt-1">Collez l'URL de votre vidéo YouTube ou Vimeo</p>
                            </div>

                            <div id="documentContent"
                                class="<?= strpos($course['content'], 'http') === false ? '' : 'hidden' ?>">
                                <textarea name="documentContent" rows="10"
                                    <?= strpos($course['content'], 'http') === false ? 'required' : '' ?>
                                    class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                    placeholder="Contenu du document..."><?= strpos($course['content'], 'http') === false ? htmlspecialchars($course['content']) : '' ?></textarea>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-gray-700 font-medium mb-2">Image de Couverture *</label>
                            <input type="url" name="coverImageUrl" value="<?= htmlspecialchars($course['image']) ?>"
                                required class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500"
                                placeholder="URL de l'image">
                            <?php if ($course['image']): ?>
                            <div class="mt-2">
                                <img src="<?= htmlspecialchars($course['image']) ?>" alt="Preview"
                                    class="w-full h-48 object-cover rounded-lg">
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-gray-700 font-medium mb-2">Tags</label>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($data['tags'] as $tag): ?>
                                <label class="inline-flex items-center bg-gray-50 px-3 py-2 rounded-lg">
                                    <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                                        <?= in_array($tag['id'], $course['tags']) ? 'checked' : '' ?>
                                        class="form-checkbox text-blue-600">
                                    <span class="ml-2"><?= htmlspecialchars($tag['title']) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="home.php"
                                class="px-6 py-2.5 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition-colors">
                                Annuler
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Sauvegarder les modifications
                            </button>
                        </div>
                    </form>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.querySelectorAll('input[name="contentType"]').forEach(radio => {
    radio.addEventListener('change', () => {
        const videoContent = document.getElementById('videoContent');
        const documentContent = document.getElementById('documentContent');
        const videoInput = videoContent.querySelector('input[name="videoUrl"]');
        const documentInput = documentContent.querySelector('textarea[name="documentContent"]');

        videoContent.classList.toggle('hidden', radio.value !== 'video');
        documentContent.classList.toggle('hidden', radio.value !== 'document');

        if (radio.value === 'video') {
            videoInput.required = true;
            documentInput.required = false;
        } else {
            videoInput.required = false;
            documentInput.required = true;
        }
    });
});
    document.querySelectorAll('input[name="contentType"]').forEach(radio => {
        radio.addEventListener('change', () => {
            document.getElementById('videoContent').classList.toggle('hidden', radio.value !== 'video');
            document.getElementById('documentContent').classList.toggle('hidden', radio.value !==
                'document');
        });
    });
    </script>
</body>

</html>