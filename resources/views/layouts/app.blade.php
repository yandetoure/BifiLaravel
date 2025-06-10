<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Bifi by B!consulting') - Paiement de Factures</title>

    <!-- Tailwind CSS CDN pour garantir l'application des styles -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    },
                    animation: {
                        'fade-in': 'fadeIn 0.5s ease-in-out',
                        'slide-up': 'slideUp 0.3s ease-out',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                        'floating': 'floating 3s ease-in-out infinite',
                        'pulse-glow': 'pulse-glow 2s infinite'
                    },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        slideUp: {
                            '0%': { transform: 'translateY(10px)', opacity: '0' },
                            '100%': { transform: 'translateY(0)', opacity: '1' },
                        },
                        floating: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        'pulse-glow': {
                            '0%, 100%': { boxShadow: '0 0 20px rgba(59, 130, 246, 0.5)' },
                            '50%': { boxShadow: '0 0 40px rgba(59, 130, 246, 0.8)' }
                        }
                    }
                }
            }
        }
    </script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <!-- Vite Assets - Backup -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
    
    <style>
        .text-gradient {
            background: linear-gradient(135deg, #3b82f6, #8b5cf6);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        /* Configuration Tailwind pour les classes personnalisées */
        .border-3 {
            border-width: 3px;
        }
    </style>
</head>
<body class="font-sans bg-gray-50 antialiased">
    <div id="app" class="min-h-screen flex flex-col">
        <!-- Navigation -->
        <nav class="bg-white shadow-lg sticky top-0 z-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-3">
                            <img src="{{ asset('images/logobi.png') }}" alt="B!consulting Logo" class="h-12 w-auto">
                            <div>
                                <span class="text-2xl font-bold text-gradient">Bifi</span>
                                <p class="text-xs text-gray-500">by B!consulting</p>
                            </div>
                        </a>
                    </div>
                
                    <div class="hidden md:flex items-center space-x-8">
                        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            <i class="fas fa-home mr-2"></i>Accueil
                        </a>
                        <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                            <i class="fas fa-info-circle mr-2"></i>À propos
                        </a>
                        @auth
                            <!-- Communication - Pour agents, superviseurs et admins seulement -->
                            @if(Auth::user()->role !== 'client')
                                <div class="relative">
                                    <button onclick="toggleCommunicationDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                        <i class="fas fa-comments mr-2"></i>
                                        <span>Communication</span>
                                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                    </button>
                                    <div id="communicationDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('chat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-comment mr-2"></i>Chat Équipe
                                        </a>
                                        <a href="{{ route('client-chat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-headset mr-2"></i>Messages Clients
                                        </a>
                                    </div>
                                </div>
                            @else
                                <!-- Menu Client -->
                                <div class="relative">
                                    <button onclick="toggleClientDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                        <i class="fas fa-user mr-2"></i>
                                        <span>Mon Espace</span>
                                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                    </button>
                                    <div id="clientDropdown" class="hidden absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('my.bills') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-invoice mr-2"></i>Mes Factures
                                        </a>
                                        <a href="{{ route('my.receipts') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-receipt mr-2"></i>Mes Reçus
                                        </a>
                                        <a href="{{ route('client-chat.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-life-ring mr-2"></i>Support Client
                                        </a>
                                    </div>
                                </div>
                            @endif
                            
                            @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                                <!-- Administration -->
                                <div class="relative">
                                    <button onclick="toggleAdminDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                        <i class="fas fa-cog mr-2"></i>
                                        <span>Administration</span>
                                        <i class="fas fa-chevron-down ml-1 text-xs"></i>
                                    </button>
                                    <div id="adminDropdown" class="hidden absolute left-0 mt-2 w-56 bg-white rounded-md shadow-lg py-1 z-50">
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('deposits.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-money-bill-transfer mr-2"></i>Gestion Versements
                                        </a>
                                        <a href="{{ route('admin.bills.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-file-invoice mr-2"></i>Gestion Factures
                                        </a>
                                        <a href="{{ route('admin.payments.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-credit-card mr-2"></i>Gestion Paiements
                                        </a>
                                        @if(Auth::user()->role === 'admin')
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-users mr-2"></i>Gestion Utilisateurs
                                        </a>
                                        @endif
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.balances.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-wallet mr-2"></i>Gestion Balances
                                        </a>
                                        <a href="{{ route('admin.notifications.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-bell mr-2"></i>Notifications
                                        </a>
                                        <a href="{{ route('admin.mail.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-envelope mr-2"></i>Mailing
                                        </a>
                                        <div class="border-t border-gray-100"></div>
                                        <a href="{{ route('admin.reports.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-chart-bar mr-2"></i>Rapports
                                        </a>
                                    </div>
                                </div>
                            @endif
                        @endauth
                    </div>
                    
                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                                </a>
                            <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                <i class="fas fa-user-plus mr-2"></i>S'inscrire
                                </a>
                        @else
                            <!-- Cloche de notification pour tous les utilisateurs connectés -->
                            <div class="relative">
                                <button onclick="toggleNotificationsDropdown()" class="relative p-2 text-gray-700 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded-full transition duration-150 ease-in-out">
                                    <i class="fas fa-bell text-xl"></i>
                                    <!-- Badge de notification (optionnel) -->
                                    <span id="notification-badge" class="hidden absolute top-0 right-0 h-2 w-2 bg-red-500 rounded-full"></span>
                                </button>
                                <div id="notificationsDropdown" class="hidden absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-2 z-50 border border-gray-200">
                                    <div class="px-4 py-2 border-b border-gray-100">
                                        <h3 class="text-sm font-semibold text-gray-900">Notifications</h3>
                                    </div>
                                    <div class="max-h-64 overflow-y-auto">
                                        @php
                                            $user = Auth::user();
                                            $notifications = [];
                                            
                                            // Notifications pour solde bas Wizall (tous les rôles)
                                            if($user->role === 'agent' || $user->role === 'supervisor' || $user->role === 'admin') {
                                                $todayBalance = \App\Models\Balance::getTodayBalance();
                                                if($todayBalance && $todayBalance->wizall_current_balance < 50000) {
                                                    $notifications[] = [
                                                        'type' => 'warning',
                                                        'icon' => 'fas fa-exclamation-triangle',
                                                        'title' => 'Solde Wizall bas',
                                                        'message' => 'Solde Wizall actuel: ' . number_format($todayBalance->wizall_current_balance, 0) . ' FCFA',
                                                        'time' => 'Maintenant'
                                                    ];
                                                }
                                            }
                                            
                                            // Notifications de fin de journée pour agents
                                            if($user->role === 'agent') {
                                                $hour = date('H');
                                                if($hour >= 17) { // Après 17h
                                                    $notifications[] = [
                                                        'type' => 'info',
                                                        'icon' => 'fas fa-clock',
                                                        'title' => 'Fin de journée',
                                                        'message' => 'N\'oubliez pas de rendre la caisse',
                                                        'time' => 'Maintenant'
                                                    ];
                                                }
                                            }
                                            
                                            // Messages clients non lus (pour agents, superviseurs, admins)
                                            if($user->role !== 'client') {
                                                $unreadClientMessages = \App\Models\ClientMessage::where('is_read', false)->count();
                                                if($unreadClientMessages > 0) {
                                                    $notifications[] = [
                                                        'type' => 'info',
                                                        'icon' => 'fas fa-envelope',
                                                        'title' => 'Messages clients',
                                                        'message' => $unreadClientMessages . ' message(s) client(s) non lu(s)',
                                                        'time' => 'Nouveau'
                                                    ];
                                                }
                                            }
                                        @endphp
                                        
                                        @if(count($notifications) > 0)
                                            @foreach($notifications as $notification)
                                                <div class="px-4 py-3 hover:bg-gray-50 border-b border-gray-100 last:border-b-0">
                                                    <div class="flex items-start space-x-3">
                                                        <div class="flex-shrink-0">
                                                            <i class="{{ $notification['icon'] }} {{ $notification['type'] === 'warning' ? 'text-yellow-500' : 'text-blue-500' }}"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">{{ $notification['title'] }}</p>
                                                            <p class="text-sm text-gray-600">{{ $notification['message'] }}</p>
                                                            <p class="text-xs text-gray-400 mt-1">{{ $notification['time'] }}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        @else
                                            <div class="px-4 py-6 text-center">
                                                <i class="fas fa-bell-slash text-gray-300 text-2xl mb-2"></i>
                                                <p class="text-sm text-gray-500">Aucune notification</p>
                                            </div>
                                        @endif
                                    </div>
                                    @if(count($notifications) > 0)
                                        <div class="border-t border-gray-100 px-4 py-2">
                                            <button onclick="markAllNotificationsRead()" class="text-sm text-blue-600 hover:text-blue-700 font-medium w-full text-center">
                                                Marquer tout comme lu
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="relative">
                                <button onclick="toggleDropdown()" class="flex items-center space-x-2 text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-150 ease-in-out">
                                    <i class="fas fa-user-circle mr-2"></i>
                                    <span>{{ Auth::user()->name }}</span>
                                    <i class="fas fa-chevron-down ml-2 text-xs"></i>
                                </button>
                                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                    <a href="{{ route('user.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                                        </a>
                                    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                                        <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-cog mr-2"></i>Administration
                                        </a>
                                    @endif
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                            </button>
                                        </form>
                                </div>
                            </div>
                        @endguest
                    </div>
                    
                    <!-- Mobile menu button -->
                    <div class="md:hidden flex items-center">
                        <button onclick="toggleMobileMenu()" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600">
                            <i class="fas fa-bars text-xl"></i>
                        </button>
                    </div>
                </div>
            </div>
            
            <!-- Mobile menu -->
            <div id="mobileMenu" class="hidden md:hidden">
                <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3 bg-white border-t">
                    <a href="{{ route('home') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        <i class="fas fa-home mr-2"></i>Accueil
                    </a>
                    <a href="{{ route('about') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                        <i class="fas fa-info-circle mr-2"></i>À propos
                    </a>
                    @auth
                        <!-- Communication Mobile -->
                        @if(Auth::user()->role !== 'client')
                            <div class="border-t border-gray-200 pt-2">
                                <p class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Communication</p>
                                <a href="{{ route('chat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-comment mr-2"></i>Chat Équipe
                                </a>
                                <a href="{{ route('client-chat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-headset mr-2"></i>Messages Clients
                                </a>
                            </div>
                        @else
                            <!-- Menu Client Mobile -->
                            <div class="border-t border-gray-200 pt-2">
                                <p class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Mon Espace Client</p>
                                <a href="{{ route('my.bills') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-file-invoice mr-2"></i>Mes Factures
                                </a>
                                <a href="{{ route('my.receipts') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-receipt mr-2"></i>Mes Reçus
                                </a>
                                <a href="{{ route('client-chat.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-life-ring mr-2"></i>Support Client
                                </a>
                            </div>
                        @endif
                        
                        @if(Auth::user()->role === 'admin' || Auth::user()->role === 'supervisor')
                            <!-- Administration Mobile -->
                            <div class="border-t border-gray-200 pt-2">
                                <p class="px-3 py-1 text-xs font-semibold text-gray-500 uppercase tracking-wide">Administration</p>
                                <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-tachometer-alt mr-2"></i>Dashboard Admin
                                </a>
                                <a href="{{ route('deposits.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-money-bill-transfer mr-2"></i>Gestion Versements
                                </a>
                                <a href="{{ route('admin.bills.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-file-invoice mr-2"></i>Gestion Factures
                                </a>
                                <a href="{{ route('admin.payments.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-credit-card mr-2"></i>Gestion Paiements
                                </a>
                                @if(Auth::user()->role === 'admin')
                                <a href="{{ route('admin.users.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-users mr-2"></i>Gestion Utilisateurs
                                </a>
                                @endif
                                <a href="{{ route('admin.balances.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-wallet mr-2"></i>Gestion Balances
                                </a>
                                <a href="{{ route('admin.notifications.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-bell mr-2"></i>Notifications
                                </a>
                                <a href="{{ route('admin.mail.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-envelope mr-2"></i>Mailing
                                </a>
                                <a href="{{ route('admin.reports.index') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-chart-bar mr-2"></i>Rapports
                                </a>
                            </div>
                        @endif
                        
                        <div class="border-t border-gray-200 pt-2">
                            <a href="{{ route('user.dashboard') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                <i class="fas fa-tachometer-alt mr-2"></i>Tableau de bord
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="block w-full text-left px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                                    <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                                </button>
                            </form>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:text-blue-600 hover:bg-gray-50">
                            <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                        </a>
                        <a href="{{ route('register') }}" class="block px-3 py-2 rounded-md text-base font-medium bg-blue-600 text-white hover:bg-blue-700">
                            <i class="fas fa-user-plus mr-2"></i>S'inscrire
                        </a>
                    @endguest
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-6">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-check-circle text-green-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-circle text-red-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-red-700">{{ session('error') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('warning'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-exclamation-triangle text-yellow-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('info'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-6">
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">{{ session('info') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @yield('content')
        </main>

        <!-- Footer -->
        @unless(Request::is('/'))
        <footer class="bg-gray-800 text-white mt-auto">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div>
                        <div class="flex items-center space-x-3 mb-4">
                            <img src="{{ asset('images/logobi.png') }}" alt="B!consulting Logo" class="h-8 w-auto">
                            <div>
                                <span class="text-xl font-bold text-gradient">Bifi</span>
                                <p class="text-xs text-gray-400">by B!consulting</p>
                            </div>
                        </div>
                        <p class="text-gray-300 text-sm">Plateforme sécurisée pour le paiement de factures développée par B!consulting.</p>
                    </div>
                    
                    <div>
                        <h6 class="font-semibold mb-3">Contact</h6>
                        <div class="space-y-2 text-sm text-gray-300">
                            <p><i class="fas fa-phone mr-2"></i>+221 78 705 67 67</p>
                            <p><i class="fas fa-envelope mr-2"></i>diarrabicons@gmail.com</p>
                            <p><i class="fas fa-map-marker-alt mr-2"></i>Mermoz, Dakar Sénégal</p>
                        </div>
                    </div>
                    
                    <div>
                        <h6 class="font-semibold mb-3">Liens rapides</h6>
                        <div class="space-y-2 text-sm">
                            <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white">Accueil</a>
                            <a href="{{ route('bills.create') }}" class="block text-gray-300 hover:text-white">Payer une facture</a>
                            @auth
                                <a href="{{ route('user.dashboard') }}" class="block text-gray-300 hover:text-white">Tableau de bord</a>
                            @endauth
                        </div>
                    </div>
                </div>
                
                <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                    <p class="text-gray-400 text-sm">
                        &copy; {{ date('Y') }} <strong>B!CONSULTING</strong> - Tous droits réservés. 
                        <span class="text-blue-400">Faites vous remarquer !</span>
                    </p>
                </div>
            </div>
        </footer>
        @endunless
    </div>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('hidden');
            
            // Fermer les autres dropdowns
            const communicationDropdown = document.getElementById('communicationDropdown');
            const adminDropdown = document.getElementById('adminDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            if (communicationDropdown) communicationDropdown.classList.add('hidden');
            if (adminDropdown) adminDropdown.classList.add('hidden');
            if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        }
        
        function toggleCommunicationDropdown() {
            const dropdown = document.getElementById('communicationDropdown');
            dropdown.classList.toggle('hidden');
            
            // Fermer les autres dropdowns
            const userDropdown = document.getElementById('userDropdown');
            const adminDropdown = document.getElementById('adminDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            if (userDropdown) userDropdown.classList.add('hidden');
            if (adminDropdown) adminDropdown.classList.add('hidden');
            if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        }
        
        function toggleAdminDropdown() {
            const dropdown = document.getElementById('adminDropdown');
            dropdown.classList.toggle('hidden');
            
            // Fermer les autres dropdowns
            const userDropdown = document.getElementById('userDropdown');
            const communicationDropdown = document.getElementById('communicationDropdown');
            const clientDropdown = document.getElementById('clientDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            if (userDropdown) userDropdown.classList.add('hidden');
            if (communicationDropdown) communicationDropdown.classList.add('hidden');
            if (clientDropdown) clientDropdown.classList.add('hidden');
            if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        }
        
        function toggleClientDropdown() {
            const dropdown = document.getElementById('clientDropdown');
            dropdown.classList.toggle('hidden');
            
            // Fermer les autres dropdowns
            const userDropdown = document.getElementById('userDropdown');
            const communicationDropdown = document.getElementById('communicationDropdown');
            const adminDropdown = document.getElementById('adminDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            if (userDropdown) userDropdown.classList.add('hidden');
            if (communicationDropdown) communicationDropdown.classList.add('hidden');
            if (adminDropdown) adminDropdown.classList.add('hidden');
            if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
        }
        
        function toggleNotificationsDropdown() {
            const dropdown = document.getElementById('notificationsDropdown');
            dropdown.classList.toggle('hidden');
            
            // Fermer les autres dropdowns
            const userDropdown = document.getElementById('userDropdown');
            const communicationDropdown = document.getElementById('communicationDropdown');
            const adminDropdown = document.getElementById('adminDropdown');
            if (userDropdown) userDropdown.classList.add('hidden');
            if (communicationDropdown) communicationDropdown.classList.add('hidden');
            if (adminDropdown) adminDropdown.classList.add('hidden');
        }

        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobileMenu');
            mobileMenu.classList.toggle('hidden');
        }

        // Fermer les dropdowns quand on clique à l'extérieur
        document.addEventListener('click', function(event) {
            const userDropdown = document.getElementById('userDropdown');
            const communicationDropdown = document.getElementById('communicationDropdown');
            const adminDropdown = document.getElementById('adminDropdown');
            const clientDropdown = document.getElementById('clientDropdown');
            const notificationsDropdown = document.getElementById('notificationsDropdown');
            
            if (!event.target.closest('.relative')) {
                if (userDropdown) userDropdown.classList.add('hidden');
                if (communicationDropdown) communicationDropdown.classList.add('hidden');
                if (adminDropdown) adminDropdown.classList.add('hidden');
                if (clientDropdown) clientDropdown.classList.add('hidden');
                if (notificationsDropdown) notificationsDropdown.classList.add('hidden');
            }
        });
        
        // Afficher le badge de notification s'il y a des notifications
        document.addEventListener('DOMContentLoaded', function() {
            @auth
                @php
                    $user = Auth::user();
                    $notifications = [];
                    
                    // Notifications pour solde bas Wizall (tous les rôles)
                    if($user->role === 'agent' || $user->role === 'supervisor' || $user->role === 'admin') {
                        $todayBalance = \App\Models\Balance::getTodayBalance();
                        if($todayBalance && $todayBalance->wizall_current_balance < 50000) {
                            $notifications[] = [
                                'type' => 'warning',
                                'icon' => 'fas fa-exclamation-triangle',
                                'title' => 'Solde Wizall bas',
                                'message' => 'Solde Wizall actuel: ' . number_format($todayBalance->wizall_current_balance, 0) . ' FCFA',
                                'time' => 'Maintenant'
                            ];
                        }
                    }
                    
                    // Notifications de fin de journée pour agents
                    if($user->role === 'agent') {
                        $hour = date('H');
                        if($hour >= 17) { // Après 17h
                            $notifications[] = [
                                'type' => 'info',
                                'icon' => 'fas fa-clock',
                                'title' => 'Fin de journée',
                                'message' => 'N\'oubliez pas de rendre la caisse',
                                'time' => 'Maintenant'
                            ];
                        }
                    }
                    
                    // Messages clients non lus (pour agents, superviseurs, admins)
                    if($user->role !== 'client') {
                        $unreadClientMessages = \App\Models\ClientMessage::where('is_read', false)->count();
                        if($unreadClientMessages > 0) {
                            $notifications[] = [
                                'type' => 'info',
                                'icon' => 'fas fa-envelope',
                                'title' => 'Messages clients',
                                'message' => $unreadClientMessages . ' message(s) client(s) non lu(s)',
                                'time' => 'Nouveau'
                            ];
                        }
                    }
                @endphp
                
                @if(count($notifications) > 0)
                    const notificationBadge = document.getElementById('notification-badge');
                    if (notificationBadge) {
                        notificationBadge.classList.remove('hidden');
                    }
                @endif
            @endauth
        });
        
        // Fonction pour marquer toutes les notifications comme lues
        function markAllNotificationsRead() {
            fetch('{{ route("notifications.mark-all-read") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Masquer le badge de notification
                    const notificationBadge = document.getElementById('notification-badge');
                    if (notificationBadge) {
                        notificationBadge.classList.add('hidden');
                    }
                    
                    // Fermer le dropdown
                    const notificationsDropdown = document.getElementById('notificationsDropdown');
                    if (notificationsDropdown) {
                        notificationsDropdown.classList.add('hidden');
                    }
                    
                    // Recharger la page pour mettre à jour les notifications
                    window.location.reload();
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
        }
    </script>
</body>
</html> 