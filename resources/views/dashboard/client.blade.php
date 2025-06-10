@extends('layouts.app')

@section('title', 'Mon tableau de bord')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                        <i class="fas fa-user-circle mr-3 text-blue-600"></i>
                        Mon tableau de bord
                    </h1>
                    <p class="mt-2 text-gray-600">Bienvenue, {{ auth()->user()->name }}</p>
                </div>
                <div class="mt-4 sm:mt-0">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                        <i class="fas fa-user mr-2"></i>
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg p-6 text-white">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                    <div class="mb-4 md:mb-0">
                        <h2 class="text-xl font-semibold mb-2">Besoin de payer une nouvelle facture ?</h2>
                        <p class="text-blue-100">Téléchargez votre facture ou saisissez les informations manuellement pour un traitement rapide.</p>
                    </div>
                    <div>
                        <a href="{{ route('bills.create') }}" 
                           class="inline-flex items-center px-6 py-3 bg-white text-blue-600 font-semibold rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Nouvelle facture
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- En attente -->
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-yellow-400 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-yellow-100 rounded-full">
                            <i class="fas fa-clock text-yellow-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">En attente</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $userBills->where('status', 'pending')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Confirmées -->
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-green-400 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-green-100 rounded-full">
                            <i class="fas fa-check-circle text-green-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Confirmées</p>
                        <p class="text-2xl font-bold text-green-600">{{ $userBills->where('status', 'confirmed')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Payées -->
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-blue-400 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-blue-100 rounded-full">
                            <i class="fas fa-credit-card text-blue-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Payées</p>
                        <p class="text-2xl font-bold text-blue-600">{{ $userBills->where('status', 'paid')->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Annulées -->
            <div class="bg-white rounded-lg shadow-sm border-l-4 border-red-400 p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <div class="p-3 bg-red-100 rounded-full">
                            <i class="fas fa-times-circle text-red-600 text-xl"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-600">Annulées</p>
                        <p class="text-2xl font-bold text-red-600">{{ $userBills->where('status', 'cancelled')->count() }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bills History -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <i class="fas fa-history mr-2 text-gray-600"></i>
                    Historique de mes factures
                </h3>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                    {{ $userBills->count() }} facture(s)
                </span>
            </div>
            
            <div class="p-6">
                @if($userBills->count() > 0)
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entreprise</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($userBills as $bill)
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
                                                     class="h-6 w-6 rounded mr-2">
                                            @endif
                                            <span class="text-sm text-gray-900">{{ $bill->company->name }}</span>
                                        </div>
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
                                                <div class="text-xs text-gray-500 mt-1">Votre demande est en cours de traitement</div>
                                                @break
                                            @case('confirmed')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    <i class="fas fa-check-circle mr-1"></i>Confirmée
                                                </span>
                                                <div class="text-xs text-gray-500 mt-1">Facture validée, en attente de paiement</div>
                                                @break
                                            @case('paid')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                    <i class="fas fa-credit-card mr-1"></i>Payée
                                                </span>
                                                <div class="text-xs text-green-600 mt-1">Paiement effectué avec succès</div>
                                                @break
                                            @case('cancelled')
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    <i class="fas fa-times-circle mr-1"></i>Annulée
                                                </span>
                                                @if($bill->cancellation_message)
                                                    <div class="text-xs text-red-600 mt-1" title="{{ $bill->cancellation_message }}">
                                                        {{ Str::limit($bill->cancellation_message, 30) }}
                                                    </div>
                                                @endif
                                                @break
                                        @endswitch
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $bill->created_at->format('d/m/Y') }}</div>
                                        <div class="text-xs text-gray-500">{{ $bill->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <div class="flex items-center space-x-2">
                                            <button type="button" 
                                                    onclick="viewBillDetails({{ $bill->id }})"
                                                    class="text-blue-600 hover:text-blue-900 transition duration-200">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            
                                            @if($bill->uploaded_file)
                                                <a href="{{ asset('storage/' . $bill->uploaded_file) }}" 
                                                   target="_blank" 
                                                   class="text-gray-600 hover:text-gray-900 transition duration-200">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                            
                                            @if($bill->status === 'paid' && $bill->payments->isNotEmpty())
                                                <a href="{{ route('receipts.generate', $bill->payments->first()) }}" 
                                                   target="_blank" 
                                                   class="text-green-600 hover:text-green-900 transition duration-200">
                                                    <i class="fas fa-receipt"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-12">
                        <i class="fas fa-file-invoice text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Aucune facture trouvée</h3>
                        <p class="text-gray-500 mb-6">Vous n'avez pas encore soumis de facture pour paiement.</p>
                        <a href="{{ route('bills.create') }}" 
                           class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-plus-circle mr-2"></i>
                            Payer ma première facture
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function viewBillDetails(billId) {
    // Fonction pour afficher les détails de la facture
    // Peut être implémentée avec une modal ou redirection
    alert('Détails de la facture #' + billId);
}
</script>
@endsection 