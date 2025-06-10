@extends('layouts.app')

@section('title', 'Chat Équipe')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <svg class="w-8 h-8 mr-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
            </svg>
            Chat Équipe
        </h1>
        <p class="text-gray-600">Communication interne pour les agents, superviseurs et administrateurs</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Zone de chat principale -->
        <div class="lg:col-span-3 bg-white rounded-lg shadow">
            <!-- Messages -->
            <div id="chat-messages" class="h-96 overflow-y-auto p-4 border-b border-gray-200 space-y-4">
                @foreach($messages as $message)
                    <div class="flex {{ $message->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-xs lg:max-w-md">
                            @if($message->user_id !== auth()->id())
                                <div class="flex items-center mb-1">
                                    <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                        <span class="text-xs font-semibold text-blue-600">{{ substr($message->user->name, 0, 2) }}</span>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $message->user->name }}</span>
                                    @if($message->user->role === 'admin')
                                        <span class="ml-1 text-xs bg-red-100 text-red-800 px-1.5 py-0.5 rounded">Admin</span>
                                    @elseif($message->user->role === 'supervisor')
                                        <span class="ml-1 text-xs bg-yellow-100 text-yellow-800 px-1.5 py-0.5 rounded">Superviseur</span>
                                    @elseif($message->user->role === 'agent')
                                        <span class="ml-1 text-xs bg-blue-100 text-blue-800 px-1.5 py-0.5 rounded">Agent</span>
                                    @endif
                                </div>
                            @endif
                            
                            <div class="px-4 py-2 rounded-lg {{ $message->user_id === auth()->id() ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900' }} {{ $message->is_urgent ? 'border-2 border-red-400' : '' }}">
                                @if($message->is_urgent && $message->user_id !== auth()->id())
                                    <div class="flex items-center mb-1">
                                        <svg class="w-4 h-4 text-red-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        <span class="text-xs font-semibold text-red-500">URGENT</span>
                                    </div>
                                @endif
                                
                                @if($message->message)
                                    <p class="text-sm mb-2">{{ $message->message }}</p>
                                @endif
                                
                                <!-- Affichage des pièces jointes -->
                                @if($message->attachments)
                                    <div class="space-y-2 mt-2">
                                        @foreach($message->attachments as $attachment)
                                            <div class="bg-white bg-opacity-20 rounded p-2">
                                                @if($attachment['message_type'] === 'image')
                                                    <img src="{{ asset('storage/' . $attachment['path']) }}" 
                                                         alt="{{ $attachment['original_name'] }}" 
                                                         class="max-w-full h-auto rounded cursor-pointer"
                                                         onclick="openImageModal('{{ asset('storage/' . $attachment['path']) }}', '{{ $attachment['original_name'] }}')">
                                                @elseif($attachment['message_type'] === 'video')
                                                    <video controls class="max-w-full h-auto rounded">
                                                        <source src="{{ asset('storage/' . $attachment['path']) }}" type="{{ $attachment['mime_type'] }}">
                                                        Votre navigateur ne supporte pas la lecture vidéo.
                                                    </video>
                                                @elseif($attachment['message_type'] === 'audio')
                                                    <audio controls class="w-full">
                                                        <source src="{{ asset('storage/' . $attachment['path']) }}" type="{{ $attachment['mime_type'] }}">
                                                        Votre navigateur ne supporte pas la lecture audio.
                                                    </audio>
                                                @else
                                                    <a href="{{ asset('storage/' . $attachment['path']) }}" 
                                                       download="{{ $attachment['original_name'] }}"
                                                       class="flex items-center text-blue-300 hover:text-blue-100">
                                                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                        </svg>
                                                        {{ $attachment['original_name'] }}
                                                        <span class="text-xs ml-1">({{ number_format($attachment['size'] / 1024, 1) }} KB)</span>
                                                    </a>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                                
                                <div class="text-xs {{ $message->user_id === auth()->id() ? 'text-blue-100' : 'text-gray-500' }} mt-1">
                                    {{ $message->created_at->format('H:i') }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Zone de saisie -->
            <div class="p-4">
                <!-- Aperçu des fichiers sélectionnés -->
                <div id="file-preview" class="hidden mb-4 p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-sm font-medium text-gray-700">Fichiers sélectionnés</span>
                        <button onclick="clearFiles()" class="text-red-500 hover:text-red-700">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="file-list" class="space-y-2"></div>
                </div>

                <form id="chat-form" class="flex flex-col space-y-3">
                    @csrf
                    <div class="flex space-x-2">
                        <input type="file" id="file-input" multiple accept="image/*,video/*,audio/*,.pdf,.doc,.docx" class="hidden">
                        
                        <button type="button" onclick="document.getElementById('file-input').click()" 
                                class="flex-shrink-0 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                            </svg>
                        </button>
                        
                        <button type="button" id="audio-record-btn" onclick="toggleAudioRecording()" 
                                class="flex-shrink-0 bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-2 rounded-lg transition duration-200">
                            <svg id="mic-icon" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
                            </svg>
                        </button>
                        
                        <textarea id="message-input" 
                                  placeholder="Tapez votre message ici... (Shift+Entrée pour nouvelle ligne)"
                                  class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                                  rows="1"></textarea>
                                  
                        <button type="submit" class="flex-shrink-0 bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <div class="flex items-center">
                        <label class="flex items-center">
                            <input type="checkbox" id="urgent-checkbox" class="form-checkbox h-4 w-4 text-red-600">
                            <span class="ml-2 text-sm text-gray-700">Message urgent</span>
                        </label>
                    </div>
                </form>
            </div>
        </div>

        <!-- Utilisateurs connectés -->
        <div class="bg-white rounded-lg shadow p-4">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Membres de l'équipe</h3>
            <div class="space-y-3">
                @foreach($users as $user)
                    <div class="flex items-center p-2 bg-gray-50 rounded-lg">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <span class="text-sm font-semibold text-blue-600">{{ substr($user->name, 0, 2) }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                            <div class="text-xs text-gray-500 capitalize">{{ $user->role }}</div>
                        </div>
                    </div>
                @endforeach
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
    const chatMessages = document.getElementById('chat-messages');
    const chatForm = document.getElementById('chat-form');
    const messageInput = document.getElementById('message-input');
    const urgentCheckbox = document.getElementById('urgent-checkbox');
    const fileInput = document.getElementById('file-input');
    const filePreview = document.getElementById('file-preview');
    const fileList = document.getElementById('file-list');
    let lastMessageId = {{ $messages->last()?->id ?? 0 }};
    let selectedFiles = [];
    let mediaRecorder;
    let audioChunks = [];
    let isRecording = false;
    
    // Gestion des fichiers
    fileInput.addEventListener('change', function(e) {
        selectedFiles = Array.from(e.target.files);
        displayFilePreview();
    });
    
    function displayFilePreview() {
        if (selectedFiles.length === 0) {
            filePreview.classList.add('hidden');
            return;
        }
        
        filePreview.classList.remove('hidden');
        fileList.innerHTML = '';
        
        selectedFiles.forEach((file, index) => {
            const fileItem = document.createElement('div');
            fileItem.className = 'flex items-center justify-between p-2 bg-white rounded';
            fileItem.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-4 h-4 mr-2 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-sm">${file.name}</span>
                    <span class="text-xs text-gray-500 ml-2">(${(file.size / 1024).toFixed(1)} KB)</span>
                </div>
                <button onclick="removeFile(${index})" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </button>
            `;
            fileList.appendChild(fileItem);
        });
    }
    
    window.removeFile = function(index) {
        selectedFiles.splice(index, 1);
        displayFilePreview();
    };
    
    window.clearFiles = function() {
        selectedFiles = [];
        fileInput.value = '';
        filePreview.classList.add('hidden');
    };
    
    // Fonctionnalité d'enregistrement audio
    window.toggleAudioRecording = async function() {
        if (!isRecording) {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                mediaRecorder = new MediaRecorder(stream);
                audioChunks = [];
                
                mediaRecorder.ondataavailable = function(event) {
                    audioChunks.push(event.data);
                };
                
                mediaRecorder.onstop = function() {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    const audioFile = new File([audioBlob], `audio_${Date.now()}.wav`, { type: 'audio/wav' });
                    selectedFiles.push(audioFile);
                    displayFilePreview();
                    
                    // Arrêter le stream
                    stream.getTracks().forEach(track => track.stop());
                };
                
                mediaRecorder.start();
                isRecording = true;
                
                // Changer l'apparence du bouton
                const recordBtn = document.getElementById('audio-record-btn');
                const micIcon = document.getElementById('mic-icon');
                recordBtn.classList.remove('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
                recordBtn.classList.add('bg-red-500', 'hover:bg-red-600', 'text-white');
                micIcon.innerHTML = `
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 10a1 1 0 011-1h4a1 1 0 011 1v4a1 1 0 01-1 1h-4a1 1 0 01-1-1v-4z"></path>
                `;
                
            } catch (error) {
                console.error('Erreur accès microphone:', error);
                alert('Impossible d\'accéder au microphone. Vérifiez les permissions.');
            }
        } else {
            mediaRecorder.stop();
            isRecording = false;
            
            // Restaurer l'apparence du bouton
            const recordBtn = document.getElementById('audio-record-btn');
            const micIcon = document.getElementById('mic-icon');
            recordBtn.classList.remove('bg-red-500', 'hover:bg-red-600', 'text-white');
            recordBtn.classList.add('bg-gray-100', 'hover:bg-gray-200', 'text-gray-700');
            micIcon.innerHTML = `
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"></path>
            `;
        }
    };
    
    // Upload de fichiers
    async function uploadFiles() {
        const uploadedFiles = [];
        
        for (const file of selectedFiles) {
            const formData = new FormData();
            formData.append('file', file);
            formData.append('chat_type', 'team');
            
            try {
                const response = await fetch('{{ route("uploads.chat-file") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: formData
                });
                
                const result = await response.json();
                if (result.success) {
                    uploadedFiles.push(result.file);
                }
            } catch (error) {
                console.error('Erreur upload:', error);
            }
        }
        
        return uploadedFiles;
    }
    
    // Faire défiler vers le bas
    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Affichage des messages (fonction mise à jour pour les fichiers)
    function displayMessage(message) {
        const isOwn = message.user_id === {{ auth()->id() }};
        let attachmentsHtml = '';
        
        if (message.attachments && message.attachments.length > 0) {
            attachmentsHtml = '<div class="space-y-2 mt-2">';
            message.attachments.forEach(attachment => {
                if (attachment.message_type === 'image') {
                    attachmentsHtml += `
                        <img src="/storage/${attachment.path}" alt="${attachment.original_name}" 
                             class="max-w-full h-auto rounded cursor-pointer"
                             onclick="openImageModal('/storage/${attachment.path}', '${attachment.original_name}')">
                    `;
                } else if (attachment.message_type === 'video') {
                    attachmentsHtml += `
                        <video controls class="max-w-full h-auto rounded">
                            <source src="/storage/${attachment.path}" type="${attachment.mime_type}">
                        </video>
                    `;
                } else if (attachment.message_type === 'audio') {
                    attachmentsHtml += `
                        <audio controls class="w-full">
                            <source src="/storage/${attachment.path}" type="${attachment.mime_type}">
                        </audio>
                    `;
                } else {
                    attachmentsHtml += `
                        <a href="/storage/${attachment.path}" download="${attachment.original_name}"
                           class="flex items-center text-blue-300 hover:text-blue-100">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                            </svg>
                            ${attachment.original_name}
                        </a>
                    `;
                }
            });
            attachmentsHtml += '</div>';
        }
        
        const messageHtml = `
            <div class="flex ${isOwn ? 'justify-end' : 'justify-start'}">
                <div class="max-w-xs lg:max-w-md">
                    ${!isOwn ? `
                        <div class="flex items-center mb-1">
                            <div class="w-6 h-6 bg-blue-100 rounded-full flex items-center justify-center mr-2">
                                <span class="text-xs font-semibold text-blue-600">${message.user.name.substring(0, 2)}</span>
                            </div>
                            <span class="text-xs text-gray-500">${message.user.name}</span>
                        </div>
                    ` : ''}
                    
                    <div class="px-4 py-2 rounded-lg ${isOwn ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-900'} ${message.is_urgent ? 'border-2 border-red-400' : ''}">
                        ${message.message ? `<p class="text-sm mb-2">${message.message}</p>` : ''}
                        ${attachmentsHtml}
                        <div class="text-xs ${isOwn ? 'text-blue-100' : 'text-gray-500'} mt-1">
                            ${new Date().toLocaleTimeString('fr-FR', {hour: '2-digit', minute: '2-digit'})}
                        </div>
                    </div>
                </div>
            </div>
        `;
        
        chatMessages.insertAdjacentHTML('beforeend', messageHtml);
        scrollToBottom();
    }
    
    // Envoi de message
    chatForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const message = messageInput.value.trim();
        const isUrgent = urgentCheckbox.checked;
        
        if (!message && selectedFiles.length === 0) return;
        
        // Upload des fichiers d'abord
        const attachments = await uploadFiles();
        
        fetch('{{ route('chat.store') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                message: message,
                attachments: attachments,
                message_type: attachments.length > 0 ? attachments[0].message_type : 'text',
                is_urgent: isUrgent
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMessage(data.message);
                messageInput.value = '';
                urgentCheckbox.checked = false;
                clearFiles();
                lastMessageId = data.message.id;
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            alert('Erreur lors de l\'envoi du message');
        });
    });
    
    // Vérification des nouveaux messages
    function checkNewMessages() {
        fetch(`{{ route('chat.get-messages') }}?last_id=${lastMessageId}`)
        .then(response => response.json())
        .then(data => {
            data.messages.forEach(message => {
                displayMessage(message);
            });
            if (data.messages.length > 0) {
                lastMessageId = data.last_id;
            }
        })
        .catch(error => console.error('Erreur:', error));
    }
    
    setInterval(checkNewMessages, 2000);
    scrollToBottom();
    messageInput.focus();
    
    messageInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    });
});

// Fonctions pour le modal d'image
function openImageModal(src, name) {
    document.getElementById('modal-image').src = src;
    document.getElementById('modal-image-name').textContent = name;
    document.getElementById('image-modal').classList.remove('hidden');
}

function closeImageModal() {
    document.getElementById('image-modal').classList.add('hidden');
}
</script>
@endsection 