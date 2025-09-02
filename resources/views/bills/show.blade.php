@extends('layouts.app')

@section('title', 'Détails de la facture #' . $bill->bill_number)

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-50 via-white to-gray-50 py-8 px-4">
    <div class="max-w-6xl mx-auto">
        <!-- En-tête -->
        <div class="bg-white rounded-2xl shadow-xl p-6 mb-8 border border-gray-100">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mr-4">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900">Facture #{{ $bill->bill_number }}</h1>
                        <p class="text-gray-600">{{ $bill->company->name }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <!-- Status Badge -->
                    @switch($bill->status)
                        @case('pending')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-yellow-100 text-yellow-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                En attente
                            </span>
                            @break
                        @case('confirmed')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Confirmée
                            </span>
                            @break
                        @case('paid')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                                Payée
                            </span>
                            @break
                        @case('cancelled')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Annulée
                            </span>
                            @break
                    @endswitch

                    <!-- Actions -->
                    @auth
                        @if(auth()->user()->isAgent() || auth()->user()->isSupervisor() || auth()->user()->isAdmin())
                            <a href="{{ route('bills.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                                <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Nouvelle facture
                            </a>
                        @endif
                    @endauth
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Informations de la facture -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    Informations de la facture
                </h2>

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
                            <p class="text-sm font-medium text-gray-600">Numéro client</p>
                            <p class="text-lg font-bold text-gray-900">{{ $bill->client_number }}</p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                        <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Montant TTC</p>
                            <p class="text-2xl font-bold text-gray-900">{{ number_format($bill->amount, 0, ',', ' ') }} <span class="text-lg text-gray-600">FCFA</span></p>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gradient-to-r from-indigo-50 to-indigo-100 rounded-xl">
                        <div class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-600">Entreprise</p>
                            <div class="flex items-center mt-1">
                                @if($bill->company->logo)
                                    <img src="{{ asset('storage/' . $bill->company->logo) }}"
                                         alt="{{ $bill->company->name }}"
                                         class="rounded mr-3 w-8 h-8 object-cover">
                                @endif
                                <p class="text-lg font-bold text-gray-900">{{ $bill->company->name }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl">
                        <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-600">Date de soumission</p>
                            <p class="text-lg font-bold text-gray-900">{{ $bill->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations du client -->
            <div class="bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
                <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    Informations du client
                </h2>

                @if($bill->user)
                    <div class="space-y-4">
                        <div class="flex items-center p-4 bg-gradient-to-r from-green-50 to-green-100 rounded-xl">
                            <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Nom</p>
                                <p class="text-lg font-bold text-gray-900">{{ $bill->user->name }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl">
                            <div class="w-3 h-3 bg-blue-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Email</p>
                                <p class="text-lg font-bold text-gray-900">{{ $bill->user->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center p-4 bg-gradient-to-r from-purple-50 to-purple-100 rounded-xl">
                            <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                            <div>
                                <p class="text-sm font-medium text-gray-600">Type de compte</p>
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-800">
                                    Client inscrit
                                </span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="bg-gradient-to-r from-blue-50 to-indigo-100 rounded-xl p-6 border border-blue-200">
                        <div class="flex items-start">
                            <div class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center mr-4">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                @if($bill->client_name)
                                    <h3 class="text-lg font-bold text-blue-900 mb-2">{{ $bill->client_name }}</h3>
                                    <p class="text-blue-700">Client externe - Numéro: {{ $bill->client_number }}</p>
                                @else
                                    <h3 class="text-lg font-bold text-blue-900 mb-2">Client anonyme</h3>
                                    <p class="text-blue-700">Cette facture a été soumise sans création de compte.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endif

                @if($bill->cancellation_message)
                    <div class="mt-6 bg-gradient-to-r from-red-50 to-red-100 rounded-xl p-6 border border-red-200">
                        <h3 class="text-lg font-bold text-red-900 mb-2 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Motif d'annulation
                        </h3>
                        <p class="text-red-700">{{ $bill->cancellation_message }}</p>
                    </div>
                @endif
            </div>
        </div>

        @if($bill->uploaded_file)
        <!-- Fichier joint -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <div class="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                    </svg>
                </div>
                Fichier joint
            </h2>

            <div class="flex items-center p-4 bg-gradient-to-r from-orange-50 to-orange-100 rounded-xl">
                <div class="w-12 h-12 bg-orange-200 rounded-xl flex items-center justify-center mr-4">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <p class="font-bold text-gray-900">Facture uploadée</p>
                    <p class="text-sm text-gray-600">Fichier joint par le client</p>
                </div>
                <a href="{{ asset('storage/' . $bill->uploaded_file) }}"
                   target="_blank"
                   class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Télécharger
                </a>
            </div>
        </div>
        @endif

        @if($bill->payments->count() > 0)
        <!-- Historique des paiements -->
        <div class="mt-8 bg-white rounded-2xl shadow-xl p-6 border border-gray-100">
            <h2 class="text-xl font-bold text-gray-900 mb-6 flex items-center">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                </div>
                Historique des paiements
            </h2>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Date</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Méthode</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Montant</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Référence</th>
                            <th class="text-left py-3 px-4 font-semibold text-gray-700">Agent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bill->payments as $payment)
                        <tr class="border-b border-gray-100 hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $payment->transaction_date->format('d/m/Y H:i') }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium
                                    @if($payment->payment_method === 'wave') bg-blue-100 text-blue-800
                                    @elseif($payment->payment_method === 'orange_money') bg-orange-100 text-orange-800
                                    @elseif($payment->payment_method === 'wizall') bg-purple-100 text-purple-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($payment->payment_method) }}
                                </span>
                            </td>
                            <td class="py-3 px-4 font-bold text-green-600">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</td>
                            <td class="py-3 px-4">
                                <code class="bg-gray-100 px-2 py-1 rounded text-sm">{{ $payment->transaction_reference }}</code>
                            </td>
                            <td class="py-3 px-4">{{ $payment->agent->name ?? 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- Actions en bas -->
        <div class="mt-8 flex justify-center gap-4">
            <a href="{{ route('user.dashboard') }}"
               class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition-colors inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Retour au dashboard
            </a>

            @if(!$bill->isPaid() && auth()->check())
                <a href="{{ route('payments.create', $bill) }}"
                   class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition-colors inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                    </svg>
                    Procéder au paiement
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
