<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Deposit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'deposit_date',
        'amount',
        'deposit_type',
        'source',
        'destination',
        'description',
        'balance_before',
        'balance_after',
        'cash_balance_before',
        'cash_balance_after',
        'wizall_balance_before',
        'wizall_balance_after',
        'affects_agent_return',
        'agent_return_amount',
        'transaction_details',
    ];

    protected $casts = [
        'deposit_date' => 'date',
        'amount' => 'decimal:2',
        'balance_before' => 'decimal:2',
        'balance_after' => 'decimal:2',
        'cash_balance_before' => 'decimal:2',
        'cash_balance_after' => 'decimal:2',
        'wizall_balance_before' => 'decimal:2',
        'wizall_balance_after' => 'decimal:2',
        'affects_agent_return' => 'boolean',
        'agent_return_amount' => 'decimal:2',
        'transaction_details' => 'array',
    ];

    /**
     * Relations
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Versement d'un agent - diminue caisse, augmente Wizall en cours
     */
    public static function agentCashDeposit(User $user, float $amount, string $description = null): self
    {
        $balance = Balance::getTodayBalance();
        if (!$balance) {
            throw new \Exception('Aucune balance trouvée pour aujourd\'hui');
        }

        return self::create([
            'user_id' => $user->id,
            'deposit_date' => today(),
            'amount' => $amount,
            'deposit_type' => 'agent_cash_deposit',
            'source' => 'cash',
            'destination' => 'wizall',
            'description' => $description ?? "Versement agent: {$user->name}",
            'cash_balance_before' => $balance->cash_balance,
            'cash_balance_after' => $balance->cash_balance - $amount,
            'wizall_balance_before' => $balance->wizall_current_balance,
            'wizall_balance_after' => $balance->wizall_current_balance + $amount,
            'affects_agent_return' => false,
            'agent_return_amount' => 0,
            'transaction_details' => [
                'operation' => 'Agent verse espèces vers Wizall',
                'impact' => 'Diminue caisse, augmente Wizall en cours'
            ]
        ]);
    }

    /**
     * Versement d'un superviseur - augmente Wizall temps réel, augmente ce que l'agent doit rendre
     */
    public static function supervisorWizallDeposit(User $user, float $amount, string $description = null): self
    {
        $balance = Balance::getTodayBalance();
        if (!$balance) {
            throw new \Exception('Aucune balance trouvée pour aujourd\'hui');
        }

        // Calculer ce que l'agent doit maintenant rendre (total_to_return + amount)
        $newAgentReturnAmount = $balance->total_to_return + $amount;

        return self::create([
            'user_id' => $user->id,
            'deposit_date' => today(),
            'amount' => $amount,
            'deposit_type' => 'supervisor_wizall_deposit',
            'source' => 'wizall',
            'destination' => 'wizall',
            'description' => $description ?? "Versement superviseur: {$user->name}",
            'wizall_balance_before' => $balance->wizall_current_balance,
            'wizall_balance_after' => $balance->wizall_current_balance + $amount,
            'affects_agent_return' => true,
            'agent_return_amount' => $newAgentReturnAmount,
            'transaction_details' => [
                'operation' => 'Superviseur verse sur Wizall',
                'impact' => 'Augmente Wizall temps réel + augmente dette agent'
            ]
        ]);
    }

    /**
     * Récupération d'espèces
     */
    public static function cashCollection(User $user, float $amount, string $description = null): self
    {
        $balance = Balance::getTodayBalance();
        if (!$balance) {
            throw new \Exception('Aucune balance trouvée pour aujourd\'hui');
        }

        return self::create([
            'user_id' => $user->id,
            'deposit_date' => today(),
            'amount' => $amount,
            'deposit_type' => 'cash_collection',
            'source' => 'cash',
            'destination' => 'cash',
            'description' => $description ?? "Récupération espèces: {$user->name}",
            'cash_balance_before' => $balance->cash_balance,
            'cash_balance_after' => $balance->cash_balance - $amount,
            'affects_agent_return' => false,
            'agent_return_amount' => 0,
        ]);
    }

    /**
     * Rechargement Wizall
     */
    public static function wizallRefill(User $user, float $amount, string $description = null): self
    {
        $balance = Balance::getTodayBalance();
        if (!$balance) {
            throw new \Exception('Aucune balance trouvée pour aujourd\'hui');
        }

        return self::create([
            'user_id' => $user->id,
            'deposit_date' => today(),
            'amount' => $amount,
            'deposit_type' => 'wizall_refill',
            'source' => 'wizall',
            'destination' => 'wizall',
            'description' => $description ?? "Rechargement Wizall: {$user->name}",
            'wizall_balance_before' => $balance->wizall_current_balance,
            'wizall_balance_after' => $balance->wizall_current_balance + $amount,
            'affects_agent_return' => false,
            'agent_return_amount' => 0,
        ]);
    }

    /**
     * Calculer le total que l'agent doit rendre aujourd'hui
     */
    public static function getTodayAgentReturnAmount(): float
    {
        $balance = Balance::getTodayBalance();
        if (!$balance) {
            return 0.0;
        }

        // Récupérer le dernier versement qui affecte le montant à rendre
        $lastAffectingDeposit = self::whereDate('deposit_date', today())
            ->where('affects_agent_return', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastAffectingDeposit) {
            return (float) $lastAffectingDeposit->agent_return_amount;
        }

        return (float) $balance->total_to_return;
    }

    /**
     * Obtenir l'historique des versements d'aujourd'hui
     */
    public static function getTodayDeposits()
    {
        return self::with('user')
            ->whereDate('deposit_date', today())
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Obtenir les versements par type
     */
    public static function getDepositsByType(string $type, ?\Carbon\Carbon $date = null)
    {
        $query = self::with('user')->where('deposit_type', $type);
        
        if ($date) {
            $query->whereDate('deposit_date', $date);
        } else {
            $query->whereDate('deposit_date', today());
        }
        
        return $query->orderBy('created_at', 'desc')->get();
    }

    /**
     * Scope pour les versements d'aujourd'hui
     */
    public function scopeToday($query)
    {
        return $query->whereDate('deposit_date', today());
    }

    /**
     * Scope pour les versements qui affectent le retour agent
     */
    public function scopeAffectingAgentReturn($query)
    {
        return $query->where('affects_agent_return', true);
    }
}
