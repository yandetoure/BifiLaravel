@extends('layouts.app')

@section('title', 'Support Client')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Support Client
            </h1>
            <p class="text-gray-600">Contactez notre équipe de support - Réponse garantie sous 24h</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('user.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Retour Dashboard
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Formulaire nouveau message -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-900 mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Nouveau Message
                </h2>
                
                <form method="POST" action="{{ route('client-chat.send') }}" id="messageForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Sujet</label>
                        <input type="text" name="subject" 
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                               placeholder="Objet de votre message..." required>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                        <select name="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                            <option value="normal">Normale</option>
                            <option value="urgent">Urgente</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">Utilisez "Urgente" uniquement pour les problèmes critiques</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="6"
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                  placeholder="Décrivez votre problème ou question..." required></textarea>
                    </div>

                    <!-- Zone de fichiers -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pièces jointes</label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4">
                            <input type="file" id="file-input" name="attachments[]" multiple 
                                   accept="image/*,video/*,audio/*,.pdf,.doc,.docx"
                                   class="hidden">
                            <div class="text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="mt-2">
                                    <button type="button" onclick="document.getElementById('file-input').click()" 
                                            class="text-blue-600 hover:text-blue-500">
                                        Cliquez pour ajouter des fichiers
                                    </button>
                                    <p class="text-xs text-gray-500">PNG, JPG, PDF, DOC jusqu'à 10MB</p>
                                </div>
                            </div>
                        </div>
                        <div id="file-preview" class="mt-3 space-y-2"></div>
                    </div>

                    <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                        <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                        Envoyer Message
                    </button>
                </form>
            </div>

            <!-- Info de contact -->
            <div class="bg-blue-50 rounded-lg p-6 mt-6">
                <h3 class="text-lg font-semibold text-blue-900 mb-3 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    Information
                </h3>
                <div class="space-y-2 text-sm text-blue-700">
                    <p>• Nous répondons généralement sous 24h</p>
                    <p>• Les messages urgents sont traités en priorité</p>
                    <p>• Vous pouvez joindre des fichiers (images, documents)</p>
                    <p>• Nos équipes répondent directement dans vos messages</p>
                </div>
            </div>
        </div>

        <!-- Historique des messages -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow">
                <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-900">Mes Messages</h2>
                    <div class="text-sm text-gray-500">
                        {{ $messages->total() }} message(s)
                    </div>
                </div>
                
                <div class="p-6 space-y-6">
                    @forelse($messages as $message)
                    <div class="border-l-4 border-l-{{ $message->priority === 'urgent' ? 'red' : 'blue' }}-500 bg-gray-50 rounded-r-lg overflow-hidden">
                        <!-- Message principal -->
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-3">
                                <div>
                                    <h3 class="font-semibold text-gray-900">{{ $message->subject ?: 'Sans sujet' }}</h3>
                                    <p class="text-sm text-gray-500">{{ $message->created_at->format('d/m/Y à H:i') }}</p>
                                </div>
                                <div class="flex space-x-2">
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                        {{ $message->priority === 'urgent' ? 'Urgent' : 'Normal' }}
                                    </span>
                                    <span class="px-2 py-1 text-xs rounded-full 
                                        {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                           ($message->status === 'replied' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                        {{ $message->status === 'pending' ? 'En attente' : 
                                           ($message->status === 'replied' ? 'Répondu' : 'Fermé') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="text-gray-700 mb-3">
                                @if(strlen($message->message) > 200)
                                    <div id="short-message-{{ $message->id }}">
                                        {{ Str::limit($message->message, 200) }}
                                        <button onclick="toggleMessage({{ $message->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm ml-2">
                                            Voir plus...
                                        </button>
                                    </div>
                                    <div id="full-message-{{ $message->id }}" class="hidden">
                                        {{ $message->message }}
                                        <button onclick="toggleMessage({{ $message->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm ml-2">
                                            Voir moins...
                                        </button>
                                    </div>
                                @else
                                    {{ $message->message }}
                                @endif
                            </div>

                            <!-- Pièces jointes du client -->
                            @if($message->attachments)
                                <div class="mt-3">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pièces jointes :</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($message->attachments as $attachment)
                                            @if($attachment['message_type'] === 'image')
                                                <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                     alt="{{ $attachment['original_name'] }}" 
                                                     class="w-20 h-20 object-cover rounded cursor-pointer"
                                                     onclick="openImageModal('{{ asset('storage/' . $attachment['path']) }}', '{{ $attachment['original_name'] }}')">
                                            @else
                                                <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                                   download="{{ $attachment['original_name'] }}"
                                                   class="flex items-center bg-white p-2 rounded border hover:bg-gray-50">
                                                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm">{{ $attachment['original_name'] }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Réponse du staff si elle existe -->
                        @if($message->staff_reply)
                            <div class="bg-green-50 border-t border-green-200 p-4">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="text-sm font-medium text-green-900">
                                                Réponse de {{ $message->repliedBy->name ?? 'Notre équipe' }}
                                            </h4>
                                            <span class="text-xs text-green-600">
                                                {{ $message->replied_at ? $message->replied_at->format('d/m/Y à H:i') : '' }}
                                            </span>
                                        </div>
                                        <p class="text-sm text-green-800">{{ $message->staff_reply }}</p>
                                        
                                        <!-- Pièces jointes de la réponse -->
                                        @if($message->reply_attachments)
                                            <div class="mt-3">
                                                <p class="text-sm font-medium text-green-700 mb-2">Fichiers joints :</p>
                                                <div class="flex flex-wrap gap-2">
                                                    @foreach($message->reply_attachments as $attachment)
                                                        @if($attachment['message_type'] === 'image')
                                                            <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                                 alt="{{ $attachment['original_name'] }}" 
                                                                 class="w-20 h-20 object-cover rounded cursor-pointer"
                                                                 onclick="openImageModal('{{ asset('storage/' . $attachment['path']) }}', '{{ $attachment['original_name'] }}')">
                                                        @else
                                                            <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                                               download="{{ $attachment['original_name'] }}"
                                                               class="flex items-center bg-white p-2 rounded border hover:bg-gray-50">
                                                                <svg class="w-4 h-4 mr-2 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                                </svg>
                                                                <span class="text-sm">{{ $attachment['original_name'] }}</span>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                        </svg>
                        <p class="text-gray-500 text-lg">Aucun message</p>
                        <p class="text-gray-400">Utilisez le formulaire pour contacter le support</p>
                    </div>
                    @endforelse
                </div>

                <!-- Pagination -->
                @if($messages->hasPages())
                <div class="px-6 py-4 border-t border-gray-200">
                    {{ $messages->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal pour visualiser les images -->
<div id="image-modal" class="hidden fixed inset-0 bg-black bg-opacity-75 flex items-center justify-center z-50">
    <div class="max-w-4xl max-h-4xl p-4">
        <img id="modal-image" src="" alt="" class="max-w-full max-h-full">
        <div class="text-center mt-4">
            <p id="modal-image-name" class="text-white text-sm"></p>
            <button onclick="closeImageModal()" class="mt-2 bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded">
                Fermer
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    let selectedFiles = [];

    // Gestion des fichiers
    fileInput.addEventListener('change', function(e) {
        selectedFiles = Array.from(e.target.files);
        displayFilePreview();
    });

    function displayFilePreview() {
        filePreview.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-white rounded border';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                </div>
                <button type="button" onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            filePreview.appendChild(fileItem);
        });
    }

    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        
        // Recréer le FileList
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        displayFilePreview();
    };
});

function toggleMessage(messageId) {
    const shortMessage = document.getElementById(`short-message-${messageId}`);
    const fullMessage = document.getElementById(`full-message-${messageId}`);
    
    if (shortMessage.classList.contains('hidden')) {
        shortMessage.classList.remove('hidden');
        fullMessage.classList.add('hidden');
    } else {
        shortMessage.classList.add('hidden');
        fullMessage.classList.remove('hidden');
    }
}

// Fonctions pour le modal d'image
function openImageModal(src, name) {
    document.getElementById('modal-image').src = src;
    document.getElementById('modal-image-name').textContent = name;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}

// Validation du formulaire
document.getElementById('messageForm').addEventListener('submit', function(e) {
    const message = this.message.value.trim();
    const subject = this.subject.value.trim();
    
    if (message.length < 10) {
        e.preventDefault();
        alert('Votre message doit contenir au moins 10 caractères.');
        return;
    }
    
    if (subject.length < 3) {
        e.preventDefault();
        alert('Le sujet doit contenir au moins 3 caractères.');
        return;
    }
});
</script>
@endsection 