@extends('layouts.app')

@section('title', 'Payer une facture')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4">
                <h1 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-file-invoice mr-3"></i>
                    Payer une facture
                </h1>
                </div>
                
            <div class="p-6">
                <p class="text-gray-600 mb-6">
                        Veuillez remplir les informations de votre facture ou télécharger une photo 
                        pour extraction automatique des données.
                    </p>
                    
                    <!-- OCR Section -->
                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <!-- Option 1: Upload -->
                    <div class="border border-blue-200 rounded-lg overflow-hidden">
                        <div class="bg-blue-50 px-4 py-3 border-b border-blue-200">
                            <h3 class="text-sm font-semibold text-blue-800 flex items-center">
                                <i class="fas fa-camera mr-2"></i>
                                Option 1: Upload de facture
                            </h3>
                                </div>
                        <div class="p-4 text-center">
                            <p class="text-gray-600 text-sm mb-4">
                                        Prenez une photo claire de votre facture pour extraction automatique
                                    </p>
                            <input type="file" id="billImageUpload" accept="image/*" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg mb-4 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" id="extractBillData" 
                                    class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition duration-200 disabled:opacity-50 disabled:cursor-not-allowed" 
                                    disabled>
                                <i class="fas fa-magic mr-2"></i>Extraire les données
                                    </button>
                            <div id="extractionLoader" class="mt-4 hidden">
                                <div class="flex justify-center">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                                        </div>
                                <p class="text-gray-600 mt-2 text-sm">Analyse de la facture en cours...</p>
                                </div>
                            </div>
                        </div>
                        
                    <!-- Option 2: Manual -->
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="bg-gray-50 px-4 py-3 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-keyboard mr-2"></i>
                                Option 2: Saisie manuelle
                            </h3>
                                </div>
                        <div class="p-4 text-center">
                            <p class="text-gray-600 text-sm mb-4">
                                        Remplissez directement le formulaire ci-dessous
                                    </p>
                            <button type="button" id="manualEntry" 
                                    class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition duration-200">
                                <i class="fas fa-edit mr-2"></i>Saisie manuelle
                                    </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form -->
                <form action="{{ route('bills.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                    <div class="grid md:grid-cols-2 gap-6">
                        <!-- Entreprise -->
                        <div>
                            <label for="company_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Entreprise <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="company_name" id="company_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('company_name') border-red-500 @enderror" 
                                   value="{{ old('company_name') }}" required readonly>
                            @error('company_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Numéro de facture -->
                        <div>
                            <label for="bill_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de facture <span class="text-red-500">*</span>
                            </label>
                                <input type="text" name="bill_number" id="bill_number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('bill_number') border-red-500 @enderror" 
                                       value="{{ old('bill_number') }}" required>
                                @error('bill_number')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            
                        <!-- Numéro client -->
                        <div>
                            <label for="client_number" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="client_number" id="client_number" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_number') border-red-500 @enderror" 
                                   value="{{ old('client_number') }}" required>
                            @error('client_number')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Nom du client -->
                        <div>
                            <label for="client_name" class="block text-sm font-medium text-gray-700 mb-2">
                                Nom du client <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="client_name" id="client_name" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('client_name') border-red-500 @enderror" 
                                   value="{{ old('client_name') }}" required placeholder="Nom complet du client">
                            @error('client_name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Numéro de téléphone -->
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                Numéro de téléphone <span class="text-red-500">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('phone') border-red-500 @enderror" 
                                   value="{{ old('phone') }}" required placeholder="Ex: +221 77 123 45 67">
                            @error('phone')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <!-- Montant -->
                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700 mb-2">
                                Montant TTC (FCFA) <span class="text-red-500">*</span>
                            </label>
                            <input type="number" name="amount" id="amount" 
                                   class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('amount') border-red-500 @enderror" 
                                   value="{{ old('amount') }}" min="0" step="0.01" required>
                            @error('amount')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                    
                    <!-- Fichier -->
                    <div>
                        <label for="uploaded_file" class="block text-sm font-medium text-gray-700 mb-2">
                            Fichier de la facture (optionnel)
                        </label>
                        <input type="file" name="uploaded_file" id="uploaded_file" 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 @error('uploaded_file') border-red-500 @enderror" 
                               accept=".pdf,.jpg,.jpeg,.png">
                        <p class="text-gray-500 text-sm mt-1">Formats acceptés: PDF, JPG, PNG (max 2MB)</p>
                        @error('uploaded_file')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <!-- Buttons -->
                    <div class="border-t border-gray-200 pt-6 flex flex-col sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('home') }}" 
                           class="inline-flex items-center justify-center px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-200">
                            <i class="fas fa-arrow-left mr-2"></i>Retour
                            </a>
                        <button type="submit" 
                                class="inline-flex items-center justify-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200">
                            <i class="fas fa-paper-plane mr-2"></i>Soumettre la demande
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enable extract button when file is selected
    const billImageUpload = document.getElementById('billImageUpload');
    const extractBillData = document.getElementById('extractBillData');
    
    billImageUpload.addEventListener('change', function() {
        const file = this.files[0];
        extractBillData.disabled = !file;
    });
    
    // Manual entry button
    document.getElementById('manualEntry').addEventListener('click', function() {
        // Just scroll to form
        document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
        document.getElementById('company_name').focus();
    });
    
    // Extract bill data
    extractBillData.addEventListener('click', function() {
        const fileInput = document.getElementById('billImageUpload');
        const file = fileInput.files[0];
        
        if (!file) {
            alert('Veuillez sélectionner une image ou un PDF de facture');
            return;
        }
        
        const formData = new FormData();
        formData.append('bill_image', file);
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
        
        // Show loader
        document.getElementById('extractionLoader').classList.remove('hidden');
        extractBillData.disabled = true;
        
        fetch('{{ route("ocr.extract-bill") }}', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Fill form with extracted data
                if (data.data.company_name) {
                    // Try to match company name
                    const companyNameInput = document.getElementById('company_name');
                    companyNameInput.value = data.data.company_name;
                }
                if (data.data.bill_number) {
                    document.getElementById('bill_number').value = data.data.bill_number;
                }
                if (data.data.client_number) {
                    document.getElementById('client_number').value = data.data.client_number;
                }
                if (data.data.client_name) {
                    document.getElementById('client_name').value = data.data.client_name;
                }
                if (data.data.amount) {
                    document.getElementById('amount').value = data.data.amount;
                }
                alert('Données extraites avec succès !');
            } else {
                alert('Erreur lors de l\'extraction: ' + (data.message || 'Erreur inconnue'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Erreur lors de l\'extraction des données');
        })
        .finally(() => {
            // Hide loader
            document.getElementById('extractionLoader').classList.add('hidden');
            extractBillData.disabled = false;
        });
    });
});
</script>
@endsection 