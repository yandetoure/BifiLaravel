<?php declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Balance extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'wizall_start_balance',
        'wizall_current_balance',
        'wizall_final_balance',
        'wave_start_balance',
        'wave_final_balance',
        'orange_money_balance',
        'cash_balance',
        'total_to_return',
    ];

    protected $casts = [
        'date' => 'date',
        'wizall_start_balance' => 'decimal:2',
        'wizall_current_balance' => 'decimal:2',
        'wizall_final_balance' => 'decimal:2',
        'wave_start_balance' => 'decimal:2',
        'wave_final_balance' => 'decimal:2',
        'orange_money_balance' => 'decimal:2',
        'cash_balance' => 'decimal:2',
        'total_to_return' => 'decimal:2',
    ];

    /**
     * Relations
     */
    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class, 'deposit_date', 'date');
    }

    public static function getTodayBalance()
    {
        return self::whereDate('date', today())->first();
    }

    public static function getYesterdayBalance()
    {
        return self::whereDate('date', today()->subDay())->first();
    }

    /**
     * Appliquer un versement à la balance et mettre à jour
     */
    public function applyDeposit(Deposit $deposit): void
    {
        DB::transaction(function () use ($deposit) {
            switch ($deposit->deposit_type) {
                case 'agent_cash_deposit':
                    // Agent verse: diminue caisse, augmente Wizall en cours
                    $this->cash_balance -= $deposit->amount;
                    $this->wizall_current_balance += $deposit->amount;
                    break;

                case 'supervisor_wizall_deposit':
                    // Superviseur verse: augmente Wizall temps réel, augmente ce que l'agent doit rendre
                    $this->wizall_current_balance += $deposit->amount;
                    $this->total_to_return += $deposit->amount;
                    break;

                case 'cash_collection':
                    // Récupération d'espèces
                    $this->cash_balance -= $deposit->amount;
                    break;

                case 'wizall_refill':
                    // Rechargement Wizall
                    $this->wizall_current_balance += $deposit->amount;
                    break;
            }

            $this->save();
        });
    }

    /**
     * Calculer le montant total que l'agent doit rendre
     */
    public function getAgentReturnAmount(): float
    {
        // Récupérer le dernier versement qui affecte le montant à rendre
        $lastAffectingDeposit = Deposit::whereDate('deposit_date', $this->date)
            ->where('affects_agent_return', true)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastAffectingDeposit) {
            return (float) $lastAffectingDeposit->agent_return_amount;
        }

        return (float) $this->total_to_return;
    }

    /**
     * Obtenir le résumé des versements d'aujourd'hui
     */
    public function getDepositsSummary(): array
    {
        $deposits = Deposit::whereDate('deposit_date', $this->date)->get();

        return [
            'agent_deposits' => $deposits->where('deposit_type', 'agent_cash_deposit')->sum('amount'),
            'supervisor_deposits' => $deposits->where('deposit_type', 'supervisor_wizall_deposit')->sum('amount'),
            'cash_collections' => $deposits->where('deposit_type', 'cash_collection')->sum('amount'),
            'wizall_refills' => $deposits->where('deposit_type', 'wizall_refill')->sum('amount'),
            'total_deposits' => $deposits->sum('amount'),
            'deposits_count' => $deposits->count(),
        ];
    }

    /**
     * Obtenir les versements détaillés d'aujourd'hui
     */
    public function getTodayDepositsDetails()
    {
        return Deposit::with('user')
            ->whereDate('deposit_date', $this->date)
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('deposit_type');
    }

    /**
     * Calculer l'impact des paiements sur les balances
     */
    public function updateBalanceFromPayments(): void
    {
        // Récupérer tous les paiements d'aujourd'hui
        $payments = \App\Models\Payment::whereDate('created_at', $this->date)->get();
        
        foreach ($payments as $payment) {
            switch ($payment->payment_method) {
                case 'wave':
                    // Les paiements Wave affectent la balance Wave
                    break;
                case 'orange_money':
                    // Les paiements Orange Money affectent la balance Orange Money
                    break;
                case 'wizall':
                    // Les paiements Wizall diminuent la balance Wizall courante
                    $this->wizall_current_balance -= $payment->amount;
                    break;
                case 'cash':
                    // Les paiements en espèces augmentent la balance cash
                    $this->cash_balance += $payment->amount;
                    break;
            }
        }
        
        $this->save();
    }

    /**
     * Créer ou obtenir la balance d'aujourd'hui
     */
    public static function getTodayBalanceOrCreate(): self
    {
        $balance = self::getTodayBalance();
        
        if (!$balance) {
            // Récupérer la balance d'hier pour initialiser
            $yesterdayBalance = self::getYesterdayBalance();
            
            $balance = self::create([
                'date' => today(),
                'wizall_start_balance' => $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0,
                'wizall_current_balance' => $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0,
                'wizall_final_balance' => 0,
                'wave_start_balance' => 0,
                'wave_final_balance' => 0,
                'orange_money_balance' => 0,
                'cash_balance' => 0,
                'total_to_return' => 0,
            ]);
        }
        
        return $balance;
    }

    /**
     * Obtenir une vue unifiée des balances pour tous les rôles
     * Cette méthode assure que admin, superviseur et agent voient les mêmes données
     */
    public static function getUnifiedBalanceView(): array
    {
        $balance = self::getTodayBalanceOrCreate();
        $deposits = Deposit::getTodayDeposits();
        $agentReturnAmount = $balance->getAgentReturnAmount();
        
        return [
            'date' => $balance->date->format('d/m/Y'),
            'wizall_current_balance' => $balance->wizall_current_balance,
            'wizall_start_balance' => $balance->wizall_start_balance,
            'wizall_final_balance' => $balance->wizall_final_balance,
            'wave_start_balance' => $balance->wave_start_balance,
            'wave_final_balance' => $balance->wave_final_balance,
            'orange_money_balance' => $balance->orange_money_balance,
            'cash_balance' => $balance->cash_balance,
            'total_to_return' => $balance->total_to_return,
            'agent_return_amount' => $agentReturnAmount,
            'deposits_summary' => $balance->getDepositsSummary(),
            'today_deposits' => $deposits,
            'last_update' => $balance->updated_at->format('H:i:s'),
        ];
    }

    /**
     * Mettre à jour les balances de manière synchronisée
     * Cette méthode est appelée après chaque opération pour maintenir la cohérence
     */
    public function synchronizeBalances(): void
    {
        DB::transaction(function () {
            // Recalculer les totaux des versements d'aujourd'hui
            $todayDeposits = Deposit::whereDate('deposit_date', $this->date)->get();
            
            // Calculer les balances basées sur les versements réels
            $agentCashDeposits = $todayDeposits->where('deposit_type', 'agent_cash_deposit')->sum('amount');
            $supervisorDeposits = $todayDeposits->where('deposit_type', 'supervisor_wizall_deposit')->sum('amount');
            $cashCollections = $todayDeposits->where('deposit_type', 'cash_collection')->sum('amount');
            $wizallRefills = $todayDeposits->where('deposit_type', 'wizall_refill')->sum('amount');
            
            // Calculer les impacts des paiements d'aujourd'hui
            $todayPayments = \App\Models\Payment::whereDate('created_at', $this->date)->get();
            $wizallPayments = $todayPayments->where('payment_method', 'wizall')->sum('amount');
            $cashPayments = $todayPayments->where('payment_method', 'cash')->sum('amount');
            
            // Mettre à jour les balances de manière cohérente
            $this->wizall_current_balance = $this->wizall_start_balance + $agentCashDeposits + $supervisorDeposits + $wizallRefills - $wizallPayments;
            $this->cash_balance = $this->cash_balance - $agentCashDeposits - $cashCollections + $cashPayments;
            $this->total_to_return = $supervisorDeposits + $this->getAgentReturnAmount();
            
            $this->save();
        });
    }
}
