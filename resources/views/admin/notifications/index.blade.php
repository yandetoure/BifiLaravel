@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Gestion des Notifications</h1>
            <p class="text-gray-600">Système de notifications pour tous les utilisateurs</p>
        </div>
        <div class="flex space-x-4">
            <a href="{{ route('admin.dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600">
                Retour Dashboard
            </a>
            <button onclick="openNotificationModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Nouvelle Notification
            </button>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-2 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a2 2 0 112 0v5z"></path>
                    </svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-600">Total</p>
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
                    <p class="text-sm font-medium text-gray-600">Non Lues</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $stats['unread'] }}</p>
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
                    <p class="text-sm font-medium text-gray-600">Urgentes</p>
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

    <!-- Liste des notifications -->
    <div class="bg-white rounded-lg shadow">
        <div class="px-6 py-4 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900">Notifications Système</h2>
        </div>
        
        <div class="divide-y divide-gray-200">
            @forelse($notifications as $notification)
            <div class="p-6 hover:bg-gray-50">
                <div class="flex justify-between items-start">
                    <div class="flex-1">
                        <div class="flex items-center space-x-3 mb-2">
                            <h3 class="font-semibold text-gray-900">{{ $notification->title }}</h3>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $notification->type === 'error' ? 'bg-red-100 text-red-800' : 
                                   ($notification->type === 'warning' ? 'bg-yellow-100 text-yellow-800' : 
                                   ($notification->type === 'success' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800')) }}">
                                {{ ucfirst($notification->type) }}
                            </span>
                            <span class="px-2 py-1 text-xs rounded-full 
                                {{ $notification->priority === 'urgent' ? 'bg-red-100 text-red-800' : 
                                   ($notification->priority === 'high' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800') }}">
                                {{ ucfirst($notification->priority) }}
                            </span>
                        </div>
                        
                        <p class="text-gray-700 mb-2">{{ $notification->message }}</p>
                        
                        <div class="flex items-center space-x-4 text-sm text-gray-500">
                            <span>{{ $notification->created_at->format('d/m/Y à H:i') }}</span>
                            @if($notification->user)
                                <span>Pour: {{ $notification->user->name }}</span>
                            @elseif($notification->is_global)
                                <span>Pour: Tous les utilisateurs</span>
                            @elseif($notification->target_roles)
                                <span>Pour: {{ implode(', ', $notification->target_roles) }}</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="flex space-x-2 ml-4">
                        @if(!$notification->isRead())
                            <button onclick="markAsRead({{ $notification->id }})" 
                                    class="text-blue-600 hover:text-blue-900 text-sm font-medium">
                                Marquer comme lu
                            </button>
                        @endif
                        
                        <form method="POST" action="{{ route('admin.notifications.delete', $notification) }}" class="inline"
                              onsubmit="return confirm('Supprimer cette notification ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div class="p-12 text-center">
                <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5-5-5h5v-5a2 2 0 112 0v5z"></path>
                </svg>
                <p class="text-gray-500 text-lg">Aucune notification</p>
                <p class="text-gray-400">Créez votre première notification système</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($notifications->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $notifications->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Nouvelle Notification -->
<div id="notificationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <form method="POST" action="{{ route('admin.notifications.send') }}">
                @csrf
                <div class="px-6 py-4 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900">Nouvelle Notification</h3>
                </div>
                
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Titre</label>
                        <input type="text" name="title" required
                               class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Message</label>
                        <textarea name="message" rows="3" required
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                            <select name="type" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="info">Information</option>
                                <option value="success">Succès</option>
                                <option value="warning">Avertissement</option>
                                <option value="error">Erreur</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Priorité</label>
                            <select name="priority" required class="w-full border border-gray-300 rounded-lg px-3 py-2">
                                <option value="low">Basse</option>
                                <option value="normal" selected>Normale</option>
                                <option value="high">Haute</option>
                                <option value="urgent">Urgente</option>
                            </select>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Destinataires</label>
                        <select name="target_type" onchange="toggleTargetOptions()" required class="w-full border border-gray-300 rounded-lg px-3 py-2 mb-3">
                            <option value="all">Tous les utilisateurs</option>
                            <option value="role">Par rôle</option>
                            <option value="user">Utilisateurs spécifiques</option>
                        </select>

                        <div id="roleOptions" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rôles ciblés</label>
                            <div class="space-y-2">
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_roles[]" value="admin" class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm">Administrateurs</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_roles[]" value="supervisor" class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm">Superviseurs</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_roles[]" value="agent" class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm">Agents</span>
                                </label>
                                <label class="flex items-center">
                                    <input type="checkbox" name="target_roles[]" value="client" class="rounded border-gray-300 text-blue-600">
                                    <span class="ml-2 text-sm">Clients</span>
                                </label>
                            </div>
                        </div>

                        <div id="userOptions" class="hidden">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Utilisateurs</label>
                            <select name="target_users[]" multiple class="w-full border border-gray-300 rounded-lg px-3 py-2 h-32">
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}</option>
                                @endforeach
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Maintenez Ctrl/Cmd pour sélectionner plusieurs utilisateurs</p>
                        </div>
                    </div>
                </div>

                <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                    <button type="button" onclick="closeNotificationModal()" 
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        Annuler
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600">
                        Envoyer Notification
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openNotificationModal() {
    document.getElementById('notificationModal').classList.remove('hidden');
}

function closeNotificationModal() {
    document.getElementById('notificationModal').classList.add('hidden');
}

function toggleTargetOptions() {
    const targetType = event.target.value;
    const roleOptions = document.getElementById('roleOptions');
    const userOptions = document.getElementById('userOptions');
    
    roleOptions.classList.add('hidden');
    userOptions.classList.add('hidden');
    
    if (targetType === 'role') {
        roleOptions.classList.remove('hidden');
    } else if (targetType === 'user') {
        userOptions.classList.remove('hidden');
    }
}

function markAsRead(notificationId) {
    fetch('{{ route("admin.notifications.mark-read") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            notification_id: notificationId
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        }
    });
}

// Fermer le modal quand on clique à l'extérieur
document.getElementById('notificationModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeNotificationModal();
    }
});
</script>
@endsection 