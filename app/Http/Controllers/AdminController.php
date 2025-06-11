<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Company;
use App\Models\Balance;
use App\Models\Transaction;
use App\Models\ClientMessage;
use App\Models\Notification;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::user() || Auth::user()->role !== 'admin') {
                abort(403, 'Accès non autorisé. Droits administrateur requis.');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        // Utiliser la vue unifiée des balances pour cohérence
        $balanceData = Balance::getUnifiedBalanceView();
        $todayBalance = Balance::getTodayBalance();
        
        // Statistiques du jour
        $today = today();
        $todayBills = Bill::whereDate('created_at', $today)->count();
        $todayPayments = Payment::whereDate('created_at', $today)->count();
        $todayRevenue = Payment::whereDate('created_at', $today)->sum('amount');
        $pendingBills = Bill::where('status', 'pending')->count();
        
        // Agents actifs
        $activeAgents = User::where('role', 'agent')->count();
        $activeSupervisors = User::where('role', 'supervisor')->count();
        
        // Alertes système
        $alerts = [];
        if ($todayBalance && $todayBalance->wizall_current_balance < 50000) {
            $alerts[] = [
                'type' => 'warning',
                'message' => 'Solde Wizall critique: ' . number_format((float) $todayBalance->wizall_current_balance, 0) . ' FCFA'
            ];
        }
        
        return view('dashboard.admin', compact(
            'balanceData',
            'todayBalance',
            'todayBills',
            'todayPayments',
            'todayRevenue',
            'pendingBills',
            'activeAgents',
            'activeSupervisors',
            'alerts'
        ));
    }

    public function users()
    {
        $users = User::orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function createUser()
    {
        return view('admin.users.create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:client,agent,supervisor,admin',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur créé avec succès!');
    }

    public function editUser(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function updateUser(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:client,agent,supervisor,admin',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users')->with('success', 'Utilisateur modifié avec succès!');
    }

    public function archiveUser(User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Seuls les admins peuvent supprimer/archiver
        if (!$currentUser->canDelete()) {
            return redirect()->back()->with('error', 'Seuls les administrateurs peuvent archiver des utilisateurs.');
        }

        // On ne supprime pas, on archive en changeant l'email pour éviter les conflits
        $user->update([
            'email' => 'archived_' . time() . '_' . $user->email,
            'role' => 'archived',
        ]);

        return redirect()->route('admin.users')->with('success', 'Utilisateur archivé avec succès!');
    }

    public function deleteUser(User $user)
    {
        /** @var \App\Models\User $currentUser */
        $currentUser = Auth::user();
        
        // Seuls les admins peuvent supprimer définitivement
        if (!$currentUser->isAdmin()) {
            return redirect()->back()->with('error', 'Seuls les administrateurs peuvent supprimer définitivement des utilisateurs.');
        }

        $user->delete();
        return redirect()->route('admin.users')->with('success', 'Utilisateur supprimé définitivement.');
    }

    public function bills()
    {
        $bills = Bill::with(['company', 'user'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.bills.index', compact('bills'));
    }

    public function payments()
    {
        $payments = Payment::with(['bill.company', 'agent'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        
        return view('admin.payments.index', compact('payments'));
    }

    public function companies()
    {
        $companies = Company::withCount('bills')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.companies.index', compact('companies'));
    }

    public function createCompany()
    {
        return view('admin.companies.create');
    }

    public function storeCompany(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
        ]);

        Company::create($request->all());

        return redirect()->route('admin.companies')->with('success', 'Entreprise créée avec succès!');
    }

    public function balances()
    {
        $balances = Balance::orderBy('date', 'desc')->paginate(20);
        return view('admin.balances.index', compact('balances'));
    }

    public function reports()
    {
        $startDate = request('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = request('end_date', now()->format('Y-m-d'));

        $payments = Payment::with(['bill.company', 'agent'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->get();

        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('total'),
            'total_fees' => $payments->sum('fees'),
            'by_method' => $payments->groupBy('payment_method')->map->count(),
            'by_company' => $payments->groupBy('bill.company.name')->map->count(),
            'by_agent' => $payments->groupBy('agent.name')->map->count(),
        ];

        return view('admin.reports.index', compact('payments', 'stats', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $payments = Payment::with(['bill.company', 'agent'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->where('status', 'completed')
            ->get();

        $filename = 'transactions_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($payments) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'Date',
                'Référence Transaction',
                'Entreprise',
                'Client',
                'Agent',
                'Montant',
                'Frais',
                'Total',
                'Méthode de Paiement',
                'Montant Reçu',
                'Monnaie',
                'Méthode de Rendu',
                'Statut'
            ]);

            // Données
            foreach ($payments as $payment) {
                fputcsv($file, [
                    $payment->created_at->format('d/m/Y H:i'),
                    $payment->transaction_reference,
                    $payment->bill->company->name ?? 'N/A',
                    $payment->client_name ?? 'N/A',
                    $payment->agent->name ?? 'N/A',
                    $payment->amount,
                    $payment->fees,
                    $payment->total,
                    $payment->payment_method,
                    $payment->amount_received ?? 'N/A',
                    $payment->change_amount ?? '0',
                    $payment->change_method ?? 'N/A',
                    $payment->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Supprimer une facture
     */
    public function deleteBill(Bill $bill)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Seuls les administrateurs peuvent supprimer des factures.');
        }

        $bill->delete();
        return redirect()->route('admin.bills.index')->with('success', 'Facture supprimée avec succès!');
    }

    /**
     * Mettre à jour le statut d'un paiement
     */
    public function updatePaymentStatus(Request $request, Payment $payment)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $payment->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut du paiement mis à jour avec succès!');
    }

    /**
     * Supprimer un paiement
     */
    public function deletePayment(Payment $payment)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Seuls les administrateurs peuvent supprimer des paiements.');
        }

        $payment->delete();
        return redirect()->route('admin.payments.index')->with('success', 'Paiement supprimé avec succès!');
    }

    /**
     * Modifier une entreprise
     */
    public function editCompany(Company $company)
    {
        return view('admin.companies.edit', compact('company'));
    }

    /**
     * Mettre à jour une entreprise
     */
    public function updateCompany(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email',
        ]);

        $company->update($request->all());

        return redirect()->route('admin.companies.index')->with('success', 'Entreprise modifiée avec succès!');
    }

    /**
     * Supprimer une entreprise
     */
    public function deleteCompany(Company $company)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        
        if (!$user->isAdmin()) {
            return redirect()->back()->with('error', 'Seuls les administrateurs peuvent supprimer des entreprises.');
        }

        $company->delete();
        return redirect()->route('admin.companies.index')->with('success', 'Entreprise supprimée avec succès!');
    }

    /**
     * Historique des balances
     */
    public function balanceHistory()
    {
        $balances = Balance::with('transactions')
            ->orderBy('date', 'desc')
            ->paginate(30);

        return view('admin.balances.history', compact('balances'));
    }

    /**
     * Ajustement manuel des balances
     */
    public function manualBalanceAdjustment(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'adjustment_type' => 'required|in:wizall,wave,orange_money,cash',
            'amount' => 'required|numeric',
            'reason' => 'required|string|max:500',
        ]);

        $balance = Balance::where('date', $request->date)->first();

        if (!$balance) {
            return redirect()->back()->with('error', 'Aucun solde trouvé pour cette date.');
        }

        // Mettre à jour le solde
        $column = match($request->adjustment_type) {
            'wizall' => 'wizall_final_balance',
            'wave' => 'wave_final_balance',
            'orange_money' => 'orange_money_balance',
            'cash' => 'cash_balance',
            default => $request->adjustment_type . '_final_balance'
        };
        
        if ($request->adjustment_type === 'wizall') {
            $balance->increment('wizall_current_balance', $request->amount);
        }
        $balance->increment($column, $request->amount);

        // Créer une transaction d'ajustement
        Transaction::create([
            'user_id' => Auth::user()->id,
            'type' => 'manual_adjustment',
            'amount' => $request->amount,
            'from_account' => $request->adjustment_type,
            'to_account' => $request->adjustment_type,
            'description' => 'Ajustement manuel: ' . $request->reason,
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Ajustement manuel effectué avec succès!');
    }

    /**
     * Export des balances
     */
    public function exportBalances(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $balances = Balance::whereBetween('date', [$startDate, $endDate])
            ->orderBy('date', 'desc')
            ->get();

        $filename = 'balances_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($balances) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'Date',
                'Wizall Début',
                'Wizall Actuel',
                'Wizall Final',
                'Wave Début',
                'Wave Final',
                'Orange Money',
                'Espèces',
                'Total à Rendre'
            ]);

            foreach ($balances as $balance) {
                fputcsv($file, [
                    $balance->date->format('d/m/Y'),
                    $balance->wizall_start_balance,
                    $balance->wizall_current_balance,
                    $balance->wizall_final_balance,
                    $balance->wave_start_balance,
                    $balance->wave_final_balance,
                    $balance->orange_money_balance,
                    $balance->cash_balance,
                    $balance->total_to_return
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Gestion des transactions
     */
    public function transactions()
    {
        $transactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Détails d'une transaction
     */
    public function showTransaction(Transaction $transaction)
    {
        $transaction->load('user');
        return view('admin.transactions.show', compact('transaction'));
    }

    /**
     * Mettre à jour le statut d'une transaction
     */
    public function updateTransactionStatus(Request $request, Transaction $transaction)
    {
        $request->validate([
            'status' => 'required|in:pending,completed,failed',
        ]);

        $transaction->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'Statut de la transaction mis à jour avec succès!');
    }

    /**
     * Rapport quotidien
     */
    public function dailyReport(Request $request)
    {
        $date = $request->input('date', now()->format('Y-m-d'));

        $payments = Payment::with(['bill.company', 'agent'])
            ->whereDate('created_at', $date)
            ->where('status', 'completed')
            ->get();

        $balance = Balance::where('date', $date)->first();

        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('total'),
            'by_method' => $payments->groupBy('payment_method')->map->sum('total'),
            'by_agent' => $payments->groupBy('agent.name')->map->count(),
        ];

        return view('admin.reports.daily', compact('payments', 'balance', 'stats', 'date'));
    }

    /**
     * Rapport mensuel
     */
    public function monthlyReport(Request $request)
    {
        $month = $request->input('month', now()->format('Y-m'));

        $payments = Payment::with(['bill.company', 'agent'])
            ->whereYear('created_at', date('Y', strtotime($month)))
            ->whereMonth('created_at', date('m', strtotime($month)))
            ->where('status', 'completed')
            ->get();

        $stats = [
            'total_payments' => $payments->count(),
            'total_amount' => $payments->sum('total'),
            'by_method' => $payments->groupBy('payment_method')->map->sum('total'),
            'by_company' => $payments->groupBy('bill.company.name')->map->sum('total'),
            'by_agent' => $payments->groupBy('agent.name')->map->sum('total'),
        ];

        return view('admin.reports.monthly', compact('payments', 'stats', 'month'));
    }

    /**
     * Rapport des agents
     */
    public function agentsReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $agents = User::whereIn('role', ['agent', 'supervisor'])
            ->withCount(['paymentsAsAgent' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->with(['paymentsAsAgent' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->get();

        return view('admin.reports.agents', compact('agents', 'startDate', 'endDate'));
    }

    /**
     * Rapport des entreprises
     */
    public function companiesReport(Request $request)
    {
        $startDate = $request->input('start_date', now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->input('end_date', now()->format('Y-m-d'));

        $companies = Company::withCount(['bills' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->with(['bills.payments' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->get();

        return view('admin.reports.companies', compact('companies', 'startDate', 'endDate'));
    }

    /**
     * Export de rapport personnalisé
     */
    public function exportCustomReport(Request $request)
    {
        $request->validate([
            'report_type' => 'required|in:payments,balances,agents,companies',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $reportType = $request->report_type;

        $filename = $reportType . '_report_' . $startDate . '_to_' . $endDate . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($reportType, $startDate, $endDate) {
            $file = fopen('php://output', 'w');
            
            switch ($reportType) {
                case 'payments':
                    $this->exportPaymentsData($file, $startDate, $endDate);
                    break;
                case 'balances':
                    $this->exportBalancesData($file, $startDate, $endDate);
                    break;
                case 'agents':
                    $this->exportAgentsData($file, $startDate, $endDate);
                    break;
                case 'companies':
                    $this->exportCompaniesData($file, $startDate, $endDate);
                    break;
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Configuration du système
     */
    public function settings()
    {
        // Récupérer les paramètres depuis la base de données ou fichier de config
        $settings = [
            'company_name' => config('bifi.company.name', 'B!consulting'),
            'company_email' => config('bifi.company.email', 'contact@biconsulting.biz'),
            'company_phone' => config('bifi.company.phone', '+221 XX XXX XX XX'),
            'receipt_prefix' => config('bifi.receipt.number_prefix', 'BIFI'),
            // Ajouter d'autres paramètres selon les besoins
        ];

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Statistiques en temps réel pour le dashboard
     */
    public function getDashboardStats()
    {
        $stats = [
            'today' => [
                'payments' => Payment::whereDate('created_at', today())->count(),
                'amount' => Payment::whereDate('created_at', today())->sum('total'),
                'bills' => Bill::whereDate('created_at', today())->count(),
                'users' => User::whereDate('created_at', today())->count(),
            ],
            'month' => [
                'payments' => Payment::whereMonth('created_at', now()->month)->count(),
                'amount' => Payment::whereMonth('created_at', now()->month)->sum('total'),
                'bills' => Bill::whereMonth('created_at', now()->month)->count(),
                'users' => User::whereMonth('created_at', now()->month)->count(),
            ]
        ];

        return response()->json($stats);
    }

    /**
     * Recherche d'utilisateurs pour autocomplétion
     */
    public function searchUsers(Request $request)
    {
        $search = $request->input('q');
        
        $users = User::where('name', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'email', 'role']);

        return response()->json($users);
    }

    /**
     * Recherche d'entreprises pour autocomplétion
     */
    public function searchCompanies(Request $request)
    {
        $search = $request->input('q');
        
        $companies = Company::where('name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name']);

        return response()->json($companies);
    }

    // Méthodes privées pour l'export

    private function exportPaymentsData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Date', 'Référence', 'Entreprise', 'Client', 'Agent', 'Montant', 'Total', 'Méthode']);
        
        Payment::with(['bill.company', 'agent'])
            ->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59'])
            ->chunk(100, function($payments) use ($file) {
                foreach ($payments as $payment) {
                    fputcsv($file, [
                        $payment->created_at->format('d/m/Y'),
                        $payment->transaction_reference,
                        $payment->bill->company->name ?? 'N/A',
                        $payment->client_name,
                        $payment->agent->name ?? 'N/A',
                        $payment->amount,
                        $payment->total,
                        $payment->payment_method
                    ]);
                }
            });
    }

    private function exportBalancesData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Date', 'Wizall Début', 'Wizall Final', 'Wave Final', 'Orange Money', 'Espèces']);
        
        Balance::whereBetween('date', [$startDate, $endDate])
            ->chunk(100, function($balances) use ($file) {
                foreach ($balances as $balance) {
                    fputcsv($file, [
                        $balance->date->format('d/m/Y'),
                        $balance->wizall_start_balance,
                        $balance->wizall_final_balance,
                        $balance->wave_final_balance,
                        $balance->orange_money_balance,
                        $balance->cash_balance
                    ]);
                }
            });
    }

    private function exportAgentsData($file, $startDate, $endDate)
    {
        fputcsv($file, ['Agent', 'Rôle', 'Paiements', 'Montant Total']);
        
        User::whereIn('role', ['agent', 'supervisor'])
            ->with(['paymentsAsAgent' => function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate . ' 23:59:59']);
            }])
            ->chunk(50, function($agents) use ($file) {
                foreach ($agents as $agent) {
                    fputcsv($file, [
                        $agent->name,
                        $agent->role,
                        $agent->paymentsAsAgent->count(),
                        $agent->paymentsAsAgent->sum('total')
                    ]);
                }
            });
    }

    private function exportCompaniesData($file, $startDate, $endDate)
    {
        $companies = Company::withCount(['bills' => function ($query) use ($startDate, $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }])->get();

        fputcsv($file, [
            'Nom',
            'Email',
            'Téléphone',
            'Adresse',
            'Factures (période)',
            'Date création'
        ]);

        foreach ($companies as $company) {
            fputcsv($file, [
                $company->name,
                $company->email,
                $company->phone,
                $company->address,
                $company->bills_count,
                $company->created_at->format('d/m/Y')
            ]);
        }
    }

    /**
     * Mise à jour en masse des factures
     */
    public function bulkUpdateBills(Request $request)
    {
        $request->validate([
            'bill_ids' => 'required|array',
            'bill_ids.*' => 'exists:bills,id',
            'status' => 'required|in:pending,confirmed,paid,cancelled',
        ]);

        $updatedCount = Bill::whereIn('id', $request->bill_ids)
            ->update(['status' => $request->status]);

        // Créer une notification
        $this->createNotification(
            'Mise à jour en masse',
            "{$updatedCount} factures ont été mises à jour vers le statut: {$request->status}",
            'success',
            ['admin']
        );

        return redirect()->back()->with('success', "{$updatedCount} factures mises à jour avec succès!");
    }

    /**
     * Gestion du solde Wizall
     */
    public function updateWizallBalance(Request $request)
    {
        $request->validate([
            'wizall_balance' => 'required|numeric|min:0',
            'reason' => 'required|string|max:500',
        ]);

        // Mettre à jour le solde Wizall du jour
        $today = now()->format('Y-m-d');
        $balance = Balance::firstOrCreate(['date' => $today]);
        
        $oldBalance = $balance->wizall_current_balance;
        $balance->update(['wizall_current_balance' => $request->wizall_balance]);

        // Créer une transaction d'ajustement
        Transaction::create([
            'user_id' => Auth::user()->id,
            'type' => 'wizall_adjustment',
            'amount' => $request->wizall_balance - $oldBalance,
            'from_account' => 'wizall',
            'to_account' => 'wizall',
            'description' => 'Ajustement solde Wizall: ' . $request->reason,
            'status' => 'completed',
        ]);

        return redirect()->back()->with('success', 'Solde Wizall mis à jour avec succès!');
    }

    /**
     * Interface de mailing
     */
    public function mailIndex()
    {
        $clients = User::where('role', 'client')->get();
        $recentEmails = DB::table('email_logs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.mail.index', compact('clients', 'recentEmails'));
    }

    /**
     * Envoyer un email à tous les clients
     */
    public function sendToAllClients(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
            'send_copy_to_admin' => 'boolean',
        ]);

        $clients = User::where('role', 'client')->get();
        $sentCount = 0;

        foreach ($clients as $client) {
            try {
                Mail::to($client->email)->send(new \App\Mail\ClientBulkMail(
                    $request->subject,
                    $request->message,
                    $client
                ));
                $sentCount++;

                // Log l'email
                DB::table('email_logs')->insert([
                    'to_email' => $client->email,
                    'subject' => $request->subject,
                    'status' => 'sent',
                    'sent_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur envoi email à {$client->email}: " . $e->getMessage());
                
                DB::table('email_logs')->insert([
                    'to_email' => $client->email,
                    'subject' => $request->subject,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'sent_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        // Envoyer une copie à l'admin si demandé
        if ($request->send_copy_to_admin) {
            Mail::to(Auth::user()->email)->send(new \App\Mail\ClientBulkMail(
                '[COPIE] ' . $request->subject,
                $request->message,
                Auth::user()
            ));
        }

        return redirect()->back()->with('success', "Email envoyé à {$sentCount} clients sur " . $clients->count());
    }

    /**
     * Envoyer un email personnalisé
     */
    public function sendCustomEmail(Request $request)
    {
        $request->validate([
            'recipients' => 'required|array',
            'recipients.*' => 'email',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $sentCount = 0;

        foreach ($request->recipients as $email) {
            try {
                $user = User::where('email', $email)->first();
                Mail::to($email)->send(new \App\Mail\ClientBulkMail(
                    $request->subject,
                    $request->message,
                    $user
                ));
                $sentCount++;

                DB::table('email_logs')->insert([
                    'to_email' => $email,
                    'subject' => $request->subject,
                    'status' => 'sent',
                    'sent_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } catch (\Exception $e) {
                Log::error("Erreur envoi email à {$email}: " . $e->getMessage());
                
                DB::table('email_logs')->insert([
                    'to_email' => $email,
                    'subject' => $request->subject,
                    'status' => 'failed',
                    'error' => $e->getMessage(),
                    'sent_by' => Auth::user()->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        return redirect()->back()->with('success', "Email envoyé à {$sentCount} destinataires");
    }

    /**
     * Interface notifications
     */
    public function notificationsIndex()
    {
        $notifications = \App\Models\Notification::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        $stats = [
            'total' => \App\Models\Notification::count(),
            'unread' => \App\Models\Notification::unread()->count(),
            'urgent' => \App\Models\Notification::urgent()->count(),
            'today' => \App\Models\Notification::whereDate('created_at', today())->count(),
        ];

        return view('admin.notifications.index', compact('notifications', 'stats'));
    }

    /**
     * Envoyer une notification
     */
    public function sendNotification(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:info,warning,error,success',
            'priority' => 'required|in:low,normal,high,urgent',
            'target_type' => 'required|in:all,role,user',
            'target_roles' => 'required_if:target_type,role|array',
            'target_users' => 'required_if:target_type,user|array',
        ]);

        $notificationData = [
            'title' => $request->title,
            'message' => $request->message,
            'type' => $request->type,
            'priority' => $request->priority,
        ];

        if ($request->target_type === 'all') {
            $notificationData['is_global'] = true;
        } elseif ($request->target_type === 'role') {
            $notificationData['target_roles'] = $request->target_roles;
        } else {
            // Pour chaque utilisateur spécifique
            foreach ($request->target_users as $userId) {
                $userNotification = $notificationData;
                $userNotification['user_id'] = $userId;
                \App\Models\Notification::create($userNotification);
            }
            
            return redirect()->back()->with('success', 'Notifications envoyées avec succès!');
        }

        \App\Models\Notification::create($notificationData);

        return redirect()->back()->with('success', 'Notification créée avec succès!');
    }

    /**
     * Marquer une notification comme lue
     */
    public function markNotificationRead(Request $request)
    {
        $request->validate([
            'notification_id' => 'required|exists:notifications,id',
        ]);

        $notification = \App\Models\Notification::find($request->notification_id);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /**
     * Supprimer une notification
     */
    public function deleteNotification(\App\Models\Notification $notification)
    {
        $notification->delete();
        return redirect()->back()->with('success', 'Notification supprimée');
    }

    /**
     * Obtenir les notifications non lues
     */
    public function getUnreadNotifications()
    {
        $user = Auth::user();
        $notifications = \App\Models\Notification::forUser($user)
            ->unread()
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return response()->json($notifications);
    }

    /**
     * Créer une notification helper
     */
    private function createNotification($title, $message, $type = 'info', $targetRoles = ['admin'])
    {
        \App\Models\Notification::create([
            'title' => $title,
            'message' => $message,
            'type' => $type,
            'target_roles' => $targetRoles,
        ]);
    }

    /**
     * Supprimer un message client
     */
    public function deleteClientMessage(ClientMessage $message)
    {
        $message->delete();
        return redirect()->back()->with('success', 'Message client supprimé avec succès');
    }
}
