@extends('layouts.app')

@section('title', 'Dashboard Superviseur')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-user-tie mr-3"></i>
                    Dashboard Superviseur
                </h1>
                <p class="mt-2 text-gray-600">Vue d'ensemble et gestion des opérations</p>
            </div>
            <div class="mt-4 flex md:mt-0 md:ml-4">
                <span class="inline-flex items-center px-3 py-2 border border-green-300 rounded-md text-sm font-medium text-green-700 bg-green-50">
                    <i class="fas fa-shield-alt mr-2"></i>
                    Superviseur
                </span>
            </div>
        </div>
    </div>

    <!-- Accès rapides -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Gestion des versements -->
        <a href="{{ route('deposits.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-money-bill-transfer text-blue-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Gestion des Versements</dt>
                            <dd class="text-sm text-gray-600">Balance unifiée et suivi en temps réel</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Gestion des factures -->
        <a href="{{ route('admin.bills.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-file-invoice text-green-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Gestion des Factures</dt>
                            <dd class="text-sm text-gray-600">Suivi et validation des factures</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Gestion des paiements -->
        <a href="{{ route('admin.payments.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-credit-card text-purple-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Gestion des Paiements</dt>
                            <dd class="text-sm text-gray-600">Suivi des transactions</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Gestion des balances -->
        <a href="{{ route('admin.balances.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wallet text-orange-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Gestion des Balances</dt>
                            <dd class="text-sm text-gray-600">Vue d'ensemble des soldes</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Rapports -->
        <a href="{{ route('admin.reports.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-red-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-bar text-red-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Rapports</dt>
                            <dd class="text-sm text-gray-600">Statistiques et exports</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>

        <!-- Notifications -->
        <a href="{{ route('admin.notifications.index') }}" class="block bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-indigo-500 hover:bg-gray-50 transition duration-150 ease-in-out">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-bell text-indigo-600 text-3xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-lg font-medium text-gray-900">Notifications</dt>
                            <dd class="text-sm text-gray-600">Gestion des alertes</dd>
                        </dl>
                    </div>
                    <div class="flex-shrink-0">
                        <i class="fas fa-arrow-right text-gray-400"></i>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Statistiques rapides -->
    <div class="bg-white shadow-lg rounded-lg mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-chart-line mr-2"></i>
                Aperçu Aujourd'hui
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <div class="text-2xl font-bold text-blue-600">{{ \App\Models\Bill::whereDate('created_at', today())->count() }}</div>
                    <div class="text-sm text-gray-600">Nouvelles Factures</div>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <div class="text-2xl font-bold text-green-600">{{ \App\Models\Payment::whereDate('created_at', today())->count() }}</div>
                    <div class="text-sm text-gray-600">Paiements Effectués</div>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <div class="text-2xl font-bold text-purple-600">{{ \App\Models\Deposit::whereDate('deposit_date', today())->count() }}</div>
                    <div class="text-sm text-gray-600">Versements Aujourd'hui</div>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <div class="text-2xl font-bold text-orange-600">{{ number_format(\App\Models\Payment::whereDate('created_at', today())->sum('amount'), 0) }} FCFA</div>
                    <div class="text-sm text-gray-600">Montant Total Encaissé</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions rapides -->
    <div class="bg-white shadow-lg rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-bolt mr-2"></i>
                Actions Rapides
            </h3>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('deposits.index') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-money-bill-transfer mr-2"></i>
                    Nouveau Versement
                </a>
                <a href="{{ route('bills.third-party.form') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-hand-holding-dollar mr-2"></i>
                    Paiement Tiers
                </a>
                <a href="{{ route('admin.reports.daily') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-purple-600 hover:bg-purple-700">
                    <i class="fas fa-file-excel mr-2"></i>
                    Rapport Journalier
                </a>
            </div>
        </div>
    </div>
</div>
@endsection 