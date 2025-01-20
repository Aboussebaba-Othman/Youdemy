<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <div class="bg-white shadow-lg rounded-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div>
                        <h1 id="course-title" class="text-3xl font-bold mb-2">
                            Développement Web Fullstack
                        </h1>
                        <span id="course-style" class="bg-white/20 px-3 py-1 rounded-full">
                            Cours Intensif
                        </span>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-2 gap-8 p-6">
                <div>
                    <img id="course-image"
                        src="https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80"
                        alt="Course Image" class="w-full h-64 object-cover rounded-lg shadow-md">
                </div>

                <div>
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Description</h2>
                    <p id="course-description" class="text-gray-600 mb-6">
                        Un cours complet pour devenir un développeur web professionnel,
                        couvrant les technologies frontend et backend les plus récentes.
                    </p>

                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="text-xl font-semibold mb-3 text-gray-800">Instructeur</h3>
                        <div class="flex items-center">
                            <img id="teacher-image" src="https://randomuser.me/api/portraits/men/32.jpg"
                                alt="Instructeur" class="w-16 h-16 rounded-full mr-4 object-cover">
                            <div>
                                <h4 id="teacher-name" class="text-lg font-semibold">Jean Dupont</h4>
                                <p id="teacher-email" class="text-gray-600 text-sm">
                                    jean.dupont@example.com
                                </p>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold mb-3 text-gray-800">Compétences Acquises</h3>
                        <div id="course-skills" class="flex flex-wrap gap-2">
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                HTML5
                            </span>
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                CSS3
                            </span>
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                JavaScript
                            </span>
                            <span class="bg-red-100 text-red-800 px-3 py-1 rounded-full text-sm">
                                React
                            </span>
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                Node.js
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-6 border-t">
                <button id="register-btn" class="w-full bg-blue-500 text-white py-3 rounded-lg 
                           hover:bg-blue-600 transition duration-300 
                           flex items-center justify-center">
                    <i class="fas fa-graduation-cap mr-3"></i>
                    S'inscrire au cours
                </button>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const courseDetails = {
            title: "Développement Web Fullstack",
            style: "Cours Intensif",
            description: "Un cours complet pour devenir un développeur web professionnel, couvrant les technologies frontend et backend les plus récentes.",
            image: "https://images.unsplash.com/photo-1498050108023-c5249f4df085?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1472&q=80",
            teacher: {
                name: "Jean Dupont",
                email: "jean.dupont@example.com",
                image: "https://randomuser.me/api/portraits/men/32.jpg"
            },
            skills: [
                "HTML5",
                "CSS3",
                "JavaScript",
                "React",
                "Node.js"
            ]
        };

        document.getElementById('course-title').textContent = courseDetails.title;
        document.getElementById('course-style').textContent = courseDetails.style;
        document.getElementById('course-description').textContent = courseDetails.description;
        document.getElementById('course-image').src = courseDetails.image;

        document.getElementById('teacher-name').textContent = courseDetails.teacher.name;
        document.getElementById('teacher-email').textContent = courseDetails.teacher.email;
        document.getElementById('teacher-image').src = courseDetails.teacher.image;

        const skillsContainer = document.getElementById('course-skills');
        skillsContainer.innerHTML = courseDetails.skills.map(skill => `
                <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                    ${skill}
                </span>
            `).join('');

        document.getElementById('register-btn').addEventListener('click', function() {
            alert('Inscription au cours : ' + courseDetails.title);
        });
    });
    </script>
</body>

</html>