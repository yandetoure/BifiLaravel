<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $messages = ChatMessage::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->reverse();

        $users = User::whereIn('role', ['admin', 'supervisor', 'agent'])
            ->select('id', 'name', 'role')
            ->get();

        return view('chat.index', compact('messages', 'users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required_without:attachments|string|max:1000',
            'attachments' => 'nullable|array',
            'attachments.*' => 'array',
            'message_type' => 'in:text,image,video,audio,file',
            'is_urgent' => 'boolean'
        ]);

        $message = ChatMessage::create([
            'user_id' => Auth::id(),
            'message' => $request->message ?? '',
            'attachments' => $request->attachments,
            'message_type' => $request->message_type ?? 'text',
            'is_urgent' => $request->boolean('is_urgent', false),
        ]);

        $message->load('user');

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $message
            ]);
        }

        return redirect()->route('chat.index')->with('success', 'Message envoyé');
    }

    public function getMessages(Request $request)
    {
        $lastId = $request->get('last_id', 0);
        
        $messages = ChatMessage::with('user')
            ->where('id', '>', $lastId)
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'messages' => $messages,
            'last_id' => $messages->last()?->id ?? $lastId
        ]);
    }

    public function markAsRead(Request $request)
    {
        $messageId = $request->get('message_id');
        
        $message = ChatMessage::findOrFail($messageId);
        
        // Marquer comme lu pour l'utilisateur actuel
        $message->readers()->syncWithoutDetaching([Auth::id()]);

        return response()->json(['success' => true]);
    }

    /**
     * Marquer toutes les notifications comme lues
     */
    public function markAllNotificationsRead(Request $request)
    {
        $user = Auth::user();
        
        // Marquer tous les messages clients comme lus si l'utilisateur n'est pas un client
        if ($user->role !== 'client') {
            \App\Models\ClientMessage::where('is_read', false)
                ->update(['is_read' => true]);
        }

        return response()->json(['success' => true]);
    }
} 