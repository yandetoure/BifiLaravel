<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', '₿iFi by B!consulting') - Paiement de Factures</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- Custom CSS -->
    <link href="{{ asset('css/bitcoin-logo.css') }}" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bifi-orange': '#eb6d19',
                        'bifi-turquoise': '#5aa9a4',
                        'bifi-blue': '#007590',
                    }
                }
            }
        }
    </script>
    <!-- Configuration Tailwind personnalisée -->
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'bifi-orange': '#eb6d19',
                        'bifi-turquoise': '#5aa9a4',
                        'bifi-blue': '#007590',
                    }
                }
            }
        }
    </script>
</head>
<body class="font-sans antialiased">
    <div id="app" class="min-h-screen flex flex-col">
        {{-- Modern Navbar, caché sur la home --}}
        @if (!Request::is('/'))
        <nav id="navbar" class="fixed w-full z-50 transition-all duration-300 bg-black bg-opacity-90 backdrop-blur shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-20 items-center">
                    <div class="flex-shrink-0 flex items-center">
                        <a href="{{ route('home') }}" class="flex items-center space-x-2">
                            <div class="bitcoin-logo">₿</div>
                            <span class="text-white text-2xl font-bold">₿iFi</span>
                        </a>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-10 flex items-center space-x-8">
                            <a href="{{ route('home') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Accueil</a>
                            <a href="{{ route('about') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">À propos</a>
                            @auth
                                @if(Auth::user()->role === 'client')
                                    <a href="{{ route('my.bills') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Mes Factures</a>
                                    <a href="{{ route('my.receipts') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Mes Reçus</a>
                                @elseif(Auth::user()->role === 'admin')
                                    <a href="{{ route('admin.dashboard') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Admin</a>
                                    <a href="{{ route('admin.bills.index') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Factures</a>
                                    <a href="{{ route('admin.payments.index') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Paiements</a>
                                @elseif(Auth::user()->role === 'supervisor')
                                    <a href="{{ route('supervisor.dashboard') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Superviseur</a>
                                    <a href="{{ route('deposits.index') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Versements</a>
                                @else
                                    <a href="{{ route('agent.dashboard') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Agent</a>
                                @endif
                                <a href="{{ route('chat.index') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Chat</a>
                            @endauth
                            <a href="{{ route('bills.create') }}" class="bg-bifi-turquoise text-white px-6 py-2 rounded-lg text-lg font-semibold hover:bg-opacity-90">Payer une facture</a>
                        </div>
                    </div>
                    <div class="hidden md:flex items-center space-x-4">
                        @guest
                            <a href="{{ route('login') }}" class="text-white hover:text-bifi-orange px-3 py-2 rounded-md text-lg font-medium transition-colors">Connexion</a>
                            <a href="{{ route('register') }}" class="bg-bifi-orange hover:bg-bifi-turquoise text-white px-4 py-2 rounded-lg text-lg font-semibold transition-colors">S'inscrire</a>
                        @else
                            <div class="relative group">
                                <button class="flex items-center space-x-2 focus:outline-none">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-bifi-turquoise text-white text-xl font-bold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </span>
                                    <span class="text-white font-medium">{{ Auth::user()->name }}</span>
                                    <svg class="w-4 h-4 text-white ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                                </button>
                                <div class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-2 z-50 hidden group-hover:block">
                                    <a href="#" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">Profil</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">Déconnexion</button>
                                    </form>
                                </div>
                            </div>
                        @endguest
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
                    <a href="{{ route('home') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Accueil</a>
                    <a href="{{ route('about') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">À propos</a>
                    @auth
                        @if(Auth::user()->role === 'client')
                            <a href="{{ route('my.bills') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Mes Factures</a>
                            <a href="{{ route('my.receipts') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Mes Reçus</a>
                        @elseif(Auth::user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Admin</a>
                            <a href="{{ route('admin.bills.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Factures</a>
                            <a href="{{ route('admin.payments.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Paiements</a>
                        @elseif(Auth::user()->role === 'supervisor')
                            <a href="{{ route('supervisor.dashboard') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Superviseur</a>
                            <a href="{{ route('deposits.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Versements</a>
                        @else
                            <a href="{{ route('agent.dashboard') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Agent</a>
                        @endif
                        <a href="{{ route('chat.index') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Chat</a>
                    @endauth
                    <a href="{{ route('bills.create') }}" class="bg-bifi-turquoise text-white block px-3 py-2 rounded-md text-base font-medium mt-2">Payer une facture</a>
                    @guest
                        <a href="{{ route('login') }}" class="text-white block px-3 py-2 rounded-md text-base font-medium">Connexion</a>
                        <a href="{{ route('register') }}" class="bg-bifi-orange text-white block px-3 py-2 rounded-md text-base font-medium mt-2">S'inscrire</a>
                    @else
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left px-3 py-2 text-gray-700 hover:bg-gray-100">Déconnexion</button>
                        </form>
                    @endguest
                </div>
            </div>
        </nav>
        @endif

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

        <!-- WhatsApp Floating Button -->
        <a href="https://wa.me/221787056767" 
           class="whatsapp-float"
           target="_blank"
           rel="noopener noreferrer">
            <i class="fab fa-whatsapp text-2xl"></i>
        </a>

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

        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.remove('navbar-transparent');
                navbar.classList.add('navbar-solid');
            } else {
                navbar.classList.remove('navbar-solid');
                navbar.classList.add('navbar-transparent');
            }
        });
    </script>
</body>
</html> 
