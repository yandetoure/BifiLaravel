<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Bill Box - Simplifiez le paiement de vos factures au Sénégal</title>
        
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
        
        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body {
                font-family: 'Inter', sans-serif;
            }
            
            .hero-bg {
                background: linear-gradient(135deg, rgba(37, 99, 235, 0.85) 0%, rgba(29, 78, 216, 0.9) 50%, rgba(30, 64, 175, 0.85) 100%), 
                           url('https://images.unsplash.com/photo-1559526324-4b87b5e36e44?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
                background-size: cover;
                background-position: center;
                background-attachment: fixed;
                min-height: 100vh;
                position: relative;
            }
            
            .hero-bg::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background: rgba(30, 64, 175, 0.3);
                z-index: 1;
            }
            
            .hero-content {
                position: relative;
                z-index: 2;
            }
            
            .navbar-transparent {
                background: transparent;
                transition: all 0.3s ease;
                backdrop-filter: blur(0px);
            }
            
            .navbar-solid {
                background: rgba(255, 255, 255, 0.95);
                backdrop-filter: blur(10px);
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            }
            
            .floating-card {
                animation: float 6s ease-in-out infinite;
                transition: transform 0.3s ease;
            }
            
            .floating-card:hover {
                transform: translateY(-10px);
            }
            
            @keyframes float {
                0% { transform: translateY(0px); }
                50% { transform: translateY(-20px); }
                100% { transform: translateY(0px); }
            }
            
            .feature-image {
                transition: transform 0.3s ease;
            }
            
            .feature-image:hover {
                transform: scale(1.05);
            }
            
            .gradient-text {
                background: linear-gradient(135deg, #3b82f6, #8b5cf6);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
            }
        </style>
    </head>
    <body class="antialiased">
        <!-- Navbar fixe et transparente -->
        <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 navbar-transparent">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="text-2xl font-bold text-white" id="logo">
                            <span class="gradient-text">Bill Box</span>
                        </a>
                    </div>
                    
                    <!-- Navigation -->
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="#features" class="text-white hover:text-blue-200 transition-colors font-medium" id="nav-features">
                            Fonctionnalités
                        </a>
                        <a href="{{ route('about') }}" class="text-white hover:text-blue-200 transition-colors font-medium" id="nav-about">
                            À propos
                        </a>
                        <a href="#contact" class="text-white hover:text-blue-200 transition-colors font-medium" id="nav-contact">
                            Contact
                        </a>
                    </div>
                    
                    <!-- Auth buttons -->
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" 
                               class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" 
                               class="text-white hover:text-blue-200 transition-colors font-medium" id="nav-login">
                                Connexion
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" 
                                   class="bg-white text-blue-600 px-6 py-2 rounded-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg">
                                    S'inscrire
                                </a>
                            @endif
                        @endauth
                        
                        <!-- Menu mobile -->
                        <button class="md:hidden text-white" onclick="toggleMobileMenu()">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Menu mobile -->
                <div id="mobile-menu" class="hidden md:hidden mt-4 pb-4">
                    <div class="flex flex-col space-y-2">
                        <a href="#features" class="text-white hover:text-blue-200 px-3 py-2 rounded-md font-medium">
                            Fonctionnalités
                        </a>
                        <a href="{{ route('about') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md font-medium">
                            À propos
                        </a>
                        <a href="#contact" class="text-white hover:text-blue-200 px-3 py-2 rounded-md font-medium">
                            Contact
                        </a>
                        @guest
                            <a href="{{ route('login') }}" class="text-white hover:text-blue-200 px-3 py-2 rounded-md font-medium">
                                Connexion
                            </a>
                            <a href="{{ route('register') }}" class="bg-white text-blue-600 px-3 py-2 rounded-md font-medium text-center">
                                S'inscrire
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section avec bannière -->
        <div class="hero-bg flex items-center justify-center">
            <div class="hero-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center text-white">
                <div class="animate-fade-in">
                    <h1 class="text-5xl md:text-7xl font-bold mb-6 leading-tight">
                        Payez vos factures<br>
                        <span class="text-blue-200">en toute simplicité</span>
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 max-w-3xl mx-auto text-blue-100">
                        Bill Box révolutionne le paiement de factures au Sénégal. Une plateforme unique pour toutes vos transactions.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('bills.create') }}" 
                           class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors shadow-lg hover:shadow-xl transform hover:scale-105">
                            <i class="fas fa-credit-card mr-2"></i>Payer une facture
                        </a>
                        <a href="{{ route('about') }}" 
                           class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                            <i class="fas fa-info-circle mr-2"></i>En savoir plus
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Section Moyens de Paiement -->
        <section id="payment-methods" class="py-20 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">
                        Nos Moyens de Paiement
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Choisissez le mode de paiement qui vous convient le mieux parmi nos solutions sécurisées
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Carte Bancaire -->
                    <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-credit-card text-2xl text-blue-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Carte Bancaire</h3>
                        <p class="text-gray-600 mb-4">
                            Paiement sécurisé par carte bancaire avec cryptage SSL et protection 3D Secure
                        </p>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Visa, Mastercard, UBA
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Transaction instantanée
                            </li>
                        </ul>
                    </div>

                    <!-- Mobile Money -->
                    <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-mobile-alt text-2xl text-orange-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Mobile Money</h3>
                        <p class="text-gray-600 mb-4">
                            Payez facilement avec votre mobile money, disponible 24/7
                        </p>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Wave, Orange Money, Free Money
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Confirmation SMS
                            </li>
                        </ul>
                    </div>

                    <!-- Espèces -->
                    <div class="bg-white rounded-xl shadow-lg p-8 transform hover:scale-105 transition-transform duration-300">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-6">
                            <i class="fas fa-money-bill-wave text-2xl text-green-600"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Paiement en Espèces</h3>
                        <p class="text-gray-600 mb-4">
                            Payez en espèces dans nos points de service agréés
                        </p>
                        <ul class="space-y-2 text-gray-600">
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Réseau d'agents certifiés
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-check text-green-500 mr-2"></i>
                                Reçu immédiat
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section avec images -->
        <div class="py-20 bg-gradient-to-r from-gray-50 to-blue-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Une expérience moderne et sécurisée</h2>
                    <p class="text-xl text-gray-600">Découvrez comment Bill Box simplifie vos paiements</p>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
                    <div class="feature-image overflow-hidden rounded-2xl shadow-xl">
                        <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             alt="Interface moderne" 
                             class="w-full h-80 object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                    <div>
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Interface intuitive</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Notre interface moderne et épurée vous permet de payer vos factures en quelques clics seulement.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Navigation simplifiée</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Design responsive</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Accessibilité optimale</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center mt-20">
                    <div class="order-2 lg:order-1">
                        <h3 class="text-3xl font-bold text-gray-900 mb-6">Sécurité renforcée</h3>
                        <p class="text-lg text-gray-600 mb-6">
                            Toutes vos transactions sont protégées par les dernières technologies de cryptage et de sécurité.
                        </p>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Cryptage SSL 256 bits</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Authentification multi-facteurs</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center mr-3">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 1.944A11.954 11.954 0 012.166 5C2.056 5.649 2 6.319 2 7c0 5.225 3.34 9.67 8 11.317C14.66 16.67 18 12.225 18 7c0-.682-.057-1.35-.166-2.001A11.954 11.954 0 0110 1.944zM11.166 4.12C9.67 4.542 8.367 5.406 7.414 6.578l2.586 2.586 4-4zm-1.332 7.88l-4-4a7.025 7.025 0 00-.572 2.284C5.262 12.542 7.593 14 10 14a4.977 4.977 0 00.834-.069z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <span class="text-gray-700">Protection anti-fraude</span>
                            </div>
                        </div>
                    </div>
                    <div class="order-1 lg:order-2 feature-image overflow-hidden rounded-2xl shadow-xl">
                        <img src="https://images.unsplash.com/photo-1563013544-824ae1b704d3?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
                             alt="Sécurité" 
                             class="w-full h-80 object-cover transition-transform duration-300 hover:scale-110">
                    </div>
                </div>
            </div>
        </div>

        <!-- Fonctionnalités Section -->
        <div id="features" class="py-20 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Pourquoi choisir Bill Box ?</h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Notre plateforme vous offre une expérience de paiement unique, sécurisée et accessible.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    <div class="floating-card bg-white p-8 rounded-2xl shadow-lg text-center border border-gray-100">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Simplicité</h3>
                        <p class="text-gray-600">Interface intuitive et facile à utiliser pour tous</p>
                    </div>

                    <div class="floating-card bg-white p-8 rounded-2xl shadow-lg text-center border border-gray-100" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.031 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Sécurité</h3>
                        <p class="text-gray-600">Transactions protégées par cryptage avancé</p>
                    </div>

                    <div class="floating-card bg-white p-8 rounded-2xl shadow-lg text-center border border-gray-100" style="animation-delay: 0.4s;">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Temps réel</h3>
                        <p class="text-gray-600">Suivi instantané de vos paiements</p>
                    </div>

                    <div class="floating-card bg-white p-8 rounded-2xl shadow-lg text-center border border-gray-100" style="animation-delay: 0.6s;">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-900 mb-2">Support 24/7</h3>
                        <p class="text-gray-600">Assistance disponible à tout moment</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Services supportés -->
        <div class="py-20 bg-gradient-to-r from-blue-50 to-purple-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Méthodes de paiement acceptées</h2>
                    <p class="text-xl text-gray-600">Payez avec votre méthode préférée</p>
                </div>

                <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                    <div class="text-center p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Wave</h3>
                        <p class="text-sm text-gray-600">Paiement mobile</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Orange Money</h3>
                        <p class="text-sm text-gray-600">Mobile banking</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                        <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Wizall</h3>
                        <p class="text-sm text-gray-600">Carte bancaire</p>
                    </div>
                    <div class="text-center p-6 bg-white rounded-lg shadow-lg hover:shadow-xl transition-shadow border border-gray-100">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-2">Espèces</h3>
                        <p class="text-sm text-gray-600">Paiement cash</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Section -->
        <div class="py-20 bg-gradient-to-r from-blue-600 to-purple-600 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="text-4xl font-bold mb-6">Prêt à simplifier vos paiements ?</h2>
                <p class="text-xl mb-8 max-w-2xl mx-auto">
                    Rejoignez des milliers d'utilisateurs qui font confiance à Bill Box pour leurs transactions quotidiennes.
                </p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <a href="{{ route('bills.create') }}" 
                       class="bg-white text-blue-600 px-8 py-4 rounded-lg text-lg font-semibold hover:bg-gray-100 transition-colors inline-block shadow-lg">
                        Commencer maintenant
                    </a>
                    <a href="{{ route('about') }}" 
                       class="border-2 border-white text-white px-8 py-4 rounded-lg text-lg font-semibold hover:bg-white hover:text-blue-600 transition-colors">
                        En savoir plus
                    </a>
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div id="contact" class="py-20 bg-gray-900 text-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <div>
                        <h2 class="text-3xl font-bold mb-6">Contactez-nous</h2>
                        <p class="text-gray-300 mb-8">
                            Notre équipe est là pour vous accompagner dans l'utilisation de Bill Box.
                        </p>
                        
                        <div class="space-y-4">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Adresse</h3>
                                    <p class="text-gray-300">Mermoz Dakar, Sénégal<br>BP 15350 DAKAR-FANN</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Téléphone</h3>
                                    <p class="text-gray-300">+221 78 705 67 67</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path>
                                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold">Horaires</h3>
                                    <p class="text-gray-300">Lundi - Vendredi : 9h00 - 17h00</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-gray-800 p-8 rounded-lg">
                        <h3 class="text-xl font-semibold mb-6">Démarrer avec Bill Box</h3>
                        <div class="space-y-4">
                            <a href="{{ route('bills.create') }}" 
                               class="block w-full bg-blue-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors">
                                Payer une facture
                            </a>
                            <a href="{{ route('about') }}" 
                               class="block w-full border border-gray-600 text-white text-center py-3 rounded-lg font-semibold hover:bg-gray-700 transition-colors">
                                En savoir plus
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <footer class="bg-gray-800 text-gray-300 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; {{ date('Y') }} Bill Box by B!consulting. Tous droits réservés.</p>
            </div>
        </footer>

        <!-- JavaScript pour la navbar et effets -->
        <script>
            window.addEventListener('scroll', function() {
                const navbar = document.getElementById('navbar');
                const logo = document.getElementById('logo');
                const navLinks = document.querySelectorAll('#nav-features, #nav-about, #nav-contact, #nav-login');
                
                if (window.scrollY > 50) {
                    navbar.classList.remove('navbar-transparent');
                    navbar.classList.add('navbar-solid');
                    logo.classList.remove('text-white');
                    logo.classList.add('text-gray-900');
                    navLinks.forEach(link => {
                        link.classList.remove('text-white', 'hover:text-blue-200');
                        link.classList.add('text-gray-700', 'hover:text-blue-600');
                    });
                } else {
                    navbar.classList.remove('navbar-solid');
                    navbar.classList.add('navbar-transparent');
                    logo.classList.remove('text-gray-900');
                    logo.classList.add('text-white');
                    navLinks.forEach(link => {
                        link.classList.remove('text-gray-700', 'hover:text-blue-600');
                        link.classList.add('text-white', 'hover:text-blue-200');
                    });
                }
            });

            // Smooth scrolling pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    e.preventDefault();
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });
            
            // Toggle mobile menu
            function toggleMobileMenu() {
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.toggle('hidden');
            }
            
            // Animation d'apparition au scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };
            
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.opacity = '1';
                        entry.target.style.transform = 'translateY(0)';
                    }
                });
            }, observerOptions);
            
            document.addEventListener('DOMContentLoaded', () => {
                const animatedElements = document.querySelectorAll('.floating-card');
                animatedElements.forEach(el => {
                    el.style.opacity = '0';
                    el.style.transform = 'translateY(20px)';
                    el.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
                    observer.observe(el);
                });
            });
        </script>
    </body>
</html>
