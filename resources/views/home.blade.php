@extends('layouts.app')

@section('title', 'Accueil - Faites vous remarquer !')

@section('content')
        <style>
    .gradient-hero {
        background: linear-gradient(135deg, #1e40af 0%, #3730a3 50%, #581c87 100%);
        position: relative;
        overflow: hidden;
    }
    
    .gradient-hero::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="60" height="60" viewBox="0 0 60 60" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><g fill="%23ffffff" fill-opacity="0.05"><circle cx="30" cy="30" r="4"/></g></svg>');
        opacity: 0.3;
    }
    
    .text-gradient {
        background: linear-gradient(135deg, #3b82f6, #8b5cf6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    .hero-glow {
        box-shadow: 0 0 50px rgba(250, 204, 21, 0.5);
        animation: glow-pulse 2s ease-in-out infinite alternate;
    }
    
    @keyframes glow-pulse {
        from {
            box-shadow: 0 0 20px rgba(250, 204, 21, 0.5);
        }
        to {
            box-shadow: 0 0 40px rgba(250, 204, 21, 0.8);
        }
    }
    
    .floating-icon {
        animation: float 6s ease-in-out infinite;
    }
    
    .floating-icon:nth-child(2) {
        animation-delay: -2s;
    }
    
    .floating-icon:nth-child(3) {
        animation-delay: -4s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
            opacity: 0.1;
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
            opacity: 0.2;
        }
    }
    
    .hero-card {
        backdrop-filter: blur(10px);
        background: rgba(255, 255, 255, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .sparkle {
        position: absolute;
        width: 4px;
        height: 4px;
        background: #fbbf24;
        border-radius: 50%;
        animation: sparkle 3s linear infinite;
    }
    
    @keyframes sparkle {
        0%, 100% {
            opacity: 0;
            transform: scale(0);
        }
        50% {
            opacity: 1;
            transform: scale(1);
        }
    }
    
    /* Configuration Tailwind pour les classes personnalisées */
    .border-3 {
        border-width: 3px;
    }
        </style>

<!-- Hero Section -->
<section class="gradient-hero text-white py-20 relative overflow-hidden min-h-screen flex items-center">
    <!-- Floating decorative elements -->
    <div class="floating-icon absolute top-20 left-10">
        <i class="fas fa-file-invoice text-white text-6xl"></i>
    </div>
    <div class="floating-icon absolute bottom-20 right-10">
        <i class="fas fa-mobile-alt text-white text-6xl"></i>
    </div>
    <div class="floating-icon absolute top-1/3 right-1/4">
        <i class="fas fa-credit-card text-white text-4xl"></i>
    </div>
    <div class="floating-icon absolute bottom-1/3 left-1/4">
        <i class="fas fa-shield-alt text-white text-4xl"></i>
    </div>
    
    <!-- Sparkles -->
    <div class="sparkle" style="top: 15%; left: 20%; animation-delay: 0s;"></div>
    <div class="sparkle" style="top: 25%; right: 25%; animation-delay: 1s;"></div>
    <div class="sparkle" style="bottom: 30%; left: 30%; animation-delay: 2s;"></div>
    <div class="sparkle" style="bottom: 20%; right: 15%; animation-delay: 0.5s;"></div>
    <div class="sparkle" style="top: 40%; left: 60%; animation-delay: 1.5s;"></div>
                
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
        <!-- Logo/Brand Enhanced -->
        <div class="mb-8">
            <div class="inline-flex items-center space-x-4 hero-card px-8 py-4 rounded-2xl">
                <img src="{{ asset('images/logobi.png') }}" alt="B!consulting Logo" class="h-16 w-auto">
                <div>
                    <span class="text-4xl font-bold text-white">Bifi</span>
                    <p class="text-yellow-300 text-sm font-medium">by B!consulting</p>
                </div>
            </div>
        </div>
        
        <h1 class="text-6xl md:text-8xl font-bold mb-8 leading-tight">
            <span class="block mb-4">Faites vous</span>
            <span class="text-yellow-300 text-stroke">remarquer !</span>
        </h1>
        
        <div class="hero-card px-8 py-6 rounded-2xl mb-8 max-w-4xl mx-auto">
            <blockquote class="text-xl md:text-2xl italic font-light">
                "C'est dans les moments de décision que votre destinée se dessine."
                <br>
                <span class="text-yellow-300 text-lg font-medium">- Tony Robbins</span>
            </blockquote>
        </div>
        
        <div class="mb-12">
            <a href="{{ route('bills.create') }}" class="inline-block bg-yellow-400 text-gray-900 px-12 py-6 rounded-2xl text-xl font-bold hover:bg-yellow-300 transition-all duration-300 transform hover:scale-105 hero-glow uppercase tracking-wide">
                <i class="fas fa-bolt mr-3"></i>PAYER MA FACTURE
                <i class="fas fa-arrow-right ml-3"></i>
            </a>
        </div>
        
        <!-- Enhanced Contact Info -->
        <div class="grid md:grid-cols-3 gap-8 text-lg max-w-4xl mx-auto">
            <div class="hero-card px-6 py-4 rounded-xl flex items-center justify-center space-x-3 transform hover:scale-105 transition-all duration-300">
                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-phone text-xl text-gray-900"></i>
                </div>
                <div class="text-left">
                    <p class="text-yellow-300 text-sm font-medium">Appelez-nous</p>
                    <span class="font-semibold">+221 78 705 67 67</span>
                </div>
            </div>
            <div class="hero-card px-6 py-4 rounded-xl flex items-center justify-center space-x-3 transform hover:scale-105 transition-all duration-300">
                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-envelope text-xl text-gray-900"></i>
                </div>
                <div class="text-left">
                    <p class="text-yellow-300 text-sm font-medium">Écrivez-nous</p>
                    <span class="font-semibold">diarrabicons@gmail.com</span>
                </div>
            </div>
            <div class="hero-card px-6 py-4 rounded-xl flex items-center justify-center space-x-3 transform hover:scale-105 transition-all duration-300">
                <div class="w-12 h-12 bg-yellow-400 rounded-full flex items-center justify-center">
                    <i class="fas fa-map-marker-alt text-xl text-gray-900"></i>
                </div>
                <div class="text-left">
                    <p class="text-yellow-300 text-sm font-medium">Visitez-nous</p>
                    <span class="font-semibold">Mermoz, Dakar</span>
                </div>
            </div>
        </div>
        
        <!-- Scroll indicator -->
        <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
                <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
            </div>
        </div>
    </div>
</section>

        <!-- Section avec images -->
        <div class="py-20 bg-gradient-to-r from-gray-50 to-blue-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Une expérience moderne et sécurisée</h2>
                    <p class="text-xl text-gray-600">Découvrez comment Bifi simplifie vos paiements</p>
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
                        <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80" 
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
                    <h2 class="text-4xl font-bold text-gray-900 mb-4">Pourquoi choisir Bifi ?</h2>
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
                    Rejoignez des milliers d'utilisateurs qui font confiance à Bifi pour leurs transactions quotidiennes.
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
                            Notre équipe est là pour vous accompagner dans l'utilisation de Bifi.
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
                                    <h3 class="font-semibold">Email</h3>
                                    <p class="text-gray-300">diarrabicons@gmail.com</p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center mr-4">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
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
                        <h3 class="text-xl font-semibold mb-6">Démarrer avec Bifi</h3>
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

        <!-- Footer personnalisé pour la page d'accueil -->
        <footer class="bg-gray-800 text-gray-300 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <p>&copy; {{ date('Y') }} Bifi by B!consulting. Tous droits réservés.</p>
            </div>
        </footer>

        <!-- JavaScript pour les effets d'animation -->
        <script>
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

@endsection 