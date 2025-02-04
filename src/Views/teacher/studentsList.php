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
    $errorMessage = "Une erreur est survenue lors du chargement des données.";
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Étudiants - Youdemy</title>
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
        /* transform: translateX(5px); */
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
                <a href="home.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-book w-6 text-blue-300"></i>
                    <span>Mes Cours</span>
                </a>
                <a href="statistiques.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg hover:bg-white/10 transition">
                    <i class="fas fa-chart-line w-6 text-blue-300"></i>
                    <span>Statistiques</span>
                </a>
                <a href="studentsList.php"
                    class="flex items-center space-x-3 py-3 px-4 rounded-lg bg-white/10 text-yellow-300">
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


        <div class="flex-1 flex flex-col bg-gray-100">

            <header
                class="bg-white shadow-md border-b border-gray-100 px-6 py-4 flex justify-between items-center sticky top-0 z-50">
                <div class="flex items-center space-x-4">
                    <h2
                        class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-primary to-purple-600">
                        Liste des Étudiants
                    </h2>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="relative group">
                        <input type="text" id="searchInput" placeholder="Rechercher des étudiants..." class="pl-12 pr-4 py-2.5 
                       border-2 border-transparent 
                       bg-gray-100 
                       rounded-full 
                       focus:border-primary 
                       focus:ring-2 focus:ring-primary/30 
                       transition-all duration-300 
                       text-sm 
                       w-72">
                        <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 
                      text-gray-500 group-focus-within:text-primary 
                      transition-colors duration-300"></i>
                    </div>

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
                </div>
            </header>

            <main class="p-6 flex-grow">
                <div class="bg-white rounded-xl shadow-md overflow-hidden">
                    <div class="p-6 bg-gray-50 border-b flex justify-between items-center">
                        <h3 class="text-xl font-semibold text-gray-800">Mes Étudiants</h3>
                        <div class="relative dropdown">
                            <button id="exportExcel"
                                class="px-4 py-2 bg-primary hover:bg-primary-dark text-white rounded-lg flex items-center space-x-2 transition-colors">
                                <i class="fas fa-download"></i>
                                <span>Exporter</span>

                            </button>

                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-100 border-b">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom
                                        Complet</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cours
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date
                                        d'Inscription</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($students)): ?>
                                <?php foreach ($students as $student): ?>
                                <tr class="student-row hover:bg-gray-50 border-b">
                                    <td class="px-6 py-4 flex items-center">
                                        <div
                                            class="w-10 h-10 rounded-full bg-primary/20 flex items-center justify-center mr-3">
                                            <span class="text-primary font-bold">
                                                <?= strtoupper(substr($student['name'], 0, 1)) ?>
                                            </span>
                                        </div>
                                        <?= htmlspecialchars($student['name']) ?>
                                    </td>
                                    <td class="px-6 py-4"><?= htmlspecialchars($student['email']) ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-primary/10 text-primary rounded-full text-xs">
                                            <?= htmlspecialchars($courses[$student['course_id']] ?? 'Inconnu') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <?= date('d/m/Y', strtotime($student['enrollment_date'])) ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                                <?php else: ?>
                                <tr>
                                    <td colspan="4" class="text-center py-6 text-gray-500">
                                        Aucun étudiant trouvé
                                    </td>
                                </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </main>
        </div>
    </div>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.querySelector('#searchInput');
        const studentRows = document.querySelectorAll('tbody tr');

        function searchStudents() {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let visibleCount = 0;

            studentRows.forEach(row => {
                const name = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                const courseCount = row.querySelector('td:nth-child(2)').textContent.toLowerCase();

                const matches = name.includes(searchTerm) || courseCount.includes(searchTerm);

                row.style.display = matches ? '' : 'none';

                if (matches) visibleCount++;
            });

            const resultCount = document.querySelector('#resultCount');
            if (resultCount) {
                resultCount.textContent =
                    `${visibleCount} étudiant${visibleCount > 1 ? 's' : ''} trouvé${visibleCount > 1 ? 's' : ''}`;
            }
        }

        let searchTimeout;
        searchInput.addEventListener('input', () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(searchStudents, 300);
        });

        searchStudents();
    });

    document.querySelector('.clear-search')?.addEventListener('click', () => {
        document.querySelector('#searchInput').value = '';
        searchStudents();
    });
    </script>
    <!-- export -->
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script>
    document.getElementById('exportExcel').addEventListener('click', function() {
        // Obtenir toutes les lignes visibles
        const rows = Array.from(document.querySelectorAll('tbody tr')).filter(row =>
            row.style.display !== 'none'
        );

        // Transformer les données
        const data = rows.map(row => {
            const nameCell = row.querySelector('td:nth-child(1)');
            // Récupérer uniquement le texte du nom en excluant l'initiale
            const fullNameWithoutInitial = nameCell.textContent.replace(/^\s+/, '').trim();

            return {
                'Nom Complet': fullNameWithoutInitial,
                'Email': row.querySelector('td:nth-child(2)').textContent.trim(),
                'Cours': row.querySelector('td:nth-child(3) span').textContent.trim(),
                'Date d\'inscription': row.querySelector('td:nth-child(4)').textContent.trim()
            };
        });

        // Créer la feuille Excel
        const worksheet = XLSX.utils.json_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "Liste des étudiants");

        // Styliser l'en-tête
        const range = XLSX.utils.decode_range(worksheet['!ref']);

        // Style pour l'en-tête
        const headerStyle = {
            font: {
                bold: true,
                color: {
                    rgb: "FFFFFF"
                }
            },
            fill: {
                fgColor: {
                    rgb: "4338CA"
                }
            },
            alignment: {
                horizontal: 'center',
                vertical: 'center'
            }
        };

        // Appliquer le style à l'en-tête
        for (let C = range.s.c; C <= range.e.c; ++C) {
            const address = XLSX.utils.encode_cell({
                r: 0,
                c: C
            });
            if (!worksheet[address]) continue;
            worksheet[address].s = headerStyle;
        }

        // Ajuster la largeur des colonnes
        const columnWidths = data.reduce((acc, row) => {
            Object.entries(row).forEach(([key, value], index) => {
                const valueLength = value ? value.toString().length : 0;
                acc[index] = Math.max(acc[index] || 0, valueLength, key.length);
            });
            return acc;
        }, []);

        worksheet['!cols'] = columnWidths.map(width => ({
            width: width + 2
        }));

        // Style pour les cellules de données
        for (let R = 1; R <= range.e.r; ++R) {
            for (let C = range.s.c; C <= range.e.c; ++C) {
                const address = XLSX.utils.encode_cell({
                    r: R,
                    c: C
                });
                if (!worksheet[address]) continue;

                worksheet[address].s = {
                    font: {
                        name: "Arial"
                    },
                    alignment: {
                        vertical: 'center'
                    },
                    border: {
                        top: {
                            style: 'thin',
                            color: {
                                rgb: "E5E7EB"
                            }
                        },
                        bottom: {
                            style: 'thin',
                            color: {
                                rgb: "E5E7EB"
                            }
                        },
                        left: {
                            style: 'thin',
                            color: {
                                rgb: "E5E7EB"
                            }
                        },
                        right: {
                            style: 'thin',
                            color: {
                                rgb: "E5E7EB"
                            }
                        }
                    }
                };
            }
        }

        // Générer et télécharger le fichier
        const date = new Date().toLocaleDateString('fr-FR').replace(/\//g, '-');
        XLSX.writeFile(workbook, `liste_etudiants_${date}.xlsx`);
    });
    </script>
</body>

</html>