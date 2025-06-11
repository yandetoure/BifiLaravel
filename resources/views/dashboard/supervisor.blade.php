@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord Superviseur</h1>
            <p class="text-gray-600">Supervision des opérations et agents</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('chat.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Chat
            </a>
            <a href="{{ route('supervisor.end-of-day') }}" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Calculs Fin de Journée
            </a>
        </div>
    </div>

    <!-- Alertes -->
    @if(!empty($alerts))
        <div class="mb-6">
            @foreach($alerts as $alert)
                <div class="bg-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-100 border border-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-400 text-{{ $alert['type'] === 'warning' ? 'yellow' : 'red' }}-700 px-4 py-3 rounded mb-2">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ $alert['message'] }}
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <!-- Statistiques du jour -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Chiffre d'Affaires</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($todayRevenue, 0) }} FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 8h6m-5 0a3 3 0 110 6H9l3 3-3-3h-.5A2.5 2.5 0 014 13.5V11a2 2 0 012-2h3z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Frais Totaux</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($todayFees, 0) }} FCFA</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Bénéfices Immédiats</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ number_format($todayProfit, 0) }} FCFA</p>
                    <p class="text-xs text-gray-500">0.8% des frais</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Agents Actifs</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ count($agentStats) }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Actions Rapides</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('bills.third-party.form') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <div class="p-2 bg-orange-100 rounded-lg">
                            <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Paiement Client Tiers</p>
                        <p class="text-xs text-gray-500">Payer une facture pour un client</p>
                    </div>
                </a>

                <a href="{{ route('chat.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <div class="p-2 bg-blue-100 rounded-lg">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                            </svg>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-900">Chat Équipe</p>
                        <p class="text-xs text-gray-500">Communication interne</p>
                    </div>
                </a>

                <a href="{{ route('supervisor.balances.index') }}" class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition duration-200">
                    <div class="flex-shrink-0 mr-3">
                        <div class="p-2 bg-green-100 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
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

    <!-- Soldes actuels -->
    @if($todayBalance)
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Soldes Actuels</h2>
        </div>
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Wizall Début</p>
                    <p class="text-lg font-semibold text-blue-600">{{ number_format($todayBalance->wizall_start_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Wizall Actuel</p>
                    <p class="text-lg font-semibold text-green-600">{{ number_format($todayBalance->wizall_current_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Wave</p>
                    <p class="text-lg font-semibold text-purple-600">{{ number_format($todayBalance->wave_final_balance, 0) }} FCFA</p>
                </div>
                <div class="text-center">
                    <p class="text-sm font-medium text-gray-600">Orange Money</p>
                    <p class="text-lg font-semibold text-orange-600">{{ number_format($todayBalance->orange_money_balance, 0) }} FCFA</p>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Performance des agents -->
    <div class="bg-white rounded-lg shadow mb-8">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Performance des Agents</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agent</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant Total</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Frais Générés</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($agentStats as $stat)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                                    <span class="text-blue-600 font-semibold">{{ substr($stat['agent']->name, 0, 2) }}</span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">{{ $stat['agent']->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $stat['agent']->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $stat['payments_count'] }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stat['total_amount'], 0) }} FCFA</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($stat['fees_generated'], 0) }} FCFA</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="{{ route('supervisor.agent-details', $stat['agent']->id) }}" class="text-blue-600 hover:text-blue-900">Détails</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Versement bancaire -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Versement Bancaire</h2>
            <p class="text-sm text-gray-600">Alimenter le compte Wizall (sera ajouté au montant à rendre par l'agent)</p>
        </div>
        <div class="p-6">
            <form action="{{ route('supervisor.bank-deposit') }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Agent concerné</label>
                        <select name="agent_id" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Sélectionner un agent</option>
                            @foreach($agentStats as $stat)
                                <option value="{{ $stat['agent']->id }}">{{ $stat['agent']->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Montant (FCFA)</label>
                        <input type="number" name="amount" required min="0" step="1" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description (optionnel)</label>
                        <input type="text" name="description" class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600">
                    Effectuer le versement
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// Auto-refresh alerts every 30 seconds
setInterval(function() {
    fetch('{{ route('supervisor.dashboard') }}')
        .then(response => response.text())
        .then(html => {
            // Update only alerts section if needed
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newAlerts = doc.querySelector('.mb-6');
            if (newAlerts) {
                const currentAlerts = document.querySelector('.mb-6');
                if (currentAlerts) {
                    currentAlerts.innerHTML = newAlerts.innerHTML;
                }
            }
        })
        .catch(error => console.log('Error updating alerts:', error));
}, 30000);
</script>
@endsection 