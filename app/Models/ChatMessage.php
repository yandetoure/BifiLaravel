<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'attachments',
        'message_type',
        'is_urgent',
        'is_client_message',
    ];

    protected $casts = [
        'is_urgent' => 'boolean',
        'is_client_message' => 'boolean',
        'attachments' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function readers()
    {
        return $this->belongsToMany(User::class, 'chat_message_reads')
            ->withTimestamps();
    }

    public function isReadBy($userId)
    {
        return $this->readers()->where('user_id', $userId)->exists();
    }

    public function scopeUnreadBy($query, $userId)
    {
        return $query->whereDoesntHave('readers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }
} 