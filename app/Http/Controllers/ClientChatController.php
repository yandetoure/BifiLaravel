<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClientMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ClientChatController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user->role === 'client') {
            // Vue pour les clients
            $messages = ClientMessage::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->paginate(20);
                
            return view('client-chat.index', compact('messages'));
        } else {
            // Vue pour admin/superviseur/agent - voir tous les messages clients
            $messages = ClientMessage::with('user')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
                
            $stats = [
                'total' => ClientMessage::count(),
                'pending' => ClientMessage::pending()->count(),
                'urgent' => ClientMessage::urgent()->count(),
                'today' => ClientMessage::today()->count(),
            ];
                
            return view('client-chat.admin', compact('messages', 'stats'));
        }
    }

    public function sendMessage(Request $request)
    {
        $request->validate([
            'message' => 'required_without:attachments|string|max:5000',
            'subject' => 'nullable|string|max:255',
            'priority' => 'in:normal,urgent',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|max:10240', // 10MB max par fichier
        ]);

        $attachments = [];
        
        // Gérer les fichiers uploadés
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                // Vérifier le type de fichier
                $allowedMimes = [
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'video/mp4', 'video/avi', 'video/mov', 'video/wmv',
                    'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a',
                    'application/pdf', 'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    continue; // Ignorer les fichiers non autorisés
                }

                // Déterminer le type de message
                $messageType = $this->getMessageType($file->getMimeType());

                // Générer un nom unique
                $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Stocker le fichier
                $path = $file->storeAs('chat/client', $filename, 'public');

                $attachments[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'message_type' => $messageType,
                ];
            }
        }

        $message = ClientMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'subject' => $request->subject,
            'priority' => $request->priority ?? 'normal',
            'attachments' => $attachments,
            'message_type' => count($attachments) > 0 ? $attachments[0]['message_type'] : 'text',
        ]);

        // Créer une notification pour les admins/superviseurs/agents
        $this->notifyStaff($message);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message->load('user'),
            ]);
        }

        return redirect()->back()->with('success', 'Message envoyé avec succès');
    }

    public function getMessages(Request $request)
    {
        $user = Auth::user();
        
        if ($user->role === 'client') {
            $messages = ClientMessage::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        } else {
            $messages = ClientMessage::with('user')
                ->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();
        }

        return response()->json($messages);
    }

    public function getUnreadCount()
    {
        $user = Auth::user();
        
        if ($user->role === 'client') {
            // Les clients n'ont pas de messages "non lus" dans ce contexte
            return response()->json(['count' => 0]);
        }
        
        // Pour les staff, compter les nouveaux messages clients non traités
        $count = ClientMessage::pending()->count();
        
        return response()->json(['count' => $count]);
    }

    public function updateStatus(Request $request, ClientMessage $message)
    {
        $request->validate([
            'status' => 'required|in:pending,replied,closed',
        ]);

        $message->update(['status' => $request->status]);

        return response()->json([
            'success' => true,
            'message' => 'Statut mis à jour',
        ]);
    }

    private function notifyStaff(ClientMessage $message)
    {
        // Créer une notification pour tous les admins, superviseurs et agents
        \App\Models\Notification::create([
            'title' => 'Nouveau message client',
            'message' => "Nouveau message de {$message->user->name}: " . substr($message->message, 0, 100) . '...',
            'type' => $message->priority === 'urgent' ? 'warning' : 'info',
            'priority' => $message->priority === 'urgent' ? 'high' : 'normal',
            'is_global' => false,
            'target_roles' => ['admin', 'supervisor', 'agent'],
            'metadata' => [
                'client_message_id' => $message->id,
                'client_name' => $message->user->name,
                'client_email' => $message->user->email,
            ],
        ]);
    }

    /**
     * Répondre à un message client (pour staff seulement)
     */
    public function replyToMessage(Request $request, ClientMessage $message)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur peut répondre
        if ($user->role === 'client') {
            abort(403, 'Non autorisé');
        }

        $request->validate([
            'reply' => 'required_without:reply_attachments|string|max:5000',
            'reply_attachments' => 'nullable|array',
            'reply_attachments.*' => 'file|max:10240', // 10MB max par fichier
        ]);

        $replyAttachments = [];
        
        // Gérer les fichiers uploadés pour la réponse
        if ($request->hasFile('reply_attachments')) {
            foreach ($request->file('reply_attachments') as $file) {
                // Vérifier le type de fichier
                $allowedMimes = [
                    'image/jpeg', 'image/png', 'image/gif', 'image/webp',
                    'video/mp4', 'video/avi', 'video/mov', 'video/wmv',
                    'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a',
                    'application/pdf', 'application/msword', 
                    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
                ];

                if (!in_array($file->getMimeType(), $allowedMimes)) {
                    continue; // Ignorer les fichiers non autorisés
                }

                // Déterminer le type de message
                $messageType = $this->getMessageType($file->getMimeType());

                // Générer un nom unique
                $filename = \Illuminate\Support\Str::uuid() . '.' . $file->getClientOriginalExtension();
                
                // Stocker le fichier
                $path = $file->storeAs('chat/client', $filename, 'public');

                $replyAttachments[] = [
                    'path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'size' => $file->getSize(),
                    'mime_type' => $file->getMimeType(),
                    'message_type' => $messageType,
                ];
            }
        }

        $message->update([
            'staff_reply' => $request->reply ?? '',
            'reply_attachments' => $replyAttachments,
            'replied_by' => $user->id,
            'replied_at' => now(),
            'status' => 'replied',
            'is_read' => true,
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Réponse envoyée avec succès',
                'data' => $message->load(['user', 'repliedBy'])
            ]);
        }

        return redirect()->back()->with('success', 'Réponse envoyée avec succès');
    }

    /**
     * Déterminer le type de message selon le MIME type
     */
    private function getMessageType(string $mimeType): string
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        }
        
        if (str_starts_with($mimeType, 'video/')) {
            return 'video';
        }
        
        if (str_starts_with($mimeType, 'audio/')) {
            return 'audio';
        }
        
        return 'file';
    }
}
