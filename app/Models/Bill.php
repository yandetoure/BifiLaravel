<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Bill extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bill_number',
        'client_number',
        'phone',
        'client_name',
        'company_name',
        'invoice_number',
        'description',
        'amount',
        'due_date',
        'facturier',
        'status',
        'client_user_id',
        'paid_by_user_id',
        'is_third_party_payment',
        'paid_at',
        'transaction_reference',
        'payment_method',
        'notes',
        'cancellation_message',
        'uploaded_file'
    ];

    protected $casts = [
        'due_date' => 'date',
        'paid_at' => 'datetime',
        'amount' => 'decimal:2',
        'is_third_party_payment' => 'boolean',
    ];

    // Pas de relation company - utilisation directe de company_name

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function clientUser()
    {
        return $this->belongsTo(User::class, 'client_user_id');
    }

    public function paidByUser()
    {
        return $this->belongsTo(User::class, 'paid_by_user_id');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isPaid()
    {
        return $this->status === 'paid';
    }

    public function isCancelled()
    {
        return $this->status === 'cancelled';
    }
}
