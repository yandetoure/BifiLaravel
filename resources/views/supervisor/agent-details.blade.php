@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Détails Agent - {{ $agent->name }}</h1>
            <p class="text-gray-600">{{ $agent->email }}</p>
        </div>
        <a href="{{ route('supervisor.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
            Retour Dashboard
        </a>
    </div>

    <!-- Statistiques de l'agent -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aujourd'hui</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $todayPayments->count() }} transactions</p>
                    <p class="text-sm text-gray-500">{{ number_format($todayPayments->sum('amount'), 0) }} FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a1.997 1.997 0 01-2.828 0l-7-7A1.997 1.997 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Cette Semaine</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $weekPayments->count() }} transactions</p>
                    <p class="text-sm text-gray-500">{{ number_format($weekPayments->sum('amount'), 0) }} FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Ce Mois</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $monthPayments->count() }} transactions</p>
                    <p class="text-sm text-gray-500">{{ number_format($monthPayments->sum('amount'), 0) }} FCFA</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Transactions d'aujourd'hui -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Transactions d'Aujourd'hui</h2>
            <p class="text-sm text-gray-600">{{ $todayPayments->count() }} transactions - {{ number_format($todayPayments->sum('fee_amount'), 0) }} FCFA de frais générés</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Client</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Facture</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frais</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Méthode</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($todayPayments as $payment)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->created_at->format('H:i') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $payment->client_name ?? 'Non spécifié' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            @if($payment->bill)
                                <div>
                                    <div class="font-medium">{{ $payment->bill->company_name }}</div>
                                    <div class="text-xs text-gray-500">{{ $payment->bill->bill_reference }}</div>
                                </div>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($payment->amount, 0) }} FCFA
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <div>
                                <div class="font-medium">{{ number_format($payment->fee_amount ?? 0, 0) }} FCFA</div>
                                <div class="text-xs text-gray-500">
                                    Profit: {{ number_format(($payment->fee_amount ?? 0) * 0.8, 0) }} FCFA
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $payment->payment_method === 'wizall' ? 'bg-blue-100 text-blue-800' : 
                                   ($payment->payment_method === 'wave' ? 'bg-purple-100 text-purple-800' : 
                                   ($payment->payment_method === 'orange_money' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800')) }}">
                                {{ ucfirst($payment->payment_method ?? 'Cash') }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                {{ $payment->status === 'completed' ? 'bg-green-100 text-green-800' : 
                                   ($payment->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                {{ ucfirst($payment->status ?? 'pending') }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p>Aucune transaction aujourd'hui</p>
                                <p class="text-xs">Les transactions de cet agent apparaîtront ici</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Calcul pour fin de journée -->
    @if($todayPayments->count() > 0)
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex">
            <svg class="w-6 h-6 text-blue-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
            <div class="ml-3">
                <h3 class="text-lg font-semibold text-blue-900">Calcul Fin de Journée</h3>
                <div class="mt-2 text-sm text-blue-800">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div>
                            <p class="font-medium">Total Collecté:</p>
                            <p class="text-lg font-bold">{{ number_format($todayPayments->sum('amount'), 0) }} FCFA</p>
                        </div>
                        <div>
                            <p class="font-medium">Frais (1%):</p>
                            <p class="text-lg font-bold">{{ number_format($todayPayments->sum('fee_amount'), 0) }} FCFA</p>
                        </div>
                        <div>
                            <p class="font-medium">À Rendre:</p>
                            <p class="text-lg font-bold text-red-600">{{ number_format($todayPayments->sum('amount') - $todayPayments->sum('fee_amount'), 0) }} FCFA</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection 