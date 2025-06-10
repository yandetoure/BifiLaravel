<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Upload de fichier pour le chat
     */
    public function uploadChatFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:10240', // 10MB max
            'chat_type' => 'required|in:team,client'
        ]);

        $file = $request->file('file');
        $chatType = $request->chat_type;
        
        // Vérifier le type de fichier
        $allowedMimes = [
            'image/jpeg', 'image/png', 'image/gif', 'image/webp',
            'video/mp4', 'video/avi', 'video/mov', 'video/wmv',
            'audio/mp3', 'audio/wav', 'audio/ogg', 'audio/m4a',
            'application/pdf', 'application/msword', 
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ];

        if (!in_array($file->getMimeType(), $allowedMimes)) {
            return response()->json([
                'success' => false,
                'message' => 'Type de fichier non autorisé'
            ], 400);
        }

        // Déterminer le type de message
        $messageType = $this->getMessageType($file->getMimeType());

        // Générer un nom unique
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        
        // Stocker le fichier
        $path = $file->storeAs("chat/{$chatType}", $filename, 'public');

        return response()->json([
            'success' => true,
            'file' => [
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'size' => $file->getSize(),
                'mime_type' => $file->getMimeType(),
                'message_type' => $messageType,
                'url' => Storage::url($path)
            ]
        ]);
    }

    /**
     * Supprimer un fichier
     */
    public function deleteFile(Request $request)
    {
        $request->validate([
            'path' => 'required|string'
        ]);

        if (Storage::disk('public')->exists($request->path)) {
            Storage::disk('public')->delete($request->path);
            return response()->json(['success' => true]);
        }

        return response()->json(['success' => false, 'message' => 'Fichier non trouvé'], 404);
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
