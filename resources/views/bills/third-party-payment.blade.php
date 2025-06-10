@extends('layouts.app')

@section('title', 'Paiement pour Client Tiers')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Paiement pour Client Tiers</h1>
            <p class="text-gray-600">Créer et payer une facture pour un client</p>
        </div>
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isSupervisor() ? route('supervisor.dashboard') : route('agent.dashboard')) }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
            Retour Dashboard
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire principal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-6">Informations de la facture</h2>
                
                <form id="thirdPartyForm" action="{{ route('third-party.process') }}" method="POST">
                    @csrf
                    
                    <!-- Recherche client existant -->
                    <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                        <h3 class="text-lg font-medium text-blue-900 mb-3">Rechercher un client existant</h3>
                        <div class="flex space-x-3">
                            <input type="text" id="client-search" 
                                   placeholder="Nom, email ou téléphone du client..."
                                   class="flex-1 border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" onclick="searchClient()" 
                                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                                Rechercher
                            </button>
                        </div>
                        <div id="search-results" class="mt-3 space-y-2"></div>
                        <input type="hidden" id="existing_client_id" name="existing_client_id">
                    </div>

                    <!-- Informations client -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom du client *</label>
                            <input type="text" name="client_name" id="client_name" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email du client *</label>
                            <input type="email" name="client_email" id="client_email" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Téléphone du client *</label>
                            <input type="tel" name="client_phone" id="client_phone" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nom de l'entreprise *</label>
                            <input type="text" name="company_name" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Informations facture -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Facturier *</label>
                            <select name="facturier" required
                                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                <option value="">Sélectionner un facturier</option>
                                <option value="CMA CGM">CMA CGM</option>
                                <option value="RAPIDOSTAR">RAPIDOSTAR</option>
                                <option value="TIME">TIME</option>
                                <option value="SDPWORLD">SDPWORLD</option>
                                <option value="COSEC">COSEC</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Montant (FCFA) *</label>
                            <input type="number" name="amount" step="0.01" min="0.01" required
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Date d'échéance *</label>
                            <input type="date" name="due_date" required
                                   min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description *</label>
                        <textarea name="description" rows="4" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez les services ou produits facturés..."></textarea>
                    </div>

                    <button type="submit" 
                            class="w-full bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 transition-colors font-medium">
                        Créer la facture et procéder au paiement
                    </button>
                </form>
            </div>
        </div>

        <!-- Informations et aide -->
        <div class="lg:col-span-1">
            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-yellow-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Information importante
                </h3>
                <div class="text-sm text-yellow-700 space-y-2">
                    <p>• Vous payez cette facture pour le compte d'un client</p>
                    <p>• Le client recevra la facture dans son tableau de bord</p>
                    <p>• Si le client n'a pas de compte, il sera créé automatiquement</p>
                    <p>• Le client sera notifié par email</p>
                </div>
            </div>

            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    Étapes du processus
                </h3>
                <div class="text-sm text-green-700 space-y-2">
                    <p>1. Vérification/création du compte client</p>
                    <p>2. Création de la facture</p>
                    <p>3. Traitement du paiement</p>
                    <p>4. Notification du client</p>
                    <p>5. Génération du reçu</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let searchTimeout;

function searchClient() {
    const search = document.getElementById('client-search').value.trim();
    
    if (search.length < 2) {
        alert('Veuillez saisir au moins 2 caractères');
        return;
    }
    
    // Annuler la recherche précédente
    clearTimeout(searchTimeout);
    
    searchTimeout = setTimeout(() => {
        fetch('{{ route("bills.third-party.search-client") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ search: search })
        })
        .then(response => response.json())
        .then(clients => {
            displaySearchResults(clients);
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de la recherche');
        });
    }, 300);
}

function displaySearchResults(clients) {
    const resultsDiv = document.getElementById('search-results');
    
    if (clients.length === 0) {
        resultsDiv.innerHTML = '<p class="text-sm text-gray-500">Aucun client trouvé</p>';
        return;
    }
    
    resultsDiv.innerHTML = clients.map(client => `
        <div class="bg-white border border-gray-200 rounded p-3 cursor-pointer hover:bg-gray-50"
             onclick="selectClient(${client.id}, '${client.name}', '${client.email}', '${client.phone || ''}')">
            <div class="font-medium text-gray-900">${client.name}</div>
            <div class="text-sm text-gray-600">${client.email}</div>
            ${client.phone ? `<div class="text-sm text-gray-600">${client.phone}</div>` : ''}
        </div>
    `).join('');
}

function selectClient(id, name, email, phone) {
    document.getElementById('existing_client_id').value = id;
    document.getElementById('client_name').value = name;
    document.getElementById('client_email').value = email;
    document.getElementById('client_phone').value = phone || '';
    
    // Rendre les champs en lecture seule
    document.getElementById('client_name').readOnly = true;
    document.getElementById('client_email').readOnly = true;
    if (phone) {
        document.getElementById('client_phone').readOnly = true;
    }
    
    // Effacer les résultats
    document.getElementById('search-results').innerHTML = '';
    document.getElementById('client-search').value = '';
    
    // Ajouter un indicateur visuel
    ['client_name', 'client_email', 'client_phone'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field.value) {
            field.classList.add('bg-green-50', 'border-green-300');
        }
    });
}

// Réinitialiser si l'utilisateur modifie les champs
document.getElementById('client_name').addEventListener('input', resetClientSelection);
document.getElementById('client_email').addEventListener('input', resetClientSelection);
document.getElementById('client_phone').addEventListener('input', resetClientSelection);

function resetClientSelection() {
    document.getElementById('existing_client_id').value = '';
    
    // Retirer les indicateurs visuels et readonly
    ['client_name', 'client_email', 'client_phone'].forEach(fieldId => {
        const field = document.getElementById(fieldId);
        field.readOnly = false;
        field.classList.remove('bg-green-50', 'border-green-300');
    });
}

// Recherche en temps réel
document.getElementById('client-search').addEventListener('input', function() {
    const search = this.value.trim();
    if (search.length >= 2) {
        searchClient();
    } else {
        document.getElementById('search-results').innerHTML = '';
    }
});
</script>
@endsection 