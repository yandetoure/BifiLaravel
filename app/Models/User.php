<?php declare(strict_types=1); 

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relations
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function paymentsAsAgent()
    {
        return $this->hasMany(Payment::class, 'agent_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // Helper methods
    public function isAgent()
    {
        return $this->role === 'agent';
    }

    public function isSupervisor()
    {
        return $this->role === 'supervisor';
    }

    public function isClient()
    {
        return $this->role === 'client';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function canManageAll()
    {
        return $this->isAdmin() || $this->isSupervisor();
    }

    public function canDelete()
    {
        return $this->isAdmin();
    }

    public function canArchive()
    {
        return !$this->isClient();
    }
}
