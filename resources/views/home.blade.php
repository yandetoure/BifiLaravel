<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BiFi - Faites vous remarquer !</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#eb6d19',
                        secondary: '#5aa9a4',
                        tertiary: '#007590',
                    }
                }
            }
        }
    </script>
    <style>
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 6s ease-in-out infinite;
        }

        .navbar-scroll {
            background-color: rgba(0, 0, 0, 0.9);
            backdrop-filter: blur(10px);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        }

        .hero-overlay {
            background: linear-gradient(to right, rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.3));
        }

        .whatsapp-float {
            position: fixed;
            width: 60px;
            height: 60px;
            bottom: 40px;
            right: 40px;
            background-color: #25d366;
            color: #FFF;
            border-radius: 50px;
            text-align: center;
            font-size: 30px;
            box-shadow: 2px 2px 3px #999;
            z-index: 100;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
        }

        .whatsapp-float:hover {
            transform: scale(1.1);
            background-color: #128C7E;
        }

        .bitcoin-logo {
            position: relative;
            width: 60px;
            height: 60px;
            background: #5aa9a4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 28px;
            box-shadow: 0 0 10px rgba(235, 109, 25, 0.5);
        }

        .bitcoin-logo::after {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            border: 2px dashed rgba(235, 109, 25, 0.5);
            border-radius: 50%;
            animation: spin 20s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .fade-in {
            animation: fadeIn 1s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body class="font-sans antialiased text-gray-900">
    <!-- WhatsApp Float Button -->
    <a href="https://wa.me/221757506767" class="whatsapp-float" target="_blank">
        <i class="fab fa-whatsapp"></i>
    </a>

    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20 items-center">
                <div class="flex-shrink-0 flex items-center">
                    <div class="flex items-center space-x-2">
                        <div class="bitcoin-logo">₿</div>
                        <span class="text-white text-2xl font-bold">₿iFi</span>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-center space-x-8">
                        <a href="#accueil" class="text-white hover:text-primary px-3 py-2 rounded-md text-lg font-medium transition-colors">Accueil</a>
                        <a href="#services" class="text-white hover:text-primary px-3 py-2 rounded-md text-lg font-medium transition-colors">Services</a>
                        <a href="#about" class="text-white hover:text-primary px-3 py-2 rounded-md text-lg font-medium transition-colors">À propos</a>
                        <a href="#contact" class="text-white hover:text-primary px-3 py-2 rounded-md text-lg font-medium transition-colors">Contact</a>
                        <a href="{{ route('bills.create') }}" class="bg-secondary text-white px-6 py-2 rounded-lg text-lg font-semibold hover:bg-opacity-90">Payer une facture</a>
                        <a href="{{ route('login') }}" class="bg-primary text-white px-6 py-2 rounded-lg text-lg font-semibold hover:bg-opacity-90 transition-colors">Connexion</a>
                    </div>
                </div>
                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-white focus:outline-none">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
            </div>
</div>

        <!-- Mobile menu -->
        <div id="mobile-menu" class="md:hidden hidden bg-black bg-opacity-90">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="#accueil" class="text-white block px-3 py-2 rounded-md text-base font-medium">Accueil</a>
                <a href="#services" class="text-white block px-3 py-2 rounded-md text-base font-medium">Services</a>
                <a href="#about" class="text-white block px-3 py-2 rounded-md text-base font-medium">À propos</a>
                <a href="#contact" class="text-white block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                <a href="{{ route('bills.create') }}" class="bg-secondary text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Payer une facture</a>
                <a href="{{ route('login') }}" class="bg-primary text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Connexion</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative h-screen flex items-center justify-center overflow-hidden" id>
        <!-- Background image with overlay -->
        <div class="absolute inset-0">
            <img src="https://images.unsplash.com/photo-1522071820081-009f0129c71c?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80"
                 alt="Équipe de collaborateurs"
                 class="w-full h-full object-cover">
    <div class="absolute inset-0 hero-overlay"></div>
        </div>

        <!-- Hero content -->
        <div class="relative z-10 text-center px-4 sm:px-6 lg:px-8 max-w-7xl mx-auto">
            <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6">
                <span class="block">OPTIMISEZ !</span>
    <span class="text-primary">DEMATERIALISEZ !</span>
            </h1>

            <p class="text-xl md:text-2xl text-white max-w-3xl mx-auto mb-10">
    BifF révolutionne vos transactions avec une solution rapide, sécurisée et accessible à tous.
            </p>

            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a  href="{{ route('bills.create') }}" class="bg-secondary text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-opacity-90 transition-colors">
                    Payer une facture <i class="fas fa-arrow-right ml-2"></i>
                </a>
                <a href="#services" class="bg-white text-gray-900 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Nos services <i class="fas fa-chevron-down ml-2"></i>
                </a>
    </div>
        </div>

        <!-- Scroll indicator -->
        <div class="absolute bottom-10 left-1/2 transform -translate-x-1/2 animate-bounce">
            <a href="#services" class="text-white">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                </svg>
            </a>
        </div>
    </section>

    <!-- Services Section -->
    <section id="services" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Nos services exceptionnels</h2>
                <div class="w-20 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez comment BiFi simplifie vos transactions financières quotidiennes
    </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Service 1 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-primary text-2xl">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Paiements instantanés</h3>
                    <p class="text-gray-600">
                        Effectuez vos transactions en quelques secondes seulement, sans délai d'attente.
    </p>
                </div>

                <!-- Service 2 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-secondary text-2xl">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Sécurité maximale</h3>
                    <p class="text-gray-600">
                        Vos transactions sont protégées par les dernières technologies de cryptage.
    </p>
                </div>

                <!-- Service 3 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-tertiary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-tertiary text-2xl">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Accessibilité</h3>
                    <p class="text-gray-600">
                        Utilisez BiFi depuis votre smartphone, tablette ou ordinateur.
    </p>
                </div>

                <!-- Service 4 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-primary text-2xl">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Suivi en temps réel</h3>
                    <p class="text-gray-600">
                        Visualisez l'historique de vos transactions et suivez vos dépenses.
    </p>
                </div>

                <!-- Service 5 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-secondary text-2xl">
                        <i class="fas fa-headset"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Support 24/7</h3>
                    <p class="text-gray-600">
                        Notre équipe est disponible à tout moment pour vous aider.
    </p>
                </div>

                <!-- Service 6 -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow">
                    <div class="w-16 h-16 bg-tertiary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-tertiary text-2xl">
                        <i class="fas fa-hand-holding-usd"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Multiples options</h3>
                    <p class="text-gray-600">
                        Payez avec Wave, Orange Money, Wizall ou espèces selon votre préférence.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Section Moyens de Paiement -->
    <section id="payment-methods" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Nos Moyens de Paiement</h2>
                <div class="w-20 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Choisissez le mode de paiement qui vous convient le mieux parmi nos solutions sécurisées
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Carte Bancaire -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-16 h-16 bg-primary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-primary text-2xl">
                        <i class="fas fa-credit-card"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Carte Bancaire</h3>
                    <p class="text-gray-600 mb-4">
                        Paiement sécurisé par carte bancaire avec cryptage SSL et protection 3D Secure
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-2"></i>
                            Visa, Mastercard, UBA
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-primary mr-2"></i>
                            Transaction instantanée
                        </li>
                    </ul>
                </div>

                <!-- Mobile Money -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-16 h-16 bg-secondary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-secondary text-2xl">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Mobile Money</h3>
                    <p class="text-gray-600 mb-4">
                        Payez facilement avec votre mobile money, disponible 24/7
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-secondary mr-2"></i>
                            Wave, Orange Money, Free Money
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-secondary mr-2"></i>
                            Confirmation SMS
                        </li>
                    </ul>
                </div>

                <!-- Espèces -->
                <div class="bg-white p-8 rounded-xl shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                    <div class="w-16 h-16 bg-tertiary bg-opacity-10 rounded-full flex items-center justify-center mb-6 text-tertiary text-2xl">
                        <i class="fas fa-money-bill-wave"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-3">Paiement en Espèces</h3>
                    <p class="text-gray-600 mb-4">
                        Payez en espèces dans nos points de service agréés
                    </p>
                    <ul class="space-y-2 text-gray-600">
                        <li class="flex items-center">
                            <i class="fas fa-check text-tertiary mr-2"></i>
                            Réseau d'agents certifiés
                        </li>
                        <li class="flex items-center">
                            <i class="fas fa-check text-tertiary mr-2"></i>
                            Reçu immédiat
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                <div class="order-2 lg:order-1">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-6">Qui sommes-nous ?</h2>
                    <div class="w-20 h-1 bg-primary mb-6"></div>
                    <p class="text-gray-600 mb-6 text-lg">
                        BiFi est une solution de paiement innovante développée par B!consulting, conçue pour simplifier vos transactions financières quotidiennes.
                    </p>
                    <p class="text-gray-600 mb-6 text-lg">
                        Notre mission est de rendre les paiements plus accessibles, plus rapides et plus sécurisés pour tous, en utilisant les dernières technologies disponibles.
                    </p>
                    <div class="space-y-4">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <span class="font-semibold">Expérience utilisateur</span> - Interface intuitive et facile à utiliser
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <span class="font-semibold">Sécurité</span> - Cryptage avancé pour protéger vos données
                            </p>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <div class="w-6 h-6 bg-primary rounded-full flex items-center justify-center text-white">
                                    <i class="fas fa-check text-xs"></i>
                                </div>
                            </div>
                            <p class="ml-3 text-gray-600">
                                <span class="font-semibold">Accessibilité</span> - Disponible sur tous vos appareils
                            </p>
                        </div>
                    </div>
                </div>
                <div class="order-1 lg:order-2">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1521791055366-0d553872125f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2069&q=80"
                             alt="Équipe BiFi"
                             class="rounded-xl shadow-xl w-full h-auto">
                        <div class="absolute -bottom-6 -left-6 bg-primary p-6 rounded-xl shadow-lg z-10">
                            <h3 class="text-white text-2xl font-bold">5 ans</h3>
                            <p class="text-white">d'expérience</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="p-6">
                    <div class="text-4xl font-bold mb-2">10K+</div>
                    <p class="text-white text-opacity-90">Utilisateurs</p>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold mb-2">500K+</div>
                    <p class="text-white text-opacity-90">Transactions</p>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold mb-2">99.9%</div>
                    <p class="text-white text-opacity-90">Disponibilité</p>
                </div>
                <div class="p-6">
                    <div class="text-4xl font-bold mb-2">24/7</div>
                    <p class="text-white text-opacity-90">Support</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Ce qu'ils disent de nous</h2>
                <div class="w-20 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez les témoignages de nos clients satisfaits
    </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Testimonial 1 -->
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "BiFi a révolutionné la façon dont je gère mes paiements. Tout est si simple et rapide maintenant !"
                    </p>
                    <div class="flex items-center">
                        <img src="https://www.soinsdebene.com/wp-content/uploads/2016/04/visage-femme-noire-soinsdebene.jpg"
                             alt="Client satisfait"
                             class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Aïssatou Diop</h4>
                            <p class="text-gray-500 text-sm">Transitaire</p>
                        </div>
    </div>
                </div>

                <!-- Testimonial 2 -->
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                          <i class="fas fa-star"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "La sécurité des transactions était ma principale préoccupation. Avec BiFi, je suis totalement rassuré."
                    </p>
                    <div class="flex items-center">
                        <img src="https://img.freepik.com/photos-gratuite/homme-affaires-afro-americain-costume-gris-studio-portrait_53876-102940.jpg?semt=ais_hybrid&w=740"
                             alt="Client satisfait"
                             class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Mamadou Ndiaye</h4>
                            <p class="text-gray-500 text-sm">Commerçant</p>
                        </div>
    </div>
                </div>

                <!-- Testimonial 3 -->
                <div class="bg-white p-8 rounded-xl shadow-lg">
                    <div class="flex items-center mb-4">
                        <div class="text-yellow-400 text-xl">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star-half-alt"></i>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-6 italic">
                        "Le support client est exceptionnel. Ils répondent rapidement et résolvent tous mes problèmes."
                    </p>
                    <div class="flex items-center">
                        <img src="https://france-fraternites.org/wp-content/uploads/2017/06/halima-aden-article-ff.jpg"
                             alt="Client satisfait"
                             class="w-12 h-12 rounded-full object-cover mr-4">
                        <div>
                            <h4 class="font-bold text-gray-900">Fatou Fall</h4>
                            <p class="text-gray-500 text-sm">Importatrice</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-primary text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6">Prêt à simplifier vos paiements ?</h2>
            <p class="text-xl mb-8 max-w-2xl mx-auto">
                Rejoignez des milliers d'utilisateurs qui font confiance à BiFi pour leurs transactions quotidiennes.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="#" class="bg-white text-primary px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors">
                    Commencer maintenant
                </a>
                <a href="#contact" class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                    Nous contacter
                </a>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Contactez-nous</h2>
                <div class="w-20 h-1 bg-primary mx-auto mb-6"></div>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Notre équipe est là pour répondre à toutes vos questions
    </p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                <div>
                    <div class="space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-lg text-primary">
                                <i class="fas fa-map-marker-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Adresse</h3>
                                <p class="text-gray-600">Mermoz, Dakar, Sénégal</p>
    </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-lg text-primary">
                                <i class="fas fa-phone-alt text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Téléphone</h3>
                                <p class="text-gray-600">+221 75 750 67 67</p>
    </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-lg text-primary">
                                <i class="fas fa-envelope text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Email</h3>
                                <p class="text-gray-600">diarrabicons@gmail.com</p>
    </div>
                        </div>

                        <div class="flex items-start">
                            <div class="flex-shrink-0 bg-primary bg-opacity-10 p-3 rounded-lg text-primary">
                                <i class="fas fa-clock text-xl"></i>
                            </div>
                            <div class="ml-4">
                                <h3 class="text-lg font-bold text-gray-900">Horaires</h3>
                                <p class="text-gray-600">Lundi - Vendredi : 8h00 - 18h00</p>
                            </div>
    </div>
                    </div>

                    <div class="mt-8">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">Suivez-nous</h3>
                        <div class="flex space-x-4">
                            <a href="#" class="bg-gray-100 p-3 rounded-full text-gray-700 hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="bg-gray-100 p-3 rounded-full text-gray-700 hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="bg-gray-100 p-3 rounded-full text-gray-700 hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="bg-gray-100 p-3 rounded-full text-gray-700 hover:bg-primary hover:text-white transition-colors">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        </div>
    </div>
                </div>

                <div>
                    <form class="space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="first-name" class="block text-sm font-medium text-gray-700 mb-1">Prénom</label>
                                <input type="text" id="first-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            </div>
                            <div>
                                <label for="last-name" class="block text-sm font-medium text-gray-700 mb-1">Nom</label>
                                <input type="text" id="last-name" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="email" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="subject" class="block text-sm font-medium text-gray-700 mb-1">Sujet</label>
                            <input type="text" id="subject" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary">
                        </div>
                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message</label>
                            <textarea id="message" rows="4" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-primary focus:border-primary"></textarea>
                        </div>
                        <div>
                            <button type="submit" class="w-full bg-primary text-white px-6 py-4 rounded-lg font-semibold hover:bg-opacity-90 transition-colors">
                                Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="bitcoin-logo">₿</div>
                        <span class="text-white text-2xl font-bold">₿ifi</span>
                    </div>
                    <p class="text-gray-400">
                        La solution de paiement innovante pour faciliter vos transactions quotidiennes.
    </p>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Liens rapides</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Accueil</a></li>
                        <li><a href="#services" class="text-gray-400 hover:text-white transition-colors">Services</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-white transition-colors">À propos</a></li>
                        <li><a href="#contact" class="text-gray-400 hover:text-white transition-colors">Contact</a></li>
    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Services</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Paiements</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Transferts</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Factures</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-white transition-colors">Sécurité</a></li>
    </ul>
                </div>

                <div>
                    <h3 class="text-lg font-semibold mb-4">Newsletter</h3>
                    <p class="text-gray-400 mb-4">
                        Abonnez-vous pour recevoir nos dernières actualités.
                    </p>
                    <form class="flex">
                        <input type="email" placeholder="Votre email" class="px-4 py-2 rounded-l-lg focus:outline-none text-gray-900 w-full">
                        <button type="submit" class="bg-primary px-4 py-2 rounded-r-lg">
                            <i class="fas fa-paper-plane"></i>
                        </button>
                    </form>
    </div>
            </div>

            <div class="border-t border-gray-800 mt-12 pt-8 flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400">
                    &copy; 2023 BiFi by Ndeye Yande Toure. Tous droits réservés.
                </p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-white transition-colors">
                        <i class="fab fa-linkedin-in"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button').addEventListener('click', function() {
            const menu = document.getElementById('mobile-menu');
    menu.classList.toggle('hidden');
        });

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll');
            } else {
                navbar.classList.remove('navbar-scroll');
    }
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
or.addEventListener('click', function(e) {
                e.preventDefault();

const targetId = this.getAttribute('href');
                if (targetId === '#') return;

                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
    behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const mobileMenu = document.getElementById('mobile-menu');
                    mobileMenu.classList.add('hidden');
                }
    });
        });

        // Initialize navbar state
        document.addEventListener('DOMContentLoaded', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('navbar-scroll');
            }
        });
    </s
cript>
</body>
</html>
