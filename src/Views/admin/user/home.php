<?php
session_start();
require_once "../../../../vendor/autoload.php";

use App\Models\Admin\UserModel;
use App\Controllers\Admin\UserController;

$userModel = new UserModel();
$userController = new UserController($userModel);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        $id = $_POST['delete'];
        $message = $userController->deleteUser($id) ? "Utilisateur supprimé" : "Erreur lors de la suppression";
        header("Location: home.php?message=$message");
        exit;
    }

    if (isset($_POST['toggle'])) {
        $id = $_POST['toggle'];
        $message = $userController->toggleStatus($id) ? "Statut mis à jour" : "Erreur lors de la mise à jour du statut";
        header("Location: home.php?message=$message");
        exit;
    }
}

$users = $userController->fetchUsers();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Gestion des Utilisateurs</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
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

    html {
        scrollbar-gutter: stable;
    }
    </style>
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: {
                        DEFAULT: 'rgb(51, 44, 192)',
                        light: '#c661f3',
                        dark: '#7a2cc0'
                    }
                }
            }
        }
    }
    </script>
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
            ['icon' => 'tachometer-alt', 'label' => 'Tableau de Bord', 'link' => '../home.php', 'path' => 'home'],
            ['icon' => 'users', 'label' => 'Gestion Utilisateurs', 'link' => '../user/home.php', 'path' => 'user'],
            ['icon' => 'book', 'label' => 'Gestion Cours', 'link' => '../cour/home.php', 'path' => 'cour'],
            ['icon' => 'tags', 'label' => 'Gestion Tags', 'link' => '../tag/home.php', 'path' => 'tag'],
            ['icon' => 'folder', 'label' => 'Gestion Catégories', 'link' => '../categorie/home.php', 'path' => 'categorie'],
            ['icon' => 'user-check', 'label' => 'Validation Enseignants', 'link' => '../validation/home.php', 'path' => 'validation']
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

            <a href="../../auth/login.php"
                class="px-6 py-4 flex items-center text-red-500 hover:bg-red-50 transition-colors duration-300 group">
                <i
                    class="fas fa-sign-out-alt w-6 mr-3 group-hover:-translate-x-1 transition-transform duration-300"></i>
                <span class="font-medium">Déconnexion</span>
            </a>
        </div>

        <div class="flex-1 flex flex-col overflow-hidden">
            <header class="bg-white shadow-sm border-b sticky top-0 z-20">
                <div class="flex justify-between items-center px-6 py-4">
                    <h2 class="text-xl font-semibold text-gray-800">Gestion des Utilisateurs</h2>

                    <div class="flex items-center space-x-4">
                        <button class="text-gray-500 hover:text-primary relative">
                            <i class="fas fa-bell"></i>
                            <span
                                class="absolute -top-2 -right-2 h-4 w-4 bg-red-500 text-white rounded-full flex items-center justify-center text-xs">3</span>
                        </button>

                        <div class="flex items-center">
                            <div class="w-8 h-8 rounded-full bg-primary/20 flex items-center justify-center mr-2">
                                <span class="text-primary text-sm font-bold">A</span>
                            </div>
                            <span class="text-gray-700">Admin</span>
                        </div>
                    </div>
                </div>
            </header>

            <main class="flex-1 overflow-y-auto p-6">
                <div class="mb-6 sticky top-0 z-10 bg-white pb-4">
                    <div class="flex justify-between items-center">
                        <div class="relative flex-grow mr-4">
                            <input type="text" id="searchInput" placeholder="Rechercher par nom ou email..."
                                class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:outline-none">
                            <i class="fas fa-search absolute right-3 top-3 text-gray-400"></i>
                        </div>

                        <select id="roleFilter"
                            class="px-4 py-2 border rounded-lg focus:ring-2 focus:ring-primary/50 focus:outline-none cursor-pointer">
                            <option value="">Tous les utilisateurs</option>
                            <option value="Student">Étudiants</option>
                            <option value="Teacher">Enseignants</option>
                        </select>
                    </div>

                    <div id="resultCount" class="text-sm text-gray-500 mt-2">
                        Affichage de tous les utilisateurs
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <table class="w-full">
                        <thead class="bg-gray-50 border-b sticky top-0 z-10">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Nom</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Email</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rôle</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Statut</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($users as $user): ?>
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div
                                            class="flex-shrink-0 h-10 w-10 rounded-full bg-primary/20 flex items-center justify-center">
                                            <span class="text-primary font-bold">
                                                <?= strtoupper(substr($user['name'], 0, 1)); ?>
                                            </span>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($user['name']); ?>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($user['email']); ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $user['role'] === 'Admin' ? 'bg-red-100 text-red-800' : 
                                               ($user['role'] === 'Enseignant' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800') ?>">
                                        <?= htmlspecialchars($user['role']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= $user['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                        <?= htmlspecialchars($user['status']); ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-3">
                                        <form action="" method="POST" class="inline-block">
                                            <input type="hidden" name="toggle" value="<?= $user['id']; ?>">
                                            <button type="submit"
                                                class="text-yellow-600 hover:text-yellow-900 transition">
                                                <?= $user['status'] === 'active' ? 'Bloquer' : 'Débloquer'; ?>
                                            </button>
                                        </form>
                                        <form action="" method="POST" class="inline-block">
                                            <input type="hidden" name="delete" value="<?= $user['id']; ?>">
                                            <button type="submit"
                                                onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');"
                                                class="text-red-600 hover:text-red-900 transition">
                                                Supprimer
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>

                    </table>
                </div>
            </main>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const roleFilter = document.getElementById('roleFilter');
        const tableRows = document.querySelectorAll('tbody tr');
        const resultCount = document.getElementById('resultCount');

        function filterTable() {
            const searchTerm = searchInput.value.toLowerCase();
            const selectedRole = roleFilter.value;
            let visibleCount = 0;

            tableRows.forEach(row => {
                // Get base data
                const nameCell = row.querySelector('td:nth-child(1)');
                const emailCell = row.querySelector('td:nth-child(2)');
                const roleCell = row.querySelector('td:nth-child(3) span');

                const name = nameCell.textContent.toLowerCase().trim();
                const email = emailCell.textContent.toLowerCase().trim();
                const role = roleCell.textContent.trim();

                // Handle search match
                const matchesSearch = name.includes(searchTerm) || email.includes(searchTerm);

                // Handle role match
                let matchesRole = true;
                if (selectedRole) {
                    matchesRole = role === selectedRole;
                    console.log(`Comparing: "${role}" === "${selectedRole}"`, matchesRole);
                }

                // Apply visibility
                const isVisible = matchesSearch && matchesRole;
                row.style.display = isVisible ? '' : 'none';

                if (isVisible) visibleCount++;
            });

            // Update result count message
            if (selectedRole) {
                const roleText = selectedRole === 'Etudiant' ? 'étudiants' : 'enseignants';
                resultCount.textContent =
                    `Affichage de ${visibleCount} ${visibleCount > 1 ? roleText : roleText.slice(0, -1)}`;
            } else {
                resultCount.textContent =
                    `Affichage de ${visibleCount} utilisateur${visibleCount > 1 ? 's' : ''}`;
            }
        }

        // Add event listeners
        searchInput.addEventListener('input', () => {
            clearTimeout(window.searchTimeout);
            window.searchTimeout = setTimeout(filterTable, 300);
        });

        roleFilter.addEventListener('change', filterTable);

        // Initial filter
        filterTable();
    });
    </script>
</body>

</html>