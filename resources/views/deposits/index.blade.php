@extends('layouts.app')

@section('title', 'Gestion des Versements')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <div class="md:flex md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-3xl font-bold text-gray-900">
                    <i class="fas fa-money-bill-transfer mr-3"></i>
                    Gestion des Versements
                </h1>
                <p class="mt-2 text-gray-600">
                    Vue unifiée des balances - Dernière mise à jour: {{ $balanceData['last_update'] }}
                </p>
            </div>
            <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                <button onclick="refreshBalances()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <i class="fas fa-sync-alt mr-2"></i>
                    Actualiser
                </button>
                <a href="{{ route('deposits.history') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    <i class="fas fa-history mr-2"></i>
                    Historique
                </a>
            </div>
        </div>
    </div>

    <!-- Balances actuelles - Vue unifiée pour tous les rôles -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-blue-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-wallet text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Caisse</dt>
                            <dd class="text-2xl font-bold text-gray-900" id="cash-balance">{{ number_format($balanceData['cash_balance'], 0) }} FCFA</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-green-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-credit-card text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Wizall Actuel</dt>
                            <dd class="text-2xl font-bold {{ $balanceData['wizall_current_balance'] < 50000 ? 'text-red-600' : 'text-gray-900' }}" id="wizall-balance">{{ number_format($balanceData['wizall_current_balance'], 0) }} FCFA</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-water text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Wave</dt>
                            <dd class="text-2xl font-bold text-gray-900" id="wave-balance">{{ number_format($balanceData['wave_final_balance'], 0) }} FCFA</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-orange-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-hand-holding-dollar text-orange-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">À Rendre Agent</dt>
                            <dd class="text-2xl font-bold text-gray-900" id="agent-return">{{ number_format($balanceData['agent_return_amount'], 0) }} FCFA</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg border-l-4 border-purple-500">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-chart-line text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Versements Aujourd'hui</dt>
                            <dd class="text-2xl font-bold text-gray-900" id="total-deposits">{{ $balanceData['deposits_summary']['deposits_count'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Résumé des versements -->
    <div class="bg-white shadow-lg rounded-lg p-6 mb-8">
        <h3 class="text-lg font-medium text-gray-900 mb-4">
            <i class="fas fa-chart-bar mr-2"></i>
            Résumé des versements - {{ $balanceData['date'] }}
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="text-center p-4 bg-blue-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Versements Agents</p>
                <p class="text-xl font-semibold text-blue-600">{{ number_format($balanceData['deposits_summary']['agent_deposits'], 0) }} FCFA</p>
            </div>
            <div class="text-center p-4 bg-green-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Versements Superviseurs</p>
                <p class="text-xl font-semibold text-green-600">{{ number_format($balanceData['deposits_summary']['supervisor_deposits'], 0) }} FCFA</p>
            </div>
            <div class="text-center p-4 bg-orange-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Retraits Espèces</p>
                <p class="text-xl font-semibold text-orange-600">{{ number_format($balanceData['deposits_summary']['cash_collections'], 0) }} FCFA</p>
            </div>
            <div class="text-center p-4 bg-purple-50 rounded-lg">
                <p class="text-sm font-medium text-gray-600">Recharges Wizall</p>
                <p class="text-xl font-semibold text-purple-600">{{ number_format($balanceData['deposits_summary']['wizall_refills'], 0) }} FCFA</p>
            </div>
        </div>
    </div>

    <!-- Formulaires de versement -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Versement Agent -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-blue-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user text-blue-600 mr-2"></i>
                    Versement Agent (Espèces → Wizall)
                </h3>
                <p class="text-sm text-gray-600 mt-1">Diminue la caisse, augmente Wizall en cours</p>
            </div>
            <form action="{{ route('deposits.agent-deposit') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="agent_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="amount" id="agent_amount" min="1" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
                <div class="mb-4">
                    <label for="agent_description" class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                    <textarea name="description" id="agent_description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                              placeholder="Détails du versement..."></textarea>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white py-2 px-4 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <i class="fas fa-hand-holding-dollar mr-2"></i>
                    Effectuer le Versement
                </button>
            </form>
        </div>

        <!-- Versement Superviseur -->
        @if(in_array(Auth::user()->role, ['supervisor', 'admin']))
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-green-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-user-tie text-green-600 mr-2"></i>
                    Versement Superviseur (Wizall → Wizall + Dette)
                </h3>
                <p class="text-sm text-gray-600 mt-1">Augmente Wizall temps réel + ce que l'agent doit rendre</p>
            </div>
            <form action="{{ route('deposits.supervisor-deposit') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="supervisor_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="amount" id="supervisor_amount" min="1" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div class="mb-4">
                    <label for="supervisor_description" class="block text-sm font-medium text-gray-700 mb-2">Description (optionnel)</label>
                    <textarea name="description" id="supervisor_description" rows="2"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-green-500"
                              placeholder="Détails du versement..."></textarea>
                </div>
                <button type="submit" class="w-full bg-green-600 text-white py-2 px-4 rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <i class="fas fa-university mr-2"></i>
                    Effectuer le Versement Superviseur
                </button>
            </form>
        </div>
        @endif
    </div>

    <!-- Autres opérations -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
        <!-- Récupération Espèces -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-orange-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-money-bill-wave text-orange-600 mr-2"></i>
                    Récupération d'Espèces
                </h3>
                <p class="text-sm text-gray-600 mt-1">Sortie d'espèces de la caisse</p>
            </div>
            <form action="{{ route('deposits.cash-collection') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="cash_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="amount" id="cash_amount" min="1" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500">
                </div>
                <div class="mb-4">
                    <label for="cash_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="cash_description" rows="2" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-orange-500"
                              placeholder="Raison de la récupération..."></textarea>
                </div>
                <button type="submit" class="w-full bg-orange-600 text-white py-2 px-4 rounded-md hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500">
                    <i class="fas fa-hand-holding-dollar mr-2"></i>
                    Récupérer Espèces
                </button>
            </form>
        </div>

        <!-- Rechargement Wizall -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 bg-purple-50">
                <h3 class="text-lg font-medium text-gray-900">
                    <i class="fas fa-credit-card text-purple-600 mr-2"></i>
                    Rechargement Wizall
                </h3>
                <p class="text-sm text-gray-600 mt-1">Ajout externe au solde Wizall</p>
            </div>
            <form action="{{ route('deposits.wizall-refill') }}" method="POST" class="p-6">
                @csrf
                <div class="mb-4">
                    <label for="wizall_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA)</label>
                    <input type="number" name="amount" id="wizall_amount" min="1" step="1" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500">
                </div>
                <div class="mb-4">
                    <label for="wizall_description" class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                    <textarea name="description" id="wizall_description" rows="2" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-purple-500"
                              placeholder="Source du rechargement..."></textarea>
                </div>
                <button type="submit" class="w-full bg-purple-600 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                    <i class="fas fa-plus-circle mr-2"></i>
                    Recharger Wizall
                </button>
            </form>
        </div>
    </div>

    <!-- Historique récent -->
    <div class="bg-white shadow-lg rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">
                <i class="fas fa-clock mr-2"></i>
                Derniers Versements
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Heure</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Montant</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Impact</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="deposits-table">
                    @forelse($deposits as $deposit)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $deposit->created_at->format('H:i:s') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $deposit->user->name }}
                            <span class="text-xs text-gray-500">({{ $deposit->user->role }})</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @switch($deposit->deposit_type)
                                @case('agent_cash_deposit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Agent
                                    </span>
                                @break
                                @case('supervisor_wizall_deposit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                        Superviseur
                                    </span>
                                @break
                                @case('cash_collection')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                        Récupération
                                    </span>
                                @break
                                @case('wizall_refill')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                        Rechargement
                                    </span>
                                @break
                            @endswitch
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ number_format($deposit->amount, 0) }} FCFA
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">
                            {{ $deposit->description }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">
                            @if($deposit->affects_agent_return)
                                <span class="text-red-600 font-medium">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    Affecte dette agent
                                </span>
                            @else
                                <span class="text-gray-500">Normal</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                            Aucun versement aujourd'hui
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(Auth::user()->role === 'admin')
    <!-- Section Admin - Réinitialisation des balances -->
    <div class="mt-8 bg-red-50 border border-red-200 rounded-lg p-6">
        <h3 class="text-lg font-medium text-red-900 mb-4">
            <i class="fas fa-exclamation-triangle mr-2"></i>
            Zone Administrateur - Réinitialisation des Balances
        </h3>
        <form action="{{ route('deposits.reset-balance') }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir réinitialiser les balances ?')">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="wizall_amount" class="block text-sm font-medium text-red-700 mb-2">Nouveau solde Wizall</label>
                    <input type="number" name="wizall_amount" id="wizall_amount" min="0" step="1" required
                           class="w-full px-3 py-2 border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
                <div>
                    <label for="cash_amount" class="block text-sm font-medium text-red-700 mb-2">Nouveau solde Espèces</label>
                    <input type="number" name="cash_amount" id="cash_amount" min="0" step="1" required
                           class="w-full px-3 py-2 border border-red-300 rounded-md focus:outline-none focus:ring-2 focus:ring-red-500">
                </div>
            </div>
            <div class="mb-4">
                <label class="flex items-center">
                    <input type="checkbox" name="confirm" value="1" required class="mr-2">
                    <span class="text-sm text-red-700">Je confirme vouloir réinitialiser les balances (action irréversible)</span>
                </label>
            </div>
            <button type="submit" class="bg-red-600 text-white py-2 px-4 rounded-md hover:bg-red-700">
                <i class="fas fa-redo mr-2"></i>
                Réinitialiser les Balances
            </button>
        </form>
    </div>
    @endif
</div>

<script>
function refreshBalances() {
    fetch('{{ route("deposits.stats") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const balance = data.balance_data;
                
                // Mettre à jour les éléments de balance
                document.getElementById('cash-balance').textContent = formatNumber(balance.cash_balance) + ' FCFA';
                document.getElementById('wizall-balance').textContent = formatNumber(balance.wizall_current_balance) + ' FCFA';
                document.getElementById('wave-balance').textContent = formatNumber(balance.wave_final_balance) + ' FCFA';
                document.getElementById('agent-return').textContent = formatNumber(balance.agent_return_amount) + ' FCFA';
                document.getElementById('total-deposits').textContent = balance.deposits_summary.deposits_count;
                
                // Alertes de balance faible
                const wizallElement = document.getElementById('wizall-balance');
                if (balance.wizall_current_balance < 50000) {
                    wizallElement.className = 'text-2xl font-bold text-red-600';
                } else {
                    wizallElement.className = 'text-2xl font-bold text-gray-900';
                }
                
                console.log('Balances mises à jour:', data.updated_at);
            } else {
                console.error('Erreur lors de la mise à jour:', data.error);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
        });
}

function formatNumber(num) {
    return new Intl.NumberFormat('fr-FR').format(Math.round(num));
}

// Auto-refresh toutes les 30 secondes
setInterval(refreshBalances, 30000);
</script>
@endsection 