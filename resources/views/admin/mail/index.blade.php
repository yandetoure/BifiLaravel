@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Emails</h1>
            <p class="text-gray-600">Envoi d'emails en masse aux clients</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Retour Dashboard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Envoi aux clients -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Envoyer à tous les clients</h2>
                <p class="text-gray-600">Notification générale pour tous les clients</p>
            </div>
            
            <form method="POST" action="{{ route('admin.mail.send-to-clients') }}" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                        <input type="text" name="subject" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Objet de votre email...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="8" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Votre message aux clients..."></textarea>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="send_copy_to_admin" value="1" class="rounded border-gray-300 text-blue-600">
                        <label class="ml-2 text-sm text-gray-600">M'envoyer une copie</label>
                    </div>

                    <div class="bg-blue-50 p-4 rounded-lg">
                        <div class="flex">
                            <svg class="w-5 h-5 text-blue-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div class="ml-3">
                                <p class="text-sm text-blue-700">
                                    Cet email sera envoyé à <strong>{{ $clients->count() }} clients</strong> enregistrés dans le système.
                                </p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        Envoyer à tous les clients ({{ $clients->count() }})
                    </button>
                </div>
            </form>
        </div>

        <!-- Envoi personnalisé -->
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Envoi personnalisé</h2>
                <p class="text-gray-600">Email à des destinataires spécifiques</p>
            </div>
            
            <form method="POST" action="{{ route('admin.mail.send-custom') }}" class="p-6">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destinataires</label>
                        <select name="recipients[]" multiple required class="w-full border border-gray-300 rounded-lg px-3 py-2 h-32">
                            @foreach($clients as $client)
                                <option value="{{ $client->email }}">{{ $client->name }} ({{ $client->email }})</option>
                            @endforeach
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs destinataires</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                        <input type="text" name="subject" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Objet de votre email...">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="6" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Votre message personnalisé..."></textarea>
                    </div>

                    <button type="submit" class="w-full bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                        Envoyer email personnalisé
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Historique des emails -->
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Historique des envois</h2>
        </div>
        
        <div class="p-6">
            @if($recentEmails && count($recentEmails) > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Destinataire
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Sujet
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Statut
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($recentEmails as $email)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $email->to_email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ Str::limit($email->subject, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $email->status === 'sent' ? 'bg-green-100 text-green-800' : 
                                       ($email->status === 'failed' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                    {{ $email->status === 'sent' ? 'Envoyé' : 
                                       ($email->status === 'failed' ? 'Échec' : 'En attente') }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ \Carbon\Carbon::parse($email->created_at)->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Aucun email envoyé</p>
                <p class="text-gray-400">L'historique des emails apparaîtra ici</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Templates d'emails prédéfinis -->
    <div class="mt-8 bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Templates prédéfinis</h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 cursor-pointer" onclick="useTemplate('welcome')">
                    <h3 class="font-semibold text-gray-900 mb-2">Bienvenue</h3>
                    <p class="text-sm text-gray-600">Message de bienvenue pour nouveaux clients</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 cursor-pointer" onclick="useTemplate('maintenance')">
                    <h3 class="font-semibold text-gray-900 mb-2">Maintenance</h3>
                    <p class="text-sm text-gray-600">Notification de maintenance programmée</p>
                </div>
                
                <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 cursor-pointer" onclick="useTemplate('update')">
                    <h3 class="font-semibold text-gray-900 mb-2">Mise à jour</h3>
                    <p class="text-sm text-gray-600">Annonce de nouvelles fonctionnalités</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
const templates = {
    welcome: {
        subject: "Bienvenue chez Bifi !",
        message: "Bonjour,\n\nNous sommes ravis de vous accueillir parmi nos clients !\n\nVotre compte a été créé avec succès et vous pouvez maintenant profiter de notre service de paiement de factures en ligne.\n\nN'hésitez pas à nous contacter si vous avez des questions.\n\nCordialement,\nL'équipe Bifi"
    },
    maintenance: {
        subject: "Maintenance programmée - Bifi",
        message: "Cher client,\n\nNous vous informons qu'une maintenance programmée aura lieu :\n\nDate : [DATE]\nHeure : [HEURE]\nDurée estimée : [DURÉE]\n\nDurant cette période, nos services pourront être temporairement indisponibles.\n\nNous nous excusons pour la gêne occasionnée.\n\nCordialement,\nL'équipe Bifi"
    },
    update: {
        subject: "Nouvelles fonctionnalités disponibles !",
        message: "Bonjour,\n\nNous sommes heureux de vous annoncer l'arrivée de nouvelles fonctionnalités sur votre plateforme Bifi :\n\n• [Fonctionnalité 1]\n• [Fonctionnalité 2]\n• [Fonctionnalité 3]\n\nConnectez-vous dès maintenant pour les découvrir !\n\nCordialement,\nL'équipe Bifi"
    }
};

function useTemplate(templateName) {
    const template = templates[templateName];
    if (template) {
        // Remplir le premier formulaire (envoi à tous)
        document.querySelector('input[name="subject"]').value = template.subject;
        document.querySelector('textarea[name="message"]').value = template.message;
        
        // Optionnellement, faire défiler jusqu'au formulaire
        document.querySelector('input[name="subject"]').scrollIntoView({ behavior: 'smooth' });
    }
}
</script>
@endsection 