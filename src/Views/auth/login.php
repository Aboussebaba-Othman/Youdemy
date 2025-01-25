<?php
session_start();
require_once("../../../vendor/autoload.php");
use App\Controllers\Auth\LoginController;

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $loginController = new LoginController();
    $result = $loginController->login(
        $_POST['email'] ?? '', 
        $_POST['password'] ?? ''
    );
    
    if ($result['status'] === 'success') {
        header('Location: ' . $result['redirect']);
        exit();
    } else {
        $error_message = $result['message'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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
<body>
    <?php if (!empty($error_message)): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
            <span class="block sm:inline"><?php echo htmlspecialchars($error_message); ?></span>
        </div>
    <?php endif; ?>
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

    <section class="bg-gray-50 dark:bg-gray-800 min-h-screen flex items-center justify-center">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white dark:bg-gray-700 px-4 pb-4 pt-4 sm:rounded-lg sm:px-10 sm:pb-6 sm:shadow">
                <div class="text-center sm:mx-auto sm:w-full sm:max-w-md">
                    <h1 class="text-3xl font-extrabold text-gray-900 dark:text-white">
                        Login
                    </h1>
                </div>
                <form class="space-y-6" method="POST">
                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Your email</label>
                        <input type="email" name="email" id="email" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="name@company.com" >
                    </div>
                    <div>
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Password</label>
                        <input type="password" name="password" id="password" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" >
                    </div>
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 dark:text-white dark:border-gray-600 dark:focus:ring-indigo-400 disabled:cursor-wait disabled:opacity-50">
                            <label for="remember_me" class="ml-2 block text-sm text-gray-900 dark:text-white">Remember me</label>
                        </div>
                        <div class="text-sm">
                            <a class="font-medium text-indigo-400 hover:text-indigo-500" href="forgot-password.php">
                                Forgot your password?
                            </a>
                        </div>
                    </div>
                    <div>
                        <button type="submit" class="group relative flex w-full justify-center rounded-md border border-transparent bg-indigo-600 px-4 py-2 text-sm font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:bg-indigo-700 dark:border-transparent dark:hover:bg-indigo-600 dark:focus:ring-indigo-400 dark:focus:ring-offset-2 disabled:cursor-wait disabled:opacity-50">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                                <svg class="h-5 w-5 text-indigo-500 group-hover:text-indigo-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                </svg>
                            </span>
                            Sign In
                        </button>
                    </div>
                </form>
                <div class="m-auto mt-6 w-fit md:mt-8">
                    <span class="m-auto dark:text-gray-400">Don't have an account?
                        <a class="font-semibold text-indigo-600 dark:text-indigo-100" href="register.php">Create Account</a>
                    </span>
                </div>
            </div>
        </div>
    </section>
</body>
</html>