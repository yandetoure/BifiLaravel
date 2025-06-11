@extends('layouts.app')

@section('title', 'Dashboard Administrateur')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-crown mr-3 text-yellow-600"></i>
                        Dashboard Administrateur
                    </h1>
                    <p class="mt-2 text-gray-600">Vue d'ensemble du système - {{ $balanceData['date'] }}</p>
                </div>
                <div class="mt-4 sm:mt-0 flex space-x-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                        <i class="fas fa-shield-alt mr-2"></i>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Alertes système -->
        @if(count($alerts) > 0)
        <div class="mb-8">
            @foreach($alerts as $alert)
            <div class="mb-4 bg-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-100 border border-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-400 text-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-700 px-4 py-3 rounded">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    <strong>Alerte :</strong> {{ $alert['message'] }}
                </div>
            </div>
            @endforeach
        </div>
        @endif

        <!-- Vue unifiée des balances -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <i class="fas fa-wallet mr-2 text-green-600"></i>
                Balances Système - Vue Unifiée ({{ $balanceData['last_update'] }})
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">À Rendre Agent</p>
                    <p class="text-2xl font-semibold text-red-600">{{ number_format($balanceData['agent_return_amount'], 0) }} FCFA</p>
                </div>
            </div>
        </div>

        <!-- Statistiques du jour -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Factures aujourd'hui -->
            <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-100 text-sm font-medium">Factures Aujourd'hui</p>
                        <p class="text-3xl font-bold">{{ $todayBills }}</p>
                    </div>
                    <div class="p-3 bg-blue-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-file-invoice text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Paiements aujourd'hui -->
            <div class="bg-gradient-to-r from-green-400 to-green-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium">Paiements Aujourd'hui</p>
                        <p class="text-3xl font-bold">{{ $todayPayments }}</p>
                    </div>
                    <div class="p-3 bg-green-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-credit-card text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Revenus aujourd'hui -->
            <div class="bg-gradient-to-r from-purple-400 to-purple-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-100 text-sm font-medium">Revenus Aujourd'hui</p>
                        <p class="text-3xl font-bold">{{ number_format($todayRevenue, 0) }}</p>
                        <p class="text-xs text-purple-200">FCFA</p>
                    </div>
                    <div class="p-3 bg-purple-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-chart-line text-2xl"></i>
                    </div>
                </div>
            </div>

            <!-- Factures en attente -->
            <div class="bg-gradient-to-r from-yellow-400 to-yellow-600 rounded-lg shadow-sm p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium">En Attente</p>
                        <p class="text-3xl font-bold">{{ $pendingBills }}</p>
                    </div>
                    <div class="p-3 bg-yellow-500 bg-opacity-30 rounded-full">
                        <i class="fas fa-clock text-2xl"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Équipe active -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Agents -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-users mr-2 text-blue-600"></i>
                        Agents Actifs
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $activeAgents }}
                    </span>
                </div>
                <p class="text-gray-600">{{ $activeAgents }} agent(s) dans le système</p>
            </div>

            <!-- Superviseurs -->
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                        <i class="fas fa-user-tie mr-2 text-green-600"></i>
                        Superviseurs Actifs
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                        {{ $activeSupervisors }}
                    </span>
                </div>
                <p class="text-gray-600">{{ $activeSupervisors }} superviseur(s) dans le système</p>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                    Actions Rapides Administrateur
                </h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('admin.users.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-blue-100 rounded-lg">
                                <i class="fas fa-users text-blue-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Gestion Utilisateurs</p>
                            <p class="text-xs text-gray-500">Créer, modifier, supprimer</p>
                        </div>
                    </a>

                    <a href="{{ route('admin.reports.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-green-100 rounded-lg">
                                <i class="fas fa-chart-bar text-green-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Rapports</p>
                            <p class="text-xs text-gray-500">Statistiques et exports</p>
                        </div>
                    </a>

                    <a href="{{ route('deposits.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                        <div class="flex-shrink-0 mr-3">
                            <div class="p-2 bg-purple-100 rounded-lg">
                                <i class="fas fa-money-bill-transfer text-purple-600"></i>
                            </div>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Gestion Versements</p>
                            <p class="text-xs text-gray-500">Soldes et opérations</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 