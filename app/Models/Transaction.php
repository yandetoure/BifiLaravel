<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'from_account',
        'to_account',
        'description',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function isDeposit()
    {
        return $this->type === 'deposit';
    }

    public function isSupervisorDeposit()
    {
        return $this->type === 'supervisor_deposit';
    }

    public function isTransfer()
    {
        return $this->type === 'transfer';
    }

    public function isWithdrawal()
    {
        return $this->type === 'withdrawal';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }

    public function scopeDepositsToday($query)
    {
        return $query->whereDate('created_at', today())->where('type', 'deposit');
    }

    public function scopeSupervisorDepositsToday($query)
    {
        return $query->whereDate('created_at', today())->where('type', 'supervisor_deposit');
    }
}
