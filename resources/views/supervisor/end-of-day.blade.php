@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Calculs Fin de Journée</h1>
            <p class="text-gray-600">{{ date('d/m/Y') }} - Montants à rendre par les agents</p>
        </div>
        <a href="{{ route('supervisor.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            Retour au Dashboard
        </a>
    </div>

    <!-- Résumé global -->
    @if($todayBalance)
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Résumé des Soldes</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Caisse</p>
                    <p class="text-2xl font-semibold text-blue-600">{{ number_format($todayBalance->cash_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Wizall</p>
                    <p class="text-2xl font-semibold text-green-600">{{ number_format($todayBalance->wizall_current_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Wave</p>
                    <p class="text-2xl font-semibold text-purple-600">{{ number_format($todayBalance->wave_final_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Orange Money</p>
                    <p class="text-2xl font-semibold text-orange-600">{{ number_format($todayBalance->orange_money_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-lg">
                    <p class="text-sm font-medium text-gray-600">Total à Rendre</p>
                    <p class="text-2xl font-semibold text-red-600">{{ number_format($todayBalance->total_to_return, 0) }} FCFA</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Calculs par agent -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Calculs par Agent</h2>
            <p class="text-sm text-gray-600">Montants collectés, frais générés et argent à rendre</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Collecté</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frais (1%)</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">À Rendre</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $totalToReturn = 0; @endphp
                    @foreach($calculations as $calc)
                    @php $totalToReturn += $calc['to_return']; @endphp
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold text-lg">{{ substr($calc['agent']->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $calc['agent']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $calc['agent']->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                {{ $calc['payments_count'] }} transactions
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($calc['total_collected'], 0) }} FCFA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <span class="font-medium">{{ number_format($calc['total_fees'], 0) }} FCFA</span>
                                <div class="text-xs text-gray-500">
                                    Profit: {{ number_format($calc['total_fees'] * 0.8, 0) }} FCFA (0.8%)
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-lg font-bold text-red-600">
                                {{ number_format($calc['to_return'], 0) }} FCFA
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($calc['to_return'] > 0)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Doit rendre
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Rien à rendre
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <th colspan="4" class="px-6 py-3 text-right text-sm font-medium text-gray-900">
                            Total à rendre par tous les agents:
                        </th>
                        <th class="px-6 py-3 text-left">
                            <span class="text-xl font-bold text-red-600">{{ number_format($totalToReturn, 0) }} FCFA</span>
                        </th>
                        <th class="px-6 py-3"></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Instructions -->
    <div class="mt-8 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-blue-900">Instructions pour les Agents</h3>
                <div class="mt-2 text-sm text-blue-800">
                    <ul class="list-disc list-inside space-y-1">
                        <li>Les agents doivent rendre le montant indiqué dans la colonne "À Rendre"</li>
                        <li>Ce montant correspond au total collecté moins les frais de 1%</li>
                        <li>Les 0.8% de profit sont automatiquement ajoutés au solde Wizall</li>
                        <li>Les versements bancaires effectués par le superviseur sont déjà inclus dans les calculs</li>
                        <li>Vérifiez que chaque agent a rendu le bon montant avant de clôturer la journée</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="mt-8 flex justify-center space-x-4">
        <button onclick="window.print()" class="bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
            </svg>
            Imprimer Rapport
        </button>
        <a href="{{ route('supervisor.balances') }}" class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
            Voir Historique
        </a>
    </div>
</div>

<style>
@media print {
    .no-print {
        display: none !important;
    }
    
    body {
        font-size: 12px;
    }
    
    .container {
        max-width: none;
        margin: 0;
        padding: 20px;
    }
    
    .shadow {
        box-shadow: none !important;
        border: 1px solid #e5e7eb;
    }
    
    .bg-gray-50 {
        background-color: #f9fafb !important;
        -webkit-print-color-adjust: exact;
    }
    
    .text-red-600 {
        color: #dc2626 !important;
        -webkit-print-color-adjust: exact;
    }
    
    .text-blue-600 {
        color: #2563eb !important;
        -webkit-print-color-adjust: exact;
    }
}
</style>
@endsection 