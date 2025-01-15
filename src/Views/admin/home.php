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
                    <div class="relative">
                        <img src="https://via.placeholder.com/40" class="rounded-full" alt="Admin">
                        <span class="absolute bottom-0 right-0 bg-green-500 h-3 w-3 rounded-full"></span>
                    </div>
                </div>
            </header>

            <div class="grid grid-cols-4 gap-4 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Total Cours</h3>
                            <p class="text-2xl font-bold">250</p>
                        </div>
                        <i class="fas fa-book text-blue-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Utilisateurs</h3>
                            <p class="text-2xl font-bold">1,250</p>
                        </div>
                        <i class="fas fa-users text-green-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Enseignants</h3>
                            <p class="text-2xl font-bold">75</p>
                        </div>
                        <i class="fas fa-chalkboard-teacher text-purple-500 text-3xl"></i>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-gray-500">Revenus</h3>
                            <p class="text-2xl font-bold">€125,000</p>
                        </div>
                        <i class="fas fa-euro-sign text-yellow-500 text-3xl"></i>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md">
                    <h3 class="text-xl font-semibold mb-4">Cours par Catégorie</h3>
                    <canvas id="courseChart"></canvas>
                </div>

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
                            <tr>
                                <td class="p-2">Jean Dupont</td>
                                <td class="p-2 text-center">15</td>
                                <td class="p-2 text-center">450</td>
                            </tr>
                            <tr>
                                <td class="p-2">Marie Martin</td>
                                <td class="p-2 text-center">12</td>
                                <td class="p-2 text-center">350</td>
                            </tr>
                            <tr>
                                <td class="p-2">Pierre Dubois</td>
                                <td class="p-2 text-center">10</td>
                                <td class="p-2 text-center">300</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('courseChart').getContext('2d');
        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Développement Web', 'Data Science', 'Design', 'Marketing'],
                datasets: [{
                    data: [45, 25, 15, 15],
                    backgroundColor: ['#3B82F6', '#10B981', '#6366F1', '#F43F5E']
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    </script>
</body>
</html>