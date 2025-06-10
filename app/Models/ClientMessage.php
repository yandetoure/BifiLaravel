<?php declare(strict_types=1); 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class ClientMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'message',
        'attachments',
        'message_type',
        'priority',
        'status',
        'subject',
        'is_read',
        'replied_by',
        'staff_reply',
        'reply_attachments',
        'replied_at',
    ];

    protected $casts = [
        'attachments' => 'array',
        'reply_attachments' => 'array',
        'is_read' => 'boolean',
        'replied_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function repliedBy()
    {
        return $this->belongsTo(User::class, 'replied_by');
    }

    public function isUrgent()
    {
        return $this->priority === 'urgent';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isReplied()
    {
        return $this->status === 'replied';
    }

    public function isClosed()
    {
        return $this->status === 'closed';
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }

    public function scopeReplied($query)
    {
        return $query->where('status', 'replied');
    }

    public function scopeClosed($query)
    {
        return $query->where('status', 'closed');
    }
}
