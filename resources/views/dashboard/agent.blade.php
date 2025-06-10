@extends('layouts.app')

@section('title', 'Tableau de bord Agent')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-tachometer-alt mr-3 text-blue-600"></i>
                        Tableau de bord Agent
                    </h1>
                    <p class="mt-2 text-gray-600">Gérez les factures et paiements</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-4">
                    <a href="{{ route('chat.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        Chat
                    </a>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        <i class="fas fa-user mr-2"></i>
                        {{ auth()->user()->name }} - {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Balance Alert (if Wizall balance is low) -->
        @if(isset($todayBalance) && $todayBalance && $todayBalance->wizall_current_balance < 50000)
        <div class="mb-6 bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                <strong>Alerte :</strong> Solde Wizall bas ({{ number_format($todayBalance->wizall_current_balance, 0) }} FCFA). Contactez votre superviseur.
            </div>
        </div>
        @endif

        <!-- Soldes du jour -->
        @if($todayBalance)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-wallet mr-2 text-green-600"></i>
                Soldes du jour - Vue unifiée ({{ $balanceData['last_update'] }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Caisse</p>
                    <p class="text-2xl font-semibold text-blue-600">{{ number_format($balanceData['cash_balance'], 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Wizall</p>
                    <p class="text-2xl font-semibold {{ $balanceData['wizall_current_balance'] < 50000 ? 'text-red-600' : 'text-green-600' }}">
                        {{ number_format($balanceData['wizall_current_balance'], 0) }} FCFA
                    </p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Wave</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ number_format($balanceData['wave_final_balance'], 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Orange Money</p>
                    <p class="text-2xl font-semibold text-orange-600">{{ number_format($balanceData['orange_money_balance'], 0) }} FCFA</p>
                </div>
            </div>
            
            <!-- Informations additionnelles pour les agents -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">À Rendre Total</p>
                    <p class="text-2xl font-semibold text-red-600">{{ number_format($balanceData['agent_return_amount'], 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-indigo-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Versements Aujourd'hui</p>
                    <p class="text-2xl font-semibold text-indigo-600">{{ $balanceData['deposits_summary']['deposits_count'] }}</p>
                </div>
            </div>
        </div>
        @endif

        <!-- Performance Statistics -->
        @if(isset($todayStats))
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-blue-100 rounded-lg">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Transactions Aujourd'hui</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $todayStats['payments_count'] ?? 0 }}</p>
                        <p class="text-sm text-gray-500">{{ number_format($todayStats['total_amount'] ?? 0, 0) }} FCFA</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-green-100 rounded-lg">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Frais Générés</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($todayStats['fees_generated'] ?? 0, 0) }} FCFA</p>
                        <p class="text-sm text-gray-500">1% des transactions</p>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center">
                    <div class="p-2 bg-red-100 rounded-lg">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">À Rendre en Fin de Journée</p>
                        <p class="text-2xl font-semibold text-red-600">{{ number_format(($todayStats['total_amount'] ?? 0) - ($todayStats['fees_generated'] ?? 0), 0) }} FCFA</p>
                        <p class="text-sm text-gray-500">Montant - Frais</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- En attente -->
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">En attente</p>
                        <p class="text-3xl font-bold">{{ $bills->where('status', 'pending')->count() }}</p>
                    </div>
                    <div class="p-3 bg-yellow-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Confirmées -->
            <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Confirmées</p>
                        <p class="text-3xl font-bold">{{ $bills->where('status', 'confirmed')->count() }}</p>
                    </div>
                    <div class="p-3 bg-green-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-check-circle text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Payées -->
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Payées</p>
                        <p class="text-3xl font-bold">{{ $bills->where('status', 'paid')->count() }}</p>
                    </div>
                    <div class="p-3 bg-blue-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Annulées -->
            <div class="bg-gradient-to-r from-red-400 to-red-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-100 text-sm font-medium">Annulées</p>
                        <p class="text-3xl font-bold">{{ $bills->where('status', 'cancelled')->count() }}</p>
                    </div>
                    <div class="p-3 bg-red-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions Rapides -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900 flex items-center">
                    <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                    Actions Rapides
                </h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('bills.third-party.form') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Paiement Tiers</p>
                            <p class="text-xs text-gray-500">Payer pour un client</p>
                        </div>
                    </a>

                    <a href="{{ route('chat.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Chat Équipe</p>
                            <p class="text-xs text-gray-500">Communication interne</p>
                        </div>
                    </a>

                    <a href="{{ route('agent.balances.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Gestion Balances</p>
                            <p class="text-xs text-gray-500">Soldes quotidiens</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <form method="GET" action="{{ route('user.dashboard') }}" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Statut</label>
                    <select name="status" id="status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Tous les statuts</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmées</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Payées</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulées</option>
                    </select>
                </div>
                
                <div>
                    <label for="company" class="block text-sm font-medium text-gray-700 mb-2">Entreprise</label>
                    <select name="company" id="company" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Toutes les entreprises</option>
                        @foreach($bills->pluck('company')->unique() as $company)
                            <option value="{{ $company->id }}" {{ request('company') == $company->id ? 'selected' : '' }}>
                                {{ $company->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input type="text" name="search" id="search" 
                           placeholder="Rechercher par numéro de facture, client..." 
                           value="{{ request('search') }}"
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div class="flex items-end">
                    <button type="submit" 
                            class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 flex items-center justify-center">
                        <i class="fas fa-search mr-2"></i>Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Bills Table -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-file-invoice mr-2 text-gray-600"></i>
                    Gestion des Factures
                </h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $bills->total() }} facture(s)
                </span>
            </div>
            
            <div class="overflow-x-auto">
                @if($bills->count() > 0)
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($bills as $bill)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="font-medium text-gray-900">#{{ $bill->bill_number }}</div>
                                    <div class="text-sm text-gray-500">Client: {{ $bill->client_number }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        @if($bill->company->logo)
                                            <img src="{{ asset('storage/' . $bill->company->logo) }}" 
                                                 alt="{{ $bill->company->name }}" 
                                                 class="h-8 w-8 rounded mr-3">
                                        @endif
                                        <span class="text-sm text-gray-900">{{ $bill->company->name }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($bill->user)
                                        <div class="text-sm font-medium text-gray-900">{{ $bill->user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $bill->user->email }}</div>
                                    @else
                                        <span class="text-sm text-gray-500">Client anonyme</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ number_format($bill->amount, 0, ',', ' ') }} FCFA</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @switch($bill->status)
                                        @case('pending')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                <i class="fas fa-clock mr-1"></i>En attente
                                            </span>
                                            @break
                                        @case('confirmed')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <i class="fas fa-check-circle mr-1"></i>Confirmée
                                            </span>
                                            @break
                                        @case('paid')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <i class="fas fa-credit-card mr-1"></i>Payée
                                            </span>
                                            @break
                                        @case('cancelled')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                <i class="fas fa-times-circle mr-1"></i>Annulée
                                            </span>
                                            @break
                                    @endswitch
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $bill->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('bills.show', $bill) }}" 
                                           class="text-blue-600 hover:text-blue-900">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if(!$bill->isPaid())
                                            <a href="{{ route('payments.create', $bill) }}" 
                                               class="text-green-600 hover:text-green-900"
                                               title="Procéder au paiement">
                                                <i class="fas fa-credit-card"></i>
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-gray-400 text-6xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune facture trouvée</h3>
                        <p class="text-gray-500">Aucune facture ne correspond à vos critères de recherche.</p>
                    </div>
                @endif
            </div>
            
            @if($bills->hasPages())
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $bills->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection 