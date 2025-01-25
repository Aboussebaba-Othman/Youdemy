<?php
require_once("../../../vendor/autoload.php");
require '../../../src/Controllers/Auth/RegisterController.php';

use App\Controllers\Auth\RegisterController;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registerController = new RegisterController();
    $registerController->register($_POST);
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Youdemy - Inscription</title>
    
    <script>
    tailwind.config = {
        theme: {
            extend: {
                colors: {
                    primary: '#a435f0',
                    secondary: '#1c1d1f',
                }
            }
        }
    }
    </script>
    
    <style>
        .nav-hover:hover {
            transform: scale(1.05);
            transition: transform 0.3s ease;
        }
        .form-container {
            background: linear-gradient(to right, #ffffff 0%, #f9f5ff 100%);
            box-shadow: 0 10px 25px rgba(164, 53, 240, 0.1);
            transition: all 0.3s ease;
        }
        .form-container:hover {
            box-shadow: 0 15px 35px rgba(164, 53, 240, 0.15);
        }
        .input-focus:focus {
            border-color: #a435f0;
            box-shadow: 0 0 0 3px rgba(164, 53, 240, 0.1);
            outline: none;
        }
    </style>
</head>
<body class="bg-gray-50">
<nav class="fixed w-full bg-base-300 shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="../index.php" class="flex items-center transition-transform hover:scale-105">
                        <i class="fas fa-graduation-cap text-primary text-2xl mr-2"></i>
                        <span class="text-2xl font-bold text-primary font-serif font-bold">Youdemy</span>
                    </a>
                </div>

                <div class="hidden md:flex items-center flex-1 px-8">

                    <div class="flex-1 px-8">
                        <div class="relative">
                            <input type="text" placeholder="Rechercher un cours..."
                                class="w-full pl-10 pr-4 py-2 bg-base-200 border border-accent rounded-full focus:outline-none focus:border-primary focus:ring-2 focus:ring-primary/20 transition-all duration-200">
                            <i class="fas fa-search absolute left-3 top-3 text-accent"></i>
                        </div>
                    </div>
                </div>

                <div class="hidden md:flex items-center space-x-4">
                    <button
                        class="px-4 py-2 text-secondary hover:text-primary transition-colors duration-200 hover:bg-base-200 rounded-md"><a
                            href="auth/login.php">Login</a></button>
                    <button
                        class="px-6 py-2 bg-primary text-base-300 rounded-full hover:bg-secondary transition-colors duration-200 shadow-md hover:shadow-lg"><a
                            href="auth/register.php">Register</a></button>
                </div>

                <div class="md:hidden">
                    <button class="text-primary hover:text-secondary transition-colors" id="mobile-menu-button">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <section class="pt-24 pb-12 px-4">
        <div class="container mx-auto max-w-md">
            <div class="form-container rounded-xl p-8 shadow-lg">
                <div class="text-center mb-8">
                    <h1 class="text-3xl font-bold text-secondary mb-2">
                        Créer un Compte
                    </h1>
                    <p class="text-gray-600">
                        Rejoignez Youdemy et commencez votre apprentissage
                    </p>
                </div>

                <form action="" method="POST" class="space-y-4">
                    <div>
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700">
                            Nom Complet
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus" 
                            placeholder="Votre nom complet"
                            required
                        >
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                            Email
                        </label>
                        <input 
                            type="email" 
                            name="email" 
                            id="email" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus" 
                            placeholder="nom@example.com"
                            required
                        >
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="password" class="block mb-2 text-sm font-medium text-gray-700">
                                Mot de passe
                            </label>
                            <input 
                                type="password" 
                                name="password" 
                                id="password" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus" 
                                required
                            >
                        </div>

                        <div>
                            <label for="role" class="block mb-2 text-sm font-medium text-gray-700">
                                Rôle
                            </label>
                            <select 
                                name="role_id" 
                                id="role" 
                                onchange="displayInput()" 
                                class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus"
                                required
                            >
                                <option value="">Sélectionnez un rôle</option>
                                <option value="3">Étudiant</option>
                                <option value="2">Enseignant</option>
                            </select>
                        </div>
                    </div>

                    <div id="education-level-container" class="hidden">
                        <label for="education_level" class="block mb-2 text-sm font-medium text-gray-700">
                            Niveau d'études
                        </label>
                        <input 
                            type="text" 
                            name="education_level" 
                            id="education_level" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus"
                        >
                    </div>

                    <div id="specialty-container" class="hidden">
                        <label for="specialty" class="block mb-2 text-sm font-medium text-gray-700">
                            Spécialité
                        </label>
                        <input 
                            type="text" 
                            name="specialty" 
                            id="specialty" 
                            class="w-full px-4 py-2 rounded-lg border border-gray-300 input-focus"
                        >
                    </div>

                    <div class="flex items-center">
                        <input 
                            type="checkbox" 
                            id="terms" 
                            class="mr-2 rounded text-primary focus:ring-primary"
                            required
                        >
                        <label for="terms" class="text-sm text-gray-600">
                            J'accepte les 
                            <a href="#" class="text-primary hover:underline">
                                Conditions d'utilisation
                            </a>
                        </label>
                    </div>

                    <button type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-700 dark:border-transparent dark:hover:bg-indigo-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-2 disabled:cursor-wait disabled:opacity-50">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
        
                            </span>
                            Créer un compte
                        </button>

                    <div class="text-center mt-4">
                        <p class="text-sm text-gray-600">
                            Vous avez déjà un compte ? 
                            <a href="login.php" class="text-primary hover:underline">
                                Connectez-vous
                            </a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </section>

    <script>
        function displayInput() {
            const role = document.getElementById('role').value;
            const educationContainer = document.getElementById('education-level-container');
            const specialtyContainer = document.getElementById('specialty-container');

            if (role === "3") {
                educationContainer.classList.remove('hidden');
                specialtyContainer.classList.add('hidden');
            } else if (role === "2") { 
                specialtyContainer.classList.remove('hidden');
                educationContainer.classList.add('hidden');
            } else {
                educationContainer.classList.add('hidden');
                specialtyContainer.classList.add('hidden');
            }
        }
    </script>
</body>
</html>