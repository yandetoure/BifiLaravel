@extends('layouts.app')

@section('title', 'Messages Clients')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 flex items-center">
                <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                Messages Clients
            </h1>
            <p class="text-gray-600">Gestion et réponse aux demandes de support client</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isSupervisor() ? route('supervisor.dashboard') : route('agent.dashboard')) }}" 
               class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition-colors">
                Retour Dashboard
            </a>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total Messages</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">En Attente</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['pending'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-red-100 rounded-lg">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.732 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Urgents</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['urgent'] }}</p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Aujourd'hui</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['today'] }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="bg-white rounded-lg shadow mb-6 p-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Statut</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Tous les statuts</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="replied" {{ request('status') === 'replied' ? 'selected' : '' }}>Répondu</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermé</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Priorité</label>
                <select name="priority" class="w-full border border-gray-300 rounded-lg px-3 py-2">
                    <option value="">Toutes les priorités</option>
                    <option value="normal" {{ request('priority') === 'normal' ? 'selected' : '' }}>Normale</option>
                    <option value="urgent" {{ request('priority') === 'urgent' ? 'selected' : '' }}>Urgente</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Client</label>
                <input type="text" name="client" value="{{ request('client') }}" 
                       placeholder="Nom du client..." 
                       class="w-full border border-gray-300 rounded-lg px-3 py-2">
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    Filtrer
                </button>
            </div>
        </form>
    </div>

    <!-- Liste des messages -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Messages Client</h2>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($messages as $message)
            <div class="p-6 hover:bg-gray-50" id="message-{{ $message->id }}">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-start space-x-4 flex-1">
                        <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold text-blue-600">
                                {{ substr($message->user->name, 0, 2) }}
                            </span>
                        </div>
                        <div class="flex-1">
                            <div class="flex items-center space-x-3 mb-1">
                                <h3 class="font-semibold text-gray-900">{{ $message->user->name }}</h3>
                                <span class="text-sm text-gray-500">{{ $message->user->email }}</span>
                                <span class="px-2 py-1 text-xs rounded-full 
                                    {{ $message->priority === 'urgent' ? 'bg-red-100 text-red-800' : 'bg-blue-100 text-blue-800' }}">
                                    {{ $message->priority === 'urgent' ? 'Urgent' : 'Normal' }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-500 mb-2">{{ $message->created_at->format('d/m/Y à H:i') }}</p>
                            <h4 class="font-medium text-gray-900 mb-2">{{ $message->subject ?: 'Sans sujet' }}</h4>
                            
                            <!-- Message du client -->
                            <div class="text-gray-700 mb-3">
                                @if(strlen($message->message) > 200)
                                    <div id="short-message-{{ $message->id }}">
                                        {{ Str::limit($message->message, 200) }}
                                        <button onclick="toggleFullMessage({{ $message->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm ml-2">
                                            Voir le message complet...
                                        </button>
                                    </div>
                                    <div id="full-message-{{ $message->id }}" class="hidden">
                                        <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                                        <button onclick="toggleFullMessage({{ $message->id }})" 
                                                class="text-blue-600 hover:text-blue-800 text-sm mt-2">
                                            Masquer
                                        </button>
                                    </div>
                                @else
                                    <p class="text-gray-700 whitespace-pre-wrap">{{ $message->message }}</p>
                                @endif
                            </div>

                            <!-- Pièces jointes du client -->
                            @if($message->attachments)
                                <div class="mt-3 mb-4">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Pièces jointes du client :</p>
                                    <div class="flex flex-wrap gap-2">
                                        @foreach($message->attachments as $attachment)
                                            @if($attachment['message_type'] === 'image')
                                                <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                     alt="{{ $attachment['original_name'] }}" 
                                                     class="w-20 h-20 object-cover rounded cursor-pointer border"
                                                     onclick="openImageModal('{{ asset('storage/' . $attachment['path']) }}', '{{ $attachment['original_name'] }}')">
                                            @else
                                                <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                                   download="{{ $attachment['original_name'] }}"
                                                   class="flex items-center bg-blue-50 p-2 rounded border hover:bg-blue-100">
                                                    <svg class="w-4 h-4 mr-2 text-blue-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    <span class="text-sm">{{ $attachment['original_name'] }}</span>
                                                </a>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Réponse existante du staff -->
                            @if($message->staff_reply)
                                <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
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
                                            <p class="text-sm text-green-800 whitespace-pre-wrap">{{ $message->staff_reply }}</p>
                                            
                                            <!-- Pièces jointes de la réponse -->
                                            @if($message->reply_attachments)
                                                <div class="mt-3">
                                                    <p class="text-sm font-medium text-green-700 mb-2">Fichiers joints :</p>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($message->reply_attachments as $attachment)
                                                            @if($attachment['message_type'] === 'image')
                                                                <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                                     alt="{{ $attachment['original_name'] }}" 
                                                                     class="w-20 h-20 object-cover rounded cursor-pointer border"
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

                            <!-- Formulaire de réponse -->
                            <div id="reply-form-{{ $message->id }}" class="mt-4 {{ $message->staff_reply ? 'hidden' : '' }}">
                                <form action="{{ route('client-chat.reply', $message) }}" method="POST" enctype="multipart/form-data" class="bg-gray-50 border rounded-lg p-4">
                                    @csrf
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Votre réponse</label>
                                        <textarea name="reply" rows="4" 
                                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"
                                                  placeholder="Tapez votre réponse..." required></textarea>
                                    </div>
                                    
                                    <!-- Zone de fichiers pour la réponse -->
                                    <div class="mb-3">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Pièces jointes (optionnel)</label>
                                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-3">
                                            <input type="file" id="reply-files-{{ $message->id }}" name="reply_attachments[]" multiple 
                                                   accept="image/*,video/*,audio/*,.pdf,.doc,.docx" class="hidden">
                                            <div class="text-center">
                                                <button type="button" onclick="document.getElementById('reply-files-{{ $message->id }}').click()" 
                                                        class="text-blue-600 hover:text-blue-500 text-sm">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                    </svg>
                                                    Joindre des fichiers
                                                </button>
                                                <p class="text-xs text-gray-500 mt-1">Images, documents, max 10MB</p>
                                            </div>
                                        </div>
                                        <div id="reply-file-preview-{{ $message->id }}" class="mt-2 space-y-2"></div>
                                    </div>
                                    
                                    <div class="flex justify-between items-center">
                                        <button type="button" onclick="toggleReplyForm({{ $message->id }})" 
                                                class="text-gray-600 hover:text-gray-800 text-sm">
                                            Annuler
                                        </button>
                                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                                            <svg class="w-4 h-4 inline mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                            </svg>
                                            Envoyer Réponse
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center space-x-3 ml-4">
                        <!-- Statut -->
                        <form method="POST" action="{{ route('client-chat.update-status', $message) }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()" 
                                    class="text-xs border-0 rounded-full font-medium px-3 py-1
                                    {{ $message->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : 
                                       ($message->status === 'replied' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800') }}">
                                <option value="pending" {{ $message->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="replied" {{ $message->status === 'replied' ? 'selected' : '' }}>Répondu</option>
                                <option value="closed" {{ $message->status === 'closed' ? 'selected' : '' }}>Fermé</option>
                            </select>
                        </form>

                        <!-- Actions -->
                        <div class="flex flex-col space-y-2">
                            @if(!$message->staff_reply)
                            <button onclick="toggleReplyForm({{ $message->id }})" 
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Répondre
                            </button>
                            @endif
                            
                            <a href="mailto:{{ $message->user->email }}?subject=Re: {{ $message->subject }}&body=Bonjour {{ $message->user->name }},%0D%0A%0D%0AConcernant votre message du {{ $message->created_at->format('d/m/Y') }}:%0D%0A{{ $message->message }}%0D%0A%0D%0A"
                               class="text-gray-600 hover:text-gray-900 text-sm font-medium">
                                Email
                            </a>
                            
                            @if(auth()->user()->isAdmin())
                            <form method="POST" action="{{ route('admin.client-messages.delete', $message) }}" class="inline"
                                  onsubmit="return confirm('Supprimer ce message ?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                    Supprimer
                                </button>
                            </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Aucun message client</p>
                <p class="text-gray-400">Les messages des clients apparaîtront ici</p>
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
    // Gestion des fichiers pour chaque formulaire de réponse
    @foreach($messages as $message)
    @if(!$message->staff_reply)
    setupFileHandling({{ $message->id }});
    @endif
    @endforeach
});

function setupFileHandling(messageId) {
    const fileInput = document.getElementById(`reply-files-${messageId}`);
    const filePreview = document.getElementById(`reply-file-preview-${messageId}`);
    let selectedFiles = [];

    if (!fileInput) return;

    fileInput.addEventListener('change', function(e) {
        selectedFiles = Array.from(e.target.files);
        displayFilePreview(messageId, selectedFiles, filePreview);
    });

    window[`removeReplyFile_${messageId}`] = function(index) {
        selectedFiles.splice(index, 1);
        
        const dt = new DataTransfer();
        selectedFiles.forEach(file => dt.items.add(file));
        fileInput.files = dt.files;
        
        displayFilePreview(messageId, selectedFiles, filePreview);
    };
}

function displayFilePreview(messageId, files, container) {
    container.innerHTML = '';
    
    files.forEach((file, index) => {
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
            <button type="button" onclick="removeReplyFile_${messageId}(${index})" class="text-red-500 hover:text-red-700">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                </svg>
            </button>
        `;
        container.appendChild(fileItem);
    });
}

function toggleFullMessage(messageId) {
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

function toggleReplyForm(messageId) {
    const replyForm = document.getElementById(`reply-form-${messageId}`);
    replyForm.classList.toggle('hidden');
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

// Auto-refresh pour voir les nouveaux messages (optionnel)
setInterval(function() {
    // Optionnel: recharger la page ou faire un appel AJAX pour les nouveaux messages
    // location.reload();
}, 60000); // Rafraîchir toutes les minutes
</script>
@endsection 