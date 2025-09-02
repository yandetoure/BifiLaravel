@extends('layouts.app')

@section('title', 'Facture soumise avec succès')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-green-50 py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <!-- Success Header Animation -->
        <div class="text-center mb-8 animate-fade-in">
            <div class="relative inline-block">
                <!-- Animated Success Icon -->
                <div class="w-24 h-24 bg-gradient-to-r from-green-400 to-green-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-2xl animate-bounce-slow">
                    <svg class="w-12 h-12 text-white animate-scale-in" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <!-- Ripple Effect -->
                <div class="absolute inset-0 w-24 h-24 rounded-full bg-green-400 opacity-20 animate-ping"></div>
            </div>

            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4 animate-slide-up">
                Facture soumise avec <span class="text-gradient bg-gradient-to-r from-green-500 to-blue-600 bg-clip-text text-transparent">succès</span> !
            </h1>
            <p class="text-xl text-gray-600 max-w-2xl mx-auto animate-slide-up-delay">
                Votre demande de paiement de facture a été reçue et est maintenant <strong class="text-blue-600">en cours de traitement</strong>.
            </p>
        </div>

        <!-- Bill Details Card -->
        @if(isset($bill))
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100 animate-slide-up-delay-2">
            <div class="flex items-center mb-6">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-gray-900">Détails de votre facture</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                        <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Numéro de facture</p>
                            <p class="text-lg font-bold text-gray-900">#{{ $bill->bill_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                        <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Entreprise</p>
                            <p class="text-lg font-bold text-gray-900">{{ $bill->company_name }}</p>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Numéro client</p>
                            <p class="text-lg font-bold text-gray-900">{{ $bill->client_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gradient-to-r from-yellow-50 to-yellow-100 rounded-xl">
                        <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Montant de la facture</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($bill->amount, 0, ',', ' ') }} <span class="text-lg text-gray-600">FCFA</span></p>
                            <p class="text-xs text-gray-500 mt-1">+ 1% de frais lors du paiement</p>
                        </div>
                    </div>

                    {{-- Total à payer --}}
                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl border-2 border-green-300">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Total à payer</p>
                            <p class="text-3xl font-bold text-green-700">{{ number_format($bill->amount * 1.01, 0, ',', ' ') }} <span class="text-lg text-green-600">FCFA</span></p>
                            <p class="text-xs text-gray-500 mt-1">Inclut les frais de 1%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Process Steps -->
        <div class="bg-white rounded-2xl shadow-xl p-8 mb-8 border border-gray-100 animate-slide-up-delay-3">
            <h2 class="text-2xl font-bold text-gray-900 text-center mb-8">Prochaines étapes</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Step 1 -->
                <div class="relative group">
                    <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-2xl p-6 border-2 border-yellow-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="inline-block bg-yellow-500 text-white text-xs px-3 py-1 rounded-full font-bold mb-2">ÉTAPE 1</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Vérification</h3>
                            <p class="text-sm text-gray-600">Nos agents vérifient les informations de votre facture</p>
                        </div>
                    </div>
                    <!-- Connector line -->
                    <div class="hidden md:block absolute top-1/2 -right-3 w-6 h-0.5 bg-gray-300"></div>
                </div>

                <!-- Step 2 -->
                <div class="relative group">
                    <div class="bg-gradient-to-br from-blue-50 to-indigo-100 rounded-2xl p-6 border-2 border-blue-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-400 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <span class="inline-block bg-blue-500 text-white text-xs px-3 py-1 rounded-full font-bold mb-2">ÉTAPE 2</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Confirmation</h3>
                            <p class="text-sm text-gray-600">Votre facture sera confirmée et validée</p>
                        </div>
                    </div>
                    <!-- Connector line -->
                    <div class="hidden md:block absolute top-1/2 -right-3 w-6 h-0.5 bg-gray-300"></div>
                </div>

                <!-- Step 3 -->
                <div class="group">
                    <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-2xl p-6 border-2 border-green-200 transition-all duration-300 hover:shadow-lg hover:-translate-y-1">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-r from-green-400 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform duration-300">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            </div>
                            <span class="inline-block bg-green-500 text-white text-xs px-3 py-1 rounded-full font-bold mb-2">ÉTAPE 3</span>
                            <h3 class="text-lg font-bold text-gray-900 mb-2">Paiement</h3>
                            <p class="text-sm text-gray-600">Le paiement sera effectué et vous recevrez un reçu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Important Notice -->
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 mb-8 text-white animate-slide-up-delay-4">
            <div class="flex items-start">
                <div class="w-8 h-8 bg-white bg-opacity-20 rounded-lg flex items-center justify-center mr-4 mt-1">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold mb-3">Information importante</h3>
                    <div class="space-y-2 text-blue-100">
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-white rounded-full mr-3 mt-2"></div>
                            <span>Votre facture est actuellement en <strong class="text-white">statut "En attente"</strong></span>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-white rounded-full mr-3 mt-2"></div>
                            <span>Nos agents vont vérifier les informations sous peu</span>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-white rounded-full mr-3 mt-2"></div>
                            <span>Vous recevrez une notification dès que le statut change</span>
                        </div>
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-white rounded-full mr-3 mt-2"></div>
                            <span>Lors du paiement, des <strong class="text-white">frais de 1%</strong> seront appliqués</span>
                        </div>
                        @auth
                        <div class="flex items-start">
                            <div class="w-2 h-2 bg-white rounded-full mr-3 mt-2"></div>
                            <span>Vous pouvez suivre l'évolution dans votre <a href="{{ route('user.dashboard') }}" class="text-white font-bold underline hover:text-blue-200">tableau de bord</a></span>
                        </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4 animate-slide-up-delay-5">
            @auth
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('user.dashboard') }}" class="group bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-4 px-8 rounded-2xl text-center transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path>
                    </svg>
                    Aller au tableau de bord
                </a>
                <a href="{{ route('bills.create') }}" class="group bg-white text-blue-600 font-bold py-4 px-8 rounded-2xl text-center border-2 border-blue-600 transition-all duration-300 hover:bg-blue-600 hover:text-white hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Payer une autre facture
                </a>
            </div>
            @else
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('register') }}" class="group bg-gradient-to-r from-green-600 to-green-700 text-white font-bold py-4 px-6 rounded-2xl text-center transition-all duration-300 hover:from-green-700 hover:to-green-800 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                    Créer un compte
                </a>
                <a href="{{ route('bills.create') }}" class="group bg-gradient-to-r from-blue-600 to-blue-700 text-white font-bold py-4 px-6 rounded-2xl text-center transition-all duration-300 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Autre facture
                </a>
                <a href="{{ route('home') }}" class="group bg-white text-gray-700 font-bold py-4 px-6 rounded-2xl text-center border-2 border-gray-300 transition-all duration-300 hover:bg-gray-50 hover:border-gray-400 hover:shadow-xl hover:-translate-y-1 flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2 group-hover:scale-110 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Accueil
                </a>
            </div>
            @endguest
        </div>

        @guest
        <!-- Account Creation Invitation -->
        <div class="mt-8 bg-gradient-to-r from-purple-50 to-pink-50 rounded-2xl p-6 border border-purple-200 animate-slide-up-delay-6">
            <div class="text-center">
                <div class="w-16 h-16 bg-gradient-to-r from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Créez un compte pour plus de fonctionnalités</h3>
                <p class="text-gray-600 mb-4">
                    Avec un compte, vous pourrez suivre toutes vos factures, consulter l'historique de vos paiements et recevoir des notifications.
                </p>
                <a href="{{ route('register') }}" class="inline-flex items-center bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold py-3 px-6 rounded-xl transition-all duration-300 hover:from-purple-700 hover:to-pink-700 hover:shadow-lg">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                    Commencer maintenant
                </a>
            </div>
        </div>
        @endguest
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes slideUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes bounceGlow {
    0%, 100% { transform: scale(1); box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); }
    50% { transform: scale(1.05); box-shadow: 0 0 0 10px rgba(34, 197, 94, 0); }
}

@keyframes scaleIn {
    from { transform: scale(0); }
    to { transform: scale(1); }
}

.animate-fade-in {
    animation: fadeIn 0.8s ease-out;
}

.animate-slide-up {
    animation: slideUp 0.8s ease-out 0.2s both;
}

.animate-slide-up-delay {
    animation: slideUp 0.8s ease-out 0.4s both;
}

.animate-slide-up-delay-2 {
    animation: slideUp 0.8s ease-out 0.6s both;
}

.animate-slide-up-delay-3 {
    animation: slideUp 0.8s ease-out 0.8s both;
}

.animate-slide-up-delay-4 {
    animation: slideUp 0.8s ease-out 1s both;
}

.animate-slide-up-delay-5 {
    animation: slideUp 0.8s ease-out 1.2s both;
}

.animate-slide-up-delay-6 {
    animation: slideUp 0.8s ease-out 1.4s both;
}

.animate-bounce-slow {
    animation: bounceGlow 2s infinite;
}

.animate-scale-in {
    animation: scaleIn 0.6s ease-out 0.8s both;
}
</style>
@endsection
