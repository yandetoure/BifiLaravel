<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Payment;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SupervisorController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Vérifications des permissions
        if (!Auth::user()->isSupervisor() && !Auth::user()->isAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        $today = today();
        $todayBalance = Balance::getTodayBalance();
        
        // Statistiques du jour
        $todayPayments = Payment::whereDate('created_at', $today)->get();
        $todayRevenue = $todayPayments->sum('amount');
        $todayFees = $todayPayments->sum('fee_amount');
        $todayProfit = $todayFees * 0.8; // 0.8% de profit immédiat
        
        // Agents actifs
        $agents = User::where('role', 'agent')->get();
        $agentStats = [];
        
        foreach ($agents as $agent) {
            $agentPayments = $todayPayments->where('agent_id', $agent->id);
            $agentStats[] = [
                'agent' => $agent,
                'payments_count' => $agentPayments->count(),
                'total_amount' => $agentPayments->sum('amount'),
                'fees_generated' => $agentPayments->sum('fee_amount'),
            ];
        }
        
        // Alertes
        $alerts = [];
        if ($todayBalance && $todayBalance->wizall_current_balance < 50000) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Solde Wizall bas: ' . number_format($todayBalance->wizall_current_balance, 0) . ' FCFA'
            ];
        }
        
        return view('dashboard.supervisor', compact(
            'todayBalance',
            'todayRevenue',
            'todayFees',
            'todayProfit',
            'agentStats',
            'alerts'
        ));
    }

    public function balances()
    {
        $balances = Balance::orderBy('date', 'desc')->paginate(30);
        return view('supervisor.balances', compact('balances'));
    }

    public function bankDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'agent_id' => 'required|exists:users,id',
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

            // Pour un superviseur, le versement est ajouté à ce que l'agent doit rendre
            $todayBalance->increment('total_to_return', $request->amount);
            $todayBalance->increment('wizall_current_balance', $request->amount);

            // Enregistrer la transaction
            Transaction::create([
                'user_id' => Auth::id(),
                'type' => 'bank_deposit_supervisor',
                'amount' => $request->amount,
                'description' => $request->description ?? 'Versement bancaire par superviseur pour agent ' . $request->agent_id,
                'metadata' => json_encode(['agent_id' => $request->agent_id])
            ]);
        });

        return redirect()->route('supervisor.dashboard')
            ->with('success', 'Versement effectué avec succès');
    }

    public function endOfDayCalculation()
    {
        $todayBalance = Balance::getTodayBalance();
        if (!$todayBalance) {
            return redirect()->back()->with('error', 'Aucun solde trouvé pour aujourd\'hui');
        }

        $agents = User::where('role', 'agent')->get();
        $calculations = [];

        foreach ($agents as $agent) {
            $agentPayments = Payment::whereDate('created_at', today())
                ->where('agent_id', $agent->id)
                ->get();

            $totalCollected = $agentPayments->sum('amount');
            $totalFees = $agentPayments->sum('fee_amount');
            
            // Ce que l'agent doit rendre
            $toReturn = $totalCollected - $totalFees;
            
            $calculations[] = [
                'agent' => $agent,
                'total_collected' => $totalCollected,
                'total_fees' => $totalFees,
                'to_return' => $toReturn,
                'payments_count' => $agentPayments->count()
            ];
        }

        return view('supervisor.end-of-day', compact('calculations', 'todayBalance'));
    }

    public function agentDetails($agentId)
    {
        $agent = User::findOrFail($agentId);
        
        if (!$agent->isAgent()) {
            abort(404);
        }

        $todayPayments = Payment::whereDate('created_at', today())
            ->where('agent_id', $agentId)
            ->with('bill')
            ->get();

        $weekPayments = Payment::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->where('agent_id', $agentId)
            ->get();

        $monthPayments = Payment::whereMonth('created_at', now()->month)
            ->where('agent_id', $agentId)
            ->get();

        return view('supervisor.agent-details', compact(
            'agent',
            'todayPayments',
            'weekPayments',
            'monthPayments'
        ));
    }
} 