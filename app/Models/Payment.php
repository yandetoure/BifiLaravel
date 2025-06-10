<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'bill_id',
        'agent_id',
        'client_name',
        'transaction_reference',
        'transaction_type',
        'amount',
        'fees',
        'fee_amount',
        'total',
        'amount_received',
        'change_amount',
        'change_method',
        'payment_method',
        'proof_image',
        'transaction_date',
        'status',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'fees' => 'decimal:2',
        'fee_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'amount_received' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'transaction_date' => 'datetime',
    ];

    protected static function booted()
    {
        // Calculer automatiquement les frais avant la sauvegarde
        static::saving(function ($payment) {
            if ($payment->amount) {
                // Frais à 1% du montant de la facture
                $payment->fee_amount = $payment->amount * 0.01;
                $payment->fees = $payment->fee_amount; // Compatibilité
                $payment->total = $payment->amount + $payment->fee_amount;
            }
        });

        // Mettre à jour le solde Wizall après création
        static::created(function ($payment) {
            $payment->updateWizallBalance();
        });
    }

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function receipt()
    {
        return $this->hasOne(Receipt::class);
    }

    public function calculateChange(): float
    {
        if ($this->amount_received && $this->total) {
            return max(0, $this->amount_received - $this->total);
        }
        return 0;
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Calcule le profit immédiat (0.8% des frais)
     */
    public function getImmediateProfitAttribute(): float
    {
        return $this->fee_amount * 0.8;
    }

    /**
     * Calcule la commission restante (0.2% des frais)
     */
    public function getRemainingCommissionAttribute(): float
    {
        return $this->fee_amount * 0.2;
    }

    /**
     * Met à jour le solde Wizall avec le profit immédiat
     */
    public function updateWizallBalance(): void
    {
        if ($this->isCompleted()) {
            $todayBalance = Balance::getTodayBalance();
            
            if (!$todayBalance) {
                $todayBalance = Balance::create([
                    'date' => today(),
                    'wizall_start_balance' => 0,
                    'wizall_current_balance' => 0,
                    'wizall_final_balance' => 0,
                    'wave_start_balance' => 0,
                    'wave_final_balance' => 0,
                    'orange_money_balance' => 0,
                    'cash_balance' => 0,
                    'total_to_return' => 0,
                ]);
            }

            // Ajouter le profit immédiat (0.8%) au solde Wizall
            $todayBalance->increment('wizall_current_balance', $this->immediate_profit);
        }
    }

    /**
     * Scope pour les paiements d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope pour les paiements d'un agent
     */
    public function scopeForAgent($query, $agentId)
    {
        return $query->where('agent_id', $agentId);
    }

    /**
     * Scope pour les paiements complétés
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
