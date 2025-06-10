<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\Deposit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    /**
     * Afficher la page de gestion des versements
     */
    public function index()
    {
        // Utiliser la vue unifiée des balances
        $balanceData = Balance::getUnifiedBalanceView();
        
        // Récupérer les derniers versements pour l'historique
        $deposits = \App\Models\Deposit::with('user')
            ->whereDate('deposit_date', today())
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('deposits.index', compact('balanceData', 'deposits'));
    }

    /**
     * Versement d'un agent (espèces vers Wizall)
     */
    public function agentDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $amount = (float) $request->amount;
                $description = $request->description;

                // Créer le versement
                $deposit = Deposit::agentCashDeposit($user, $amount, $description);
                
                // Appliquer à la balance et synchroniser
                $balance = Balance::getTodayBalanceOrCreate();
                $balance->applyDeposit($deposit);
                $balance->synchronizeBalances();
            });

            return back()->with('success', 'Versement agent effectué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du versement: ' . $e->getMessage());
        }
    }

    /**
     * Versement d'un superviseur (Wizall vers Wizall + dette agent)
     */
    public function supervisorDeposit(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        // Vérifier que l'utilisateur est superviseur ou admin
        if (!in_array(Auth::user()->role, ['supervisor', 'admin'])) {
            return back()->with('error', 'Seuls les superviseurs et admins peuvent effectuer ce type de versement.');
        }

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $amount = (float) $request->amount;
                $description = $request->description;

                // Créer le versement
                $deposit = Deposit::supervisorWizallDeposit($user, $amount, $description);
                
                // Appliquer à la balance et synchroniser
                $balance = Balance::getTodayBalanceOrCreate();
                $balance->applyDeposit($deposit);
                $balance->synchronizeBalances();
            });

            return back()->with('success', 'Versement superviseur effectué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du versement: ' . $e->getMessage());
        }
    }

    /**
     * Récupération d'espèces
     */
    public function cashCollection(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $amount = (float) $request->amount;
                $description = $request->description;

                // Créer l'opération
                $deposit = Deposit::cashCollection($user, $amount, $description);
                
                // Appliquer à la balance
                $balance = Balance::getTodayBalanceOrCreate();
                $balance->applyDeposit($deposit);
            });

            return back()->with('success', 'Récupération d\'espèces effectuée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la récupération: ' . $e->getMessage());
        }
    }

    /**
     * Rechargement Wizall
     */
    public function wizallRefill(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'description' => 'nullable|string|max:255'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $user = Auth::user();
                $amount = (float) $request->amount;
                $description = $request->description;

                // Créer l'opération
                $deposit = Deposit::wizallRefill($user, $amount, $description);
                
                // Appliquer à la balance
                $balance = Balance::getTodayBalanceOrCreate();
                $balance->applyDeposit($deposit);
            });

            return back()->with('success', 'Rechargement Wizall effectué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du rechargement: ' . $e->getMessage());
        }
    }

    /**
     * Afficher l'historique des versements
     */
    public function history(Request $request)
    {
        $query = Deposit::with('user');
        
        // Filtres
        if ($request->date) {
            $query->whereDate('deposit_date', $request->date);
        } else {
            $query->whereDate('deposit_date', today());
        }

        if ($request->type) {
            $query->where('deposit_type', $request->type);
        }

        if ($request->user_id) {
            $query->where('user_id', $request->user_id);
        }

        $deposits = $query->orderBy('created_at', 'desc')->paginate(20);
        
        // Utilisateurs pour le filtre
        $users = \App\Models\User::whereIn('role', ['agent', 'supervisor', 'admin'])
            ->orderBy('name')
            ->get();

        return view('deposits.history', compact('deposits', 'users'));
    }

    /**
     * API pour obtenir les statistiques en temps réel
     */
    public function getStats()
    {
        try {
            $balanceData = Balance::getUnifiedBalanceView();
            
            return response()->json([
                'success' => true,
                'balance_data' => $balanceData,
                'updated_at' => now()->format('H:i:s')
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Erreur lors de la récupération des données: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Réinitialiser les balances (admin uniquement)
     */
    public function resetBalance(Request $request)
    {
        if (Auth::user()->role !== 'admin') {
            return back()->with('error', 'Seuls les admins peuvent réinitialiser les balances.');
        }

        $request->validate([
            'wizall_amount' => 'required|numeric|min:0',
            'cash_amount' => 'required|numeric|min:0',
            'confirm' => 'required|accepted'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $balance = Balance::getTodayBalanceOrCreate();
                
                $balance->update([
                    'wizall_current_balance' => $request->wizall_amount,
                    'cash_balance' => $request->cash_amount,
                    'total_to_return' => 0,
                ]);

                // Enregistrer l'opération de réinitialisation
                Deposit::create([
                    'user_id' => Auth::id(),
                    'deposit_date' => today(),
                    'amount' => 0,
                    'deposit_type' => 'wizall_refill',
                    'source' => 'wizall',
                    'destination' => 'wizall',
                    'description' => 'Réinitialisation des balances par admin',
                    'transaction_details' => [
                        'operation' => 'Réinitialisation',
                        'wizall_set_to' => $request->wizall_amount,
                        'cash_set_to' => $request->cash_amount,
                    ]
                ]);
            });

            return back()->with('success', 'Balances réinitialisées avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la réinitialisation: ' . $e->getMessage());
        }
    }
}
