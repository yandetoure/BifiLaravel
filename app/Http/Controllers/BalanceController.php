<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Payment;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BalanceController extends Controller
{
    public function index()
    {
        $todayBalance = Balance::getTodayBalance();
        $yesterdayBalance = Balance::getYesterdayBalance();
        
        if (!$todayBalance) {
            $todayBalance = $this->initializeTodayBalance($yesterdayBalance);
        }
        
        // Mettre à jour le solde courant avec les paiements du jour
        $this->updateCurrentBalance($todayBalance);
        
        return view('balances.index', compact('todayBalance', 'yesterdayBalance'));
    }
    
    public function initializeDay()
    {
        $yesterdayBalance = Balance::getYesterdayBalance();
        $todayBalance = Balance::getTodayBalance();
        
        if (!$todayBalance) {
            $todayBalance = $this->initializeTodayBalance($yesterdayBalance);
        }
        
        return redirect()->route('balances.index')->with('success', 'Soldes du jour initialisés avec succès!');
    }
    
    public function updateBalances(Request $request)
    {
        $request->validate([
            'wave_start_balance' => 'required|numeric|min:0',
            'orange_money_balance' => 'required|numeric|min:0',
            'cash_balance' => 'required|numeric|min:0',
        ]);
        
        $todayBalance = Balance::getTodayBalance();
        
        if (!$todayBalance) {
            return redirect()->back()->with('error', 'Aucun solde trouvé pour aujourd\'hui. Veuillez initialiser la journée.');
        }
        
        $todayBalance->update([
            'wave_start_balance' => $request->wave_start_balance,
            'wave_final_balance' => $request->wave_start_balance, // Sera mis à jour avec les transactions
            'orange_money_balance' => $request->orange_money_balance,
            'cash_balance' => $request->cash_balance,
        ]);
        
        $this->calculateTotalToReturn($todayBalance);
        
        return redirect()->back()->with('success', 'Soldes mis à jour avec succès!');
    }
    
    public function deposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'from_account' => 'required|in:cash,wizall,wave,orange_money',
            'description' => 'nullable|string|max:255',
        ]);
        
        DB::transaction(function () use ($request) {
            // Créer la transaction de versement
            Transaction::create([
                'user_id' => Auth::user()->id,
                'type' => 'deposit',
                'amount' => $request->amount,
                'from_account' => $request->from_account,
                'to_account' => 'bank',
                'description' => $request->description ?? 'Versement à la banque',
                'status' => 'completed',
            ]);
            
            // Mettre à jour les soldes
            $todayBalance = Balance::getTodayBalance();
            if ($todayBalance) {
                $this->updateBalanceAfterDeposit($todayBalance, $request->from_account, (float)$request->amount);
            }
        });
        
        return redirect()->back()->with('success', 'Versement enregistré avec succès!');
    }
    
    public function supervisorDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255',
        ]);
        
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est superviseur
        if (!$user->isSupervisor()) {
            return redirect()->back()->with('error', 'Seuls les superviseurs peuvent effectuer ce type de versement.');
        }
        
        DB::transaction(function () use ($request, $user) {
            // Créer la transaction de versement superviseur
            Transaction::create([
                'user_id' => $user->id,
                'type' => 'supervisor_deposit',
                'amount' => $request->amount,
                'from_account' => 'supervisor_funds',
                'to_account' => 'operational_funds',
                'description' => $request->description ?? 'Versement superviseur - fonds additionnels',
                'status' => 'completed',
            ]);
            
            // Ce montant devra être rendu au superviseur
            $todayBalance = Balance::getTodayBalance();
            if ($todayBalance) {
                $todayBalance->increment('total_to_return', $request->amount);
            }
        });
        
        return redirect()->back()->with('success', 'Versement superviseur enregistré avec succès!');
    }
    
    public function getBalanceData()
    {
        $todayBalance = Balance::getTodayBalance();
        $yesterdayBalance = Balance::getYesterdayBalance();
        
        if (!$todayBalance) {
            $todayBalance = $this->initializeTodayBalance($yesterdayBalance);
        }
        
        $this->updateCurrentBalance($todayBalance);
        
        return response()->json([
            'today' => $todayBalance,
            'yesterday' => $yesterdayBalance,
            'payments_today' => Payment::whereDate('created_at', today())->sum('total'),
            'transactions_today' => Transaction::whereDate('created_at', today())->get()
        ]);
    }
    
    private function initializeTodayBalance(?Balance $yesterdayBalance): Balance
    {
        $wizallStartBalance = $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0;
        $waveStartBalance = $yesterdayBalance ? $yesterdayBalance->wave_final_balance : 0;
        
        return Balance::create([
            'date' => today(),
            'wizall_start_balance' => $wizallStartBalance,
            'wizall_current_balance' => $wizallStartBalance,
            'wizall_final_balance' => $wizallStartBalance,
            'wave_start_balance' => $waveStartBalance,
            'wave_final_balance' => $waveStartBalance,
            'orange_money_balance' => 0,
            'cash_balance' => 0,
            'total_to_return' => 0,
        ]);
    }
    
    private function updateCurrentBalance(Balance $balance): void
    {
        // Calculer le total des paiements Wizall du jour
        $wizallPayments = Payment::whereDate('created_at', today())
            ->where('payment_method', 'wizall')
            ->sum('total');
        
        // Calculer le total des versements Wizall du jour
        $wizallDeposits = Transaction::whereDate('created_at', today())
            ->where('type', 'deposit')
            ->where('from_account', 'wizall')
            ->sum('amount');
        
        // Mettre à jour le solde courant Wizall
        $newCurrentBalance = $balance->wizall_start_balance - $wizallPayments + $wizallDeposits;
        
        $balance->update([
            'wizall_current_balance' => $newCurrentBalance,
            'wizall_final_balance' => $newCurrentBalance
        ]);
        
        $this->calculateTotalToReturn($balance);
    }
    
    private function updateBalanceAfterDeposit(Balance $balance, string $account, float $amount): void
    {
        switch ($account) {
            case 'wizall':
                $balance->increment('wizall_current_balance', $amount);
                $balance->increment('wizall_final_balance', $amount);
                break;
            case 'wave':
                $balance->increment('wave_final_balance', $amount);
                break;
            case 'cash':
                $balance->decrement('cash_balance', $amount);
                break;
            case 'orange_money':
                $balance->decrement('orange_money_balance', $amount);
                break;
        }
        
        $this->calculateTotalToReturn($balance);
    }
    
    private function calculateTotalToReturn(Balance $balance): void
    {
        // Calculer le total à rendre au superviseur
        $totalFunds = $balance->wizall_final_balance + 
                     $balance->wave_final_balance + 
                     $balance->orange_money_balance + 
                     $balance->cash_balance;
        
        // Ajouter les versements superviseur (fonds à rendre)
        $supervisorDeposits = Transaction::whereDate('created_at', today())
            ->where('type', 'supervisor_deposit')
            ->sum('amount');
        
        $balance->update(['total_to_return' => $totalFunds + $supervisorDeposits]);
    }
}
