@extends('layouts.app')

@section('title', 'Paiement de Facture')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="bg-white rounded-lg shadow-lg p-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Paiement de Facture</h1>
            <div class="h-1 w-20 bg-blue-600"></div>
        </div>

        <!-- Informations de la facture -->
        <div class="bg-blue-50 rounded-lg p-6 mb-8">
            <h2 class="text-xl font-semibold text-blue-900 mb-4">Détails de la facture</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <span class="text-sm font-medium text-blue-700">Numéro de facture :</span>
                    <span class="block text-lg font-bold text-blue-900">{{ $bill->bill_number }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-blue-700">Entreprise :</span>
                    <span class="block text-lg font-bold text-blue-900">{{ $bill->company->name }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-blue-700">Client :</span>
                    <span class="block text-lg font-bold text-blue-900">{{ $bill->user->name ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-sm font-medium text-blue-700">Montant :</span>
                    <span class="block text-2xl font-bold text-green-600">{{ number_format($bill->amount, 0, ' ') }} FCFA</span>
                </div>
            </div>
        </div>

        <form action="{{ route('payments.store') }}" method="POST" enctype="multipart/form-data" id="paymentForm">
            @csrf
            <input type="hidden" name="bill_id" value="{{ $bill->id }}">

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Formulaire de paiement -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-900">Informations de paiement</h3>

                    <!-- Nom du client -->
                    <div>
                        <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nom du client <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="client_name" name="client_name"
                               value="{{ old('client_name', $bill->client_name ?? $bill->user->name ?? '') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('client_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Méthode de paiement -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 mb-2">
                            Méthode de paiement <span class="text-red-500">*</span>
                        </label>
                        <select id="payment_method" name="payment_method"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                required onchange="toggleCashFields()">
                            <option value="">Sélectionner une méthode</option>
                            <option value="wizall" {{ old('payment_method') == 'wizall' ? 'selected' : '' }}>Wizall</option>
                            <option value="wave" {{ old('payment_method') == 'wave' ? 'selected' : '' }}>Wave</option>
                            <option value="orange_money" {{ old('payment_method') == 'orange_money' ? 'selected' : '' }}>Orange Money</option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                        </select>
                        @error('payment_method')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Champs spécifiques aux espèces -->
                    <div id="cashFields" class="hidden space-y-4 bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
                        <h4 class="text-lg font-semibold text-yellow-800">Paiement en espèces</h4>

                        <div>
                            <label for="amount_received" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant reçu <span class="text-red-500">*</span>
                            </label>
                            <input type="number" id="amount_received" name="amount_received"
                                   value="{{ old('amount_received') }}"
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                                   step="0.01" min="0" onchange="calculateChange()">
                            @error('amount_received')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="change_method" class="block text-sm font-medium text-gray-700 mb-2">
                                Méthode de rendu de monnaie <span class="text-red-500">*</span>
                            </label>
                            <select id="change_method" name="change_method"
                                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                                <option value="">Sélectionner une méthode</option>
                                <option value="cash" {{ old('change_method') == 'cash' ? 'selected' : '' }}>Espèces</option>
                                <option value="wave" {{ old('change_method') == 'wave' ? 'selected' : '' }}>Wave</option>
                                <option value="om" {{ old('change_method') == 'om' ? 'selected' : '' }}>Orange Money</option>
                            </select>
                            @error('change_method')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Affichage du calcul de monnaie -->
                        <div id="changeDisplay" class="hidden bg-green-50 p-4 rounded-lg border border-green-200">
                            <div class="flex justify-between items-center">
                                <span class="text-sm font-medium text-green-700">Monnaie à rendre :</span>
                                <span id="changeAmount" class="text-xl font-bold text-green-600">0 FCFA</span>
                            </div>
                        </div>
                    </div>

                    <!-- Référence de transaction -->
                    <div>
                        <label for="transaction_reference" class="block text-sm font-medium text-gray-700 mb-2">
                            Référence de transaction <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="transaction_reference" name="transaction_reference"
                               value="{{ old('transaction_reference') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('transaction_reference')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Type de transaction -->
                    <div>
                        <label for="transaction_type" class="block text-sm font-medium text-gray-700 mb-2">
                            Type de transaction <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="transaction_type" name="transaction_type"
                               value="{{ old('transaction_type', 'Paiement facture') }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('transaction_type')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date de transaction -->
                    <div>
                        <label for="transaction_date" class="block text-sm font-medium text-gray-700 mb-2">
                            Date de transaction <span class="text-red-500">*</span>
                        </label>
                        <input type="datetime-local" id="transaction_date" name="transaction_date"
                               value="{{ old('transaction_date', now()->format('Y-m-d\TH:i')) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               required>
                        @error('transaction_date')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Détails financiers et preuve -->
                <div class="space-y-6">
                    <h3 class="text-xl font-semibold text-gray-900">Détails financiers</h3>

                    <!-- Montant -->
                    <div>
                        <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                            Montant de la facture <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="amount" name="amount"
                               value="{{ old('amount', $bill->amount) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               step="0.01" min="0" required readonly>
                        @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Frais -->
                    <div>
                        <label for="fees" class="block text-sm font-medium text-gray-700 mb-2">
                            Frais de service (1%) <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="fees" name="fees"
                               value="{{ old('fees', $bill->amount * 0.01) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               step="0.01" min="0" required readonly>
                        <p class="text-sm text-gray-500 mt-1">Calculé automatiquement à 1% du montant</p>
                        @error('fees')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Total -->
                    <div>
                        <label for="total" class="block text-sm font-medium text-gray-700 mb-2">
                            Total à payer <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="total" name="total"
                               value="{{ old('total', $bill->amount + ($bill->amount * 0.01)) }}"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200"
                               step="0.01" min="0" required readonly>
                        <p class="text-sm text-gray-500 mt-1">Montant + Frais de service</p>
                        @error('total')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Preuve de paiement -->
                    <div>
                        <label for="proof_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Preuve de paiement <span class="text-green-600">(Recommandé)</span>
                        </label>
                        <input type="file" id="proof_image" name="proof_image"
                               accept="image/*"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">

                        <!-- Instructions for OCR -->
                        <div class="mt-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="text-sm font-semibold text-blue-800 mb-2 flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Extraction automatique des données
                            </h4>
                            <p class="text-sm text-blue-700 mb-2">
                                Téléchargez une capture d'écran claire de la transaction pour extraction automatique de :
                            </p>
                            <ul class="text-sm text-blue-600 space-y-1">
                                <li>• <strong>Référence de transaction</strong> (ex: 354450982)</li>
                                <li>• <strong>Date et heure</strong> (ex: 05 février 2025 à 09h35min)</li>
                                <li>• <strong>Montant</strong> (ex: 368 175 FCFA)</li>
                                <li>• <strong>Frais</strong> (ex: 2945.40 FCFA)</li>
                                <li>• <strong>Numéro client</strong> (ex: 770887000)</li>
                            </ul>

                            <!-- Extract button -->
                            <button type="button" id="extractDataBtn"
                                    class="mt-3 w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
                                    disabled>
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                </svg>
                                Extraire les données automatiquement
                            </button>

                            <!-- Loading state -->
                            <div id="extractingLoader" class="mt-3 hidden">
                                <div class="flex items-center justify-center">
                                    <div class="animate-spin rounded-full h-5 w-5 border-b-2 border-blue-600 mr-2"></div>
                                    <span class="text-sm text-blue-700">Analyse de l'image en cours...</span>
                                </div>
                            </div>

                            <div class="mt-3 p-2 bg-blue-100 rounded border border-blue-300">
                                <p class="text-xs text-blue-800">
                                    💡 <strong>Astuce :</strong> Assurez-vous que le texte soit lisible et que l'image soit bien éclairée pour une meilleure extraction.
                                </p>
                            </div>
                        </div>

                        @error('proof_image')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Options d'envoi -->
                    <div class="bg-gray-50 p-4 rounded-lg">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Options d'envoi du reçu</h4>

                        <div class="space-y-4">
                            <div class="flex items-center">
                                <input type="checkbox" id="send_email" name="send_email" value="1"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       onchange="toggleEmailField()">
                                <label for="send_email" class="ml-2 text-sm text-gray-700">
                                    Envoyer par email
                                </label>
                            </div>

                            <div id="emailField" class="hidden">
                                <input type="email" name="client_email" placeholder="Email du client"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>

                            <div class="flex items-center">
                                <input type="checkbox" id="send_whatsapp" name="send_whatsapp" value="1"
                                       class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded"
                                       onchange="togglePhoneField()">
                                <label for="send_whatsapp" class="ml-2 text-sm text-gray-700">
                                    Envoyer par WhatsApp
                                </label>
                            </div>

                            <div id="phoneField" class="hidden">
                                <input type="tel" name="client_phone" placeholder="Numéro de téléphone"
                                       class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Boutons d'action -->
            <div class="mt-8 flex flex-col sm:flex-row gap-4 justify-end">
                <a href="{{ route('user.dashboard') }}"
                   class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200 text-center">
                    Annuler
                </a>
                <button type="submit"
                        class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 font-semibold">
                    Enregistrer le paiement
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function toggleCashFields() {
    const paymentMethod = document.getElementById('payment_method').value;
    const cashFields = document.getElementById('cashFields');
    const amountReceived = document.getElementById('amount_received');
    const changeMethod = document.getElementById('change_method');

    if (paymentMethod === 'cash') {
        cashFields.classList.remove('hidden');
        amountReceived.required = true;
        changeMethod.required = true;
    } else {
        cashFields.classList.add('hidden');
        amountReceived.required = false;
        changeMethod.required = false;
        document.getElementById('changeDisplay').classList.add('hidden');
    }
}

function calculateChange() {
    const total = parseFloat(document.getElementById('total').value) || 0;
    const received = parseFloat(document.getElementById('amount_received').value) || 0;
    const changeDisplay = document.getElementById('changeDisplay');
    const changeAmount = document.getElementById('changeAmount');

    if (received > 0 && total > 0) {
        const change = Math.max(0, received - total);
        changeAmount.textContent = new Intl.NumberFormat('fr-FR').format(change) + ' FCFA';

        if (change > 0) {
            changeDisplay.classList.remove('hidden');
        } else {
            changeDisplay.classList.add('hidden');
        }
    } else {
        changeDisplay.classList.add('hidden');
    }
}

function toggleEmailField() {
    const checkbox = document.getElementById('send_email');
    const field = document.getElementById('emailField');

    if (checkbox.checked) {
        field.classList.remove('hidden');
        field.querySelector('input').required = true;
    } else {
        field.classList.add('hidden');
        field.querySelector('input').required = false;
    }
}

function togglePhoneField() {
    const checkbox = document.getElementById('send_whatsapp');
    const field = document.getElementById('phoneField');

    if (checkbox.checked) {
        field.classList.remove('hidden');
        field.querySelector('input').required = true;
    } else {
        field.classList.add('hidden');
        field.querySelector('input').required = false;
    }
}

// Initialiser les fonctions au chargement de la page
document.addEventListener('DOMContentLoaded', function() {
    toggleCashFields();

    // Enable extract button when proof image is selected
    const proofImage = document.getElementById('proof_image');
    const extractDataBtn = document.getElementById('extractDataBtn');

    if (proofImage && extractDataBtn) {
        proofImage.addEventListener('change', function() {
            const file = this.files[0];
            extractDataBtn.disabled = !file;
        });

        // Extract data from image
        extractDataBtn.addEventListener('click', function() {
            const file = proofImage.files[0];
            if (!file) return;

            const loader = document.getElementById('extractingLoader');
            extractDataBtn.style.display = 'none';
            loader.classList.remove('hidden');

            // Simulate OCR extraction (in real implementation, this would call an API)
            setTimeout(() => {
                extractMockData();
                loader.classList.add('hidden');
                extractDataBtn.style.display = 'flex';

                // Show success message
                showExtractionSuccess();
            }, 3000);
        });
    }
});

function extractMockData() {
    // Mock data extraction based on the image provided
    const mockData = {
        transaction_reference: '354450982',
        transaction_date: '2025-02-05T09:35',
        transaction_type: 'CMA CGM',
        client_phone: '770887000',
        // amount and fees would be calculated from the extracted amount
    };

    // Fill the form with extracted data
    if (document.getElementById('transaction_reference')) {
        document.getElementById('transaction_reference').value = mockData.transaction_reference;
    }
    if (document.getElementById('transaction_date')) {
        document.getElementById('transaction_date').value = mockData.transaction_date;
    }
    if (document.getElementById('transaction_type')) {
        document.getElementById('transaction_type').value = mockData.transaction_type;
    }

    // Fill phone in options if available
    const clientPhoneField = document.querySelector('input[name="client_phone"]');
    if (clientPhoneField) {
        clientPhoneField.value = mockData.client_phone;
    }
}

function showExtractionSuccess() {
    // Create and show success notification
    const successDiv = document.createElement('div');
    successDiv.className = 'fixed top-4 right-4 bg-green-100 border border-green-400 text-green-700 px-6 py-4 rounded-lg shadow-lg z-50 flex items-center';
    successDiv.innerHTML = `
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <span>Données extraites avec succès ! Vérifiez les champs remplis.</span>
        <button onclick="this.parentElement.remove()" class="ml-4 text-green-800 hover:text-green-900">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;

    document.body.appendChild(successDiv);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (successDiv.parentElement) {
            successDiv.remove();
        }
    }, 5000);
}
</script>
@endsection
