@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="bg-white shadow-lg rounded-lg p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-800">Gestion des Soldes</h1>
            <div class="text-sm text-gray-600">
                {{ now()->format('d/m/Y H:i') }}
            </div>
        </div>

        {{-- Messages de succès/erreur --}}
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        {{-- Soldes du jour --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            {{-- Wizall --}}
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <h3 class="text-lg font-semibold text-blue-800 mb-2">Wizall</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Solde de départ:</span>
                        <span class="font-medium">{{ number_format($todayBalance->wizall_start_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Solde courant:</span>
                        <span class="font-medium text-blue-600">{{ number_format($todayBalance->wizall_current_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Solde final:</span>
                        <span class="font-medium">{{ number_format($todayBalance->wizall_final_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Wave --}}
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <h3 class="text-lg font-semibold text-green-800 mb-2">Wave</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Solde de départ:</span>
                        <span class="font-medium">{{ number_format($todayBalance->wave_start_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Solde final:</span>
                        <span class="font-medium text-green-600">{{ number_format($todayBalance->wave_final_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Orange Money --}}
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
                <h3 class="text-lg font-semibold text-orange-800 mb-2">Orange Money</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Solde disponible:</span>
                        <span class="font-medium text-orange-600">{{ number_format($todayBalance->orange_money_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            {{-- Cash --}}
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Cash</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>Solde disponible:</span>
                        <span class="font-medium text-gray-600">{{ number_format($todayBalance->cash_balance ?? 0, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Total à rendre --}}
        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-8">
            <div class="flex justify-between items-center">
                <span class="text-lg font-semibold text-yellow-800">Total à rendre au superviseur:</span>
                <span class="text-2xl font-bold text-yellow-600">{{ number_format($todayBalance->total_to_return ?? 0, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        {{-- Actions rapides --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            {{-- Initialiser la journée --}}
            <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200">
                <h3 class="text-lg font-semibold text-indigo-800 mb-3">Initialiser la journée</h3>
                <form action="{{ route('balances.initialize') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full bg-indigo-600 text-white px-4 py-2 rounded hover:bg-indigo-700 transition duration-200">
                        Initialiser
                    </button>
                </form>
            </div>

            {{-- Mettre à jour les soldes --}}
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                <h3 class="text-lg font-semibold text-purple-800 mb-3">Mettre à jour les soldes</h3>
                <button onclick="openBalanceModal()" class="w-full bg-purple-600 text-white px-4 py-2 rounded hover:bg-purple-700 transition duration-200">
                    Modifier les soldes
                </button>
            </div>

            {{-- Versements --}}
            <div class="bg-teal-50 p-4 rounded-lg border border-teal-200">
                <h3 class="text-lg font-semibold text-teal-800 mb-3">Effectuer un versement</h3>
                <button onclick="openDepositModal()" class="w-full bg-teal-600 text-white px-4 py-2 rounded hover:bg-teal-700 transition duration-200">
                    Nouveau versement
                </button>
            </div>
        </div>

        {{-- Versement superviseur (si utilisateur est superviseur) --}}
        @if(auth()->user()->isSupervisor())
        <div class="bg-red-50 border border-red-200 rounded-lg p-4 mb-8">
            <h3 class="text-lg font-semibold text-red-800 mb-3">Versement Superviseur</h3>
            <button onclick="openSupervisorDepositModal()" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 transition duration-200">
                Versement Superviseur
            </button>
        </div>
        @endif
    </div>
</div>

{{-- Modal pour mettre à jour les soldes --}}
<div id="balanceModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Mettre à jour les soldes</h3>
            <form action="{{ route('balances.update') }}" method="POST">
                @csrf
                @method('PATCH')
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Solde Wave de départ</label>
                    <input type="number" step="0.01" name="wave_start_balance" value="{{ $todayBalance->wave_start_balance ?? 0 }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Solde Orange Money</label>
                    <input type="number" step="0.01" name="orange_money_balance" value="{{ $todayBalance->orange_money_balance ?? 0 }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Solde Cash</label>
                    <input type="number" step="0.01" name="cash_balance" value="{{ $todayBalance->cash_balance ?? 0 }}" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeBalanceModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Mettre à jour
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour les versements --}}
<div id="depositModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Effectuer un versement</h3>
            <form action="{{ route('balances.deposit') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                    <input type="number" step="0.01" name="amount" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Depuis le compte</label>
                    <select name="from_account" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                        <option value="">Sélectionner...</option>
                        <option value="cash">Cash</option>
                        <option value="wizall">Wizall</option>
                        <option value="wave">Wave</option>
                        <option value="orange_money">Orange Money</option>
                    </select>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeDepositModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-teal-600 text-white rounded-md hover:bg-teal-700">
                        Effectuer le versement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal pour versement superviseur --}}
@if(auth()->user()->isSupervisor())
<div id="supervisorDepositModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <h3 class="text-lg font-bold text-gray-900 mb-4">Versement Superviseur</h3>
            <p class="text-sm text-red-600 mb-4">Ce montant devra être rendu au superviseur en fin de journée.</p>
            <form action="{{ route('balances.supervisor-deposit') }}" method="POST">
                @csrf
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Montant</label>
                    <input type="number" step="0.01" name="amount" min="1" 
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500" required>
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description (optionnel)</label>
                    <textarea name="description" rows="2" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:border-blue-500"></textarea>
                </div>
                
                <div class="flex justify-end space-x-2">
                    <button type="button" onclick="closeSupervisorDepositModal()" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Effectuer le versement
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

<script>
function openBalanceModal() {
    document.getElementById('balanceModal').classList.remove('hidden');
}

function closeBalanceModal() {
    document.getElementById('balanceModal').classList.add('hidden');
}

function openDepositModal() {
    document.getElementById('depositModal').classList.remove('hidden');
}

function closeDepositModal() {
    document.getElementById('depositModal').classList.add('hidden');
}

@if(auth()->user()->isSupervisor())
function openSupervisorDepositModal() {
    document.getElementById('supervisorDepositModal').classList.remove('hidden');
}

function closeSupervisorDepositModal() {
    document.getElementById('supervisorDepositModal').classList.add('hidden');
}
@endif

// Fermer les modals en cliquant à l'extérieur
window.onclick = function(event) {
    const balanceModal = document.getElementById('balanceModal');
    const depositModal = document.getElementById('depositModal');
    @if(auth()->user()->isSupervisor())
    const supervisorDepositModal = document.getElementById('supervisorDepositModal');
    @endif
    
    if (event.target === balanceModal) {
        closeBalanceModal();
    }
    if (event.target === depositModal) {
        closeDepositModal();
    }
    @if(auth()->user()->isSupervisor())
    if (event.target === supervisorDepositModal) {
        closeSupervisorDepositModal();
    }
    @endif
}
</script>
@endsection 