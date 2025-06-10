<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Dashboard de l'agent
     */
    public function dashboard()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        // Utiliser la vue unifiée des balances
        $balanceData = Balance::getUnifiedBalanceView();
        $todayBalance = Balance::getTodayBalance();
        
        // Statistiques des factures
        $bills = Bill::with(['company', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        // Statistiques des paiements de l'agent pour aujourd'hui
        $todayPayments = Payment::where('agent_id', $user->id)
            ->whereDate('created_at', today())
            ->get();
        
        $todayStats = [
            'payments_count' => $todayPayments->count(),
            'total_amount' => $todayPayments->sum('amount'),
            'fees_generated' => $todayPayments->sum('fee_amount'),
        ];

        // Dernières factures en attente
        $pendingBills = Bill::where('status', 'pending')
            ->with(['company', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Mes derniers paiements
        $recentPayments = Payment::where('agent_id', $user->id)
            ->with(['bill.company'])
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('dashboard.agent', compact(
            'bills',
            'todayBalance',
            'balanceData',
            'todayStats',
            'pendingBills', 
            'recentPayments'
        ));
    }

    /**
     * Liste des factures à traiter
     */
    public function bills(Request $request)
    {
        $query = Bill::with(['company', 'user']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhere('client_number', 'like', "%{$search}%");
            });
        }

        $bills = $query->orderBy('created_at', 'desc')->paginate(20);
        $companies = Company::all();

        return view('agent.bills.index', compact('bills', 'companies'));
    }

    /**
     * Mettre à jour le statut d'une facture
     */
    public function updateBillStatus(Request $request, Bill $bill)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
            'cancellation_message' => 'required_if:status,cancelled|string|max:500',
        ]);

        $bill->update([
            'status' => $request->status,
            'cancellation_message' => $request->status === 'cancelled' ? $request->cancellation_message : null,
        ]);

        return redirect()->back()->with('success', 'Statut mis à jour avec succès!');
    }

    /**
     * Liste des paiements
     */
    public function payments(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $query = Payment::with(['bill.company', 'agent']);

        // Si c'est un agent simple, ne montrer que ses paiements
        if ($user->isAgent()) {
            $query->where('agent_id', $user->id);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $payments = $query->orderBy('created_at', 'desc')->paginate(20);

        return view('agent.payments.index', compact('payments'));
    }

    /**
     * Statistiques de l'agent
     */
    public function statistics()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        $todayPayments = Payment::where('agent_id', $user->id)
            ->whereDate('created_at', today())
            ->get();
            
        $monthPayments = Payment::where('agent_id', $user->id)
            ->whereMonth('created_at', Carbon::now()->month)
            ->get();
        
        $stats = [
            'today' => [
                'payments' => $todayPayments->count(),
                'amount' => $todayPayments->sum('amount'),
                'fees' => $todayPayments->sum('fee_amount'),
                'to_return' => $todayPayments->sum('amount') - $todayPayments->sum('fee_amount'),
            ],
            'month' => [
                'payments' => $monthPayments->count(),
                'amount' => $monthPayments->sum('amount'),
                'fees' => $monthPayments->sum('fee_amount'),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Versement bancaire par un agent (déduit de son solde caisse)
     */
    public function bankDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string|max:255'
        ]);

        DB::transaction(function () use ($request) {
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

            // Pour un agent, le versement est déduit de son solde caisse
            $todayBalance->decrement('cash_balance', $request->amount);
            $todayBalance->increment('wizall_current_balance', $request->amount);

            // Enregistrer la transaction
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'bank_deposit_agent',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Versement bancaire par agent',
            ]);
        });

        return redirect()->back()->with('success', 'Versement effectué avec succès');
    }
}
