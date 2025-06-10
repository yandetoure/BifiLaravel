<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'priority',
        'metadata',
        'read_at',
        'is_global',
        'target_roles',
    ];

    protected $casts = [
        'metadata' => 'array',
        'read_at' => 'datetime',
        'is_global' => 'boolean',
        'target_roles' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isRead()
    {
        return !is_null($this->read_at);
    }

    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    public function isForRole($role)
    {
        if ($this->is_global) {
            return true;
        }
        
        if (!$this->target_roles) {
            return false;
        }

        return in_array($role, $this->target_roles);
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    public function scopeForUser($query, $user)
    {
        return $query->where(function ($q) use ($user) {
            $q->where('user_id', $user->id)
              ->orWhere('is_global', true)
              ->orWhereJsonContains('target_roles', $user->role);
        });
    }

    public function scopeUrgent($query)
    {
        return $query->where('priority', 'urgent');
    }
}
