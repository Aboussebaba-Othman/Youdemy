<!DOCTYPE html>
<html lang="en" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Youdemy - Online Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/daisyui@latest/dist/full.css" rel="stylesheet" type="text/css" />
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
</head>

<body class="bg-white">
    <nav class="fixed w-full bg-base-300 shadow-lg z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <div class="flex items-center">
                    <a href="/" class="flex items-center transition-transform hover:scale-105">
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
                            href="mesCourse.php">Mes course</a></button>
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

    <footer class="bg-secondary text-base-300 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-lg  mb-4  font-bold">À propos de Youdemy</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Qui sommes-nous</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Carrières</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Presse</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Ressources</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Blog</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Tutoriels</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">FAQ</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Communauté</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="hover:text-base-200 transition-colors">Forum</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Événements</a></li>
                        <li><a href="#" class="hover:text-base-200 transition-colors">Partenaires</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg  mb-4  font-bold">Suivez-nous</h3>
                    <div class="flex space-x-4">
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-facebook"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-twitter"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-instagram"></i></a>
                        <a href="#" class="text-2xl hover:text-base-200 transition-colors"><i
                                class="fab fa-linkedin"></i></a>
                    </div>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-accent text-center">
                <p>&copy; 2023 Youdemy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>

</html>