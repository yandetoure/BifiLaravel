@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        {{-- Message de succès --}}
        <div class="bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg mb-6">
            <div class="flex items-center">
                <svg class="w-6 h-6 mr-3" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <div>
                    <h3 class="text-lg font-semibold">Paiement enregistré avec succès!</h3>
                    <p class="text-sm">Le reçu a été généré automatiquement.</p>
                </div>
            </div>
        </div>

        {{-- Détails du paiement --}}
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gradient-to-r from-green-600 to-green-800 text-white p-6">
                <h1 class="text-2xl font-bold">Détails du Paiement</h1>
                <p class="text-green-100 mt-1">Transaction #{{ $payment->transaction_reference }}</p>
            </div>

            <div class="p-6">
                {{-- Informations générales --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Informations Client</h3>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Nom du client:</span>
                            <p class="text-gray-900 font-medium">{{ $payment->receipt->client_name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Numéro client:</span>
                            <p class="text-gray-900 font-medium">{{ $payment->bill->client_number }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Facture:</span>
                            <p class="text-gray-900 font-medium">#{{ $payment->bill->bill_number }}</p>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <h3 class="text-lg font-semibold text-gray-800 border-b pb-2">Détails Transaction</h3>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Référence:</span>
                            <p class="text-gray-900 font-medium">{{ $payment->transaction_reference }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Date:</span>
                            <p class="text-gray-900 font-medium">{{ $payment->transaction_date->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <span class="text-sm font-medium text-gray-600">Méthode:</span>
                            <p class="text-gray-900 font-medium">{{ ucfirst($payment->payment_method) }}</p>
                        </div>
                    </div>
                </div>

                {{-- Montants --}}
                <div class="bg-gradient-to-r from-green-50 to-blue-50 rounded-lg p-6 mb-6 border border-green-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <svg class="w-5 h-5 mr-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                        </svg>
                        Détail du montant payé
                    </h3>

                    {{-- Montant principal + frais mis en évidence --}}
                    <div class="bg-white rounded-lg p-4 mb-4 border-2 border-green-300">
                        <div class="text-center">
                            <p class="text-sm text-gray-600 mb-1">Montant saisi + 1% de frais</p>
                            <p class="text-3xl font-bold text-green-600">{{ number_format($payment->amount, 0, ',', ' ') }} + {{ number_format($payment->fees, 0, ',', ' ') }} FCFA</p>
                            <p class="text-lg font-semibold text-gray-800 mt-1">= {{ number_format($payment->total, 0, ',', ' ') }} FCFA</p>
                        </div>
                    </div>

                    {{-- Détail décomposé --}}
                    <div class="space-y-3">
                        <div class="flex justify-between items-center p-3 bg-blue-50 rounded-lg">
                            <span class="text-gray-700 font-medium">Montant de la facture:</span>
                            <span class="font-semibold text-blue-700">{{ number_format($payment->amount, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="flex justify-between items-center p-3 bg-orange-50 rounded-lg">
                            <span class="text-gray-700 font-medium">Frais de service (1%):</span>
                            <span class="font-semibold text-orange-700">{{ number_format($payment->fees, 0, ',', ' ') }} FCFA</span>
                        </div>
                        <div class="border-t-2 border-green-300 pt-3">
                            <div class="flex justify-between items-center p-3 bg-green-50 rounded-lg">
                                <span class="text-gray-800 font-bold text-lg">Total payé:</span>
                                <span class="font-bold text-green-700 text-xl">{{ number_format($payment->total, 0, ',', ' ') }} FCFA</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Informations agent --}}
                <div class="bg-blue-50 rounded-lg p-4 mb-6">
                    <h4 class="font-semibold text-blue-800 mb-2">Agent responsable</h4>
                    <p class="text-blue-700">{{ $payment->agent->name }} (ID: {{ $payment->agent->id }})</p>
                </div>

                {{-- Reçu --}}
                @if($payment->receipt)
                <div class="border rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Reçu de paiement</h3>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600">Numéro de reçu: <span class="font-medium text-gray-900">{{ $payment->receipt->receipt_number }}</span></p>
                            <p class="text-sm text-gray-500">Généré le {{ $payment->receipt->created_at->format('d/m/Y à H:i') }}</p>
                        </div>
                        <div class="flex space-x-3">
                            <a href="{{ route('receipts.download', $payment->receipt) }}"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Télécharger PDF
                            </a>
                        </div>
                    </div>

                    {{-- Statut d'envoi --}}
                    <div class="mt-4 pt-4 border-t">
                        <h4 class="font-medium text-gray-800 mb-2">Statut d'envoi</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="flex items-center">
                                @if($payment->receipt->sent_by_email)
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-green-700">Envoyé par email</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-500">Non envoyé par email</span>
                                @endif
                            </div>
                            <div class="flex items-center">
                                @if($payment->receipt->sent_by_whatsapp)
                                    <svg class="w-5 h-5 text-green-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-green-700">Envoyé par WhatsApp</span>
                                @else
                                    <svg class="w-5 h-5 text-gray-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-500">Non envoyé par WhatsApp</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Actions --}}
                <div class="flex justify-between items-center pt-6 border-t">
                    <a href="{{ route('user.dashboard') }}"
                       class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Retour au tableau de bord
                    </a>

                    <div class="flex space-x-3">
                        <a href="{{ route('balances.index') }}"
                           class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Voir les soldes
                        </a>
                        @if($payment->receipt)
                        <a href="{{ route('receipts.download', $payment->receipt) }}"
                           class="inline-flex items-center px-4 py-2 bg-green-600 text-white text-sm font-medium rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            Télécharger le reçu
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
