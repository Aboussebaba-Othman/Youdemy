<?php
session_start();
require_once "../../../vendor/autoload.php";

use App\Controllers\Teacher\CourseController;
use App\Controllers\Teacher\TeacherController;
use App\Models\Teacher\CourseModel;

try {
    $courseController = new CourseController();
    $courseModel = new CourseModel();
    $courseId = $_GET['id'] ?? null;
    if (!$courseId) {
        throw new \Exception("ID du cours non spécifié");
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if ($courseController->updateCourse($courseId, $_POST)) {
            // $_SESSION['success'] = "Cours mis à jour avec succès";
            header('Location: home.php');
            exit;
        }
    }
    if (isset($_SESSION['user_id'])) {
        $teacher = $courseModel->getTeacherByUserId($_SESSION['user_id']);
        
        if ($teacher !== null) {
            $teacherName = $teacher->getUser()->getName();
            
            $teacherInitial = strtoupper(substr($teacherName, 0, 1));
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
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.32/dist/sweetalert2.all.min.js"></script>
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

    .student-row {
        transition: all 0.3s ease;
    }

    .student-row:hover {
        background-color: #f5f5ff;
    }
    </style>
</head>

<body class="bg-gradient-to-br from-gray-50 to-gray-100 font-sans">
    <div class="flex h-screen overflow-hidden">
        <div class="w-72 bg-gradient-to-b from-primary to-primary-dark text-white shadow-2xl">
            <div class="p-6 border-b border-white/20 flex items-center">
                <i class="fas fa-graduation-cap text-white text-2xl mr-3"></i>
                <h1 class="text-3xl font-bold tracking-wider">
                    <span class="text-white">Youde</span><span class="text-yellow-300">my</span>
                </h1>
            </div>

            <nav class="mt-10 space-y-2 px-4">
                <a href="home.php" class="flex items-center space-x-3 py-3 px-4 rounded-lg bg-white/10 text-yellow-300">
                    <i class="fas fa-book w-6 text-blue-300"></i>
                    <span>Mes Cours</span>
                </a>
                <a href="statistiques.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-chart-line w-6 text-blue-300"></i>
                    <span>Statistiques</span>
                </a>
                <a href="studentsList.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-users w-6"></i>
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

        <div class="flex-1 flex flex-col overflow-hidden">
            <header
                class="bg-white shadow-md border-b border-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-40">
                <h2
                    class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">
                    Mes Courses
                </h2>

                <div class="flex items-center space-x-3">
                    <div class="avatar online">
                        <div class="w-12 h-12 rounded-full ring-2 ring-primary/30 ring-offset-2">
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($teacherName) ?>"
                                alt="<?= htmlspecialchars($teacherName) ?>" class="rounded-full object-cover" />
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

            <main class="flex-1 overflow-x-hidden overflow-y-auto p-6">
                <?php if (isset($_SESSION['error'])): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
                <?php endif; ?>

                <div class="bg-white rounded-2xl shadow-2xl p-4 max-w-4xl mx-auto space-y-6">
                    <div class="border-b pb-4 flex justify-between items-center">
                        <h2 class="text-3xl font-bold text-gray-800 flex items-center">
                            <i class="fas fa-edit text-primary mr-4"></i>
                            Modifier le Cours
                        </h2>
                    </div>

                    <form id="courseUpdateForm" action="" method="post" class="space-y-8">
                        <input type="hidden" name="course_id" value="<?= $courseId ?>">

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="relative group">
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Titre du Cours <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-book absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400 
                              group-focus-within:text-primary transition-colors"></i>
                                    <input type="text" name="title" value="<?= htmlspecialchars($course['title']) ?>"
                                        required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                        focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                        transition duration-300" placeholder="Nom du cours">
                                </div>
                            </div>

                            <div class="relative group">
                                <label class="block text-gray-700 font-semibold mb-2">
                                    Catégorie <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <i class="fas fa-layer-group absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400
                              group-focus-within:text-primary transition-colors"></i>
                                    <select name="category_id" required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                        focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                        transition duration-300">
                                        <?php foreach ($data['categories'] as $category): ?>
                                        <option value="<?= $category['id'] ?>"
                                            <?= $category['id'] == $course['category_id'] ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($category['title']) ?>
                                        </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-semibold mb-2">
                                Description <span class="text-red-500">*</span>
                            </label>
                            <textarea name="description" required rows="4" class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg 
                focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                transition duration-300"
                                placeholder="Décrivez votre cours..."><?= htmlspecialchars($course['description']) ?></textarea>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Type de Contenu <span class="text-red-500">*</span>
                            </label>
                            <div class="flex space-x-6">
                                <?php 
                $contentTypes = [
                    'video' => 'Vidéo', 
                    'document' => 'Document'
                ];
                foreach ($contentTypes as $type => $label):
                ?>
                                <label class="flex items-center cursor-pointer">
                                    <input type="radio" name="contentType" value="<?= $type ?>" <?= (strpos($course['content'], 'http') !== false && $type === 'video') || 
                                (strpos($course['content'], 'http') === false && $type === 'document') 
                                ? 'checked' : '' ?> class="form-radio text-primary">
                                    <span class="ml-2"><?= $label ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>

                            <div id="videoContent"
                                class="mt-4 <?= strpos($course['content'], 'http') !== false ? '' : 'hidden' ?>">
                                <div class="relative">
                                    <i
                                        class="fas fa-video absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                    <input type="url" name="videoUrl"
                                        value="<?= strpos($course['content'], 'http') !== false ? htmlspecialchars($course['content']) : '' ?>"
                                        <?= strpos($course['content'], 'http') !== false ? 'required' : '' ?> class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                        focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                        transition duration-300" placeholder="URL de la vidéo (YouTube, Vimeo)">
                                </div>
                                <p class="text-sm text-gray-500 mt-2">Collez l'URL de votre vidéo YouTube ou Vimeo</p>
                            </div>

                            <div id="documentContent"
                                class="mt-4 <?= strpos($course['content'], 'http') === false ? '' : 'hidden' ?>">
                                <textarea name="documentContent" rows="10"
                                    <?= strpos($course['content'], 'http') === false ? 'required' : '' ?> class="w-full px-4 py-3 border-2 border-gray-200 rounded-lg 
                    focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                    transition duration-300"
                                    placeholder="Contenu du document..."><?= strpos($course['content'], 'http') === false ? htmlspecialchars($course['content']) : '' ?></textarea>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-gray-700 font-semibold mb-2">
                                Image de Couverture <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <i
                                    class="fas fa-image absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                <input type="url" name="coverImageUrl" value="<?= htmlspecialchars($course['image']) ?>"
                                    required class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-lg 
                    focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 
                    transition duration-300" placeholder="URL de l'image de couverture">
                            </div>
                            <?php if ($course['image']): ?>
                            <div class="mt-4">
                                <img src="<?= htmlspecialchars($course['image']) ?>" alt="Aperçu de l'image"
                                    class="w-full h-64 object-cover rounded-lg shadow-md">
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="space-y-4">
                            <label class="block text-gray-700 font-semibold mb-2">Tags</label>
                            <div class="flex flex-wrap gap-3">
                                <?php foreach ($data['tags'] as $tag): ?>
                                <label
                                    class="inline-flex items-center bg-gray-100 px-3 py-1.5 rounded-full cursor-pointer">
                                    <input type="checkbox" name="tags[]" value="<?= $tag['id'] ?>"
                                        <?= in_array($tag['id'], $course['tags']) ? 'checked' : '' ?>
                                        class="form-checkbox text-primary mr-2">
                                    <span class="text-sm text-gray-700"><?= htmlspecialchars($tag['title']) ?></span>
                                </label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="flex justify-end space-x-4 pt-6 border-t">
                            <a href="home.php"
                                class="px-6 py-2.5 bg-gray-100 text-gray-800 rounded-lg hover:bg-gray-200 transition">
                                Annuler
                            </a>
                            <button type="submit"
                                class="px-6 py-2.5 bg-primary text-white rounded-lg hover:bg-primary-dark transition">
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
    <script>
    document.getElementById('courseUpdateForm').addEventListener('submit', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'Voulez-vous sauvegarder les modifications?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Oui, sauvegarder!',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Modifications Sauvegardées!',
                    text: 'Votre cours a été mis à jour avec succès.',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                }).then(() => {
                    this.submit();
                });
            }
        });
    });
    </script>
</body>

</html>