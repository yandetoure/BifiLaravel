<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance for dashboard only.
     */
    public function __construct()
    {
        $this->middleware('auth')->only('dashboard');
    }

    /**
     * Show the application homepage (public page).
     */
    public function index()
    {
        // Page d'accueil publique - invitation à payer les factures CMA
        $companies = Company::all();
        return view('home', compact('companies'));
    }

    /**
     * Show dashboard based on user role
     */
    public function dashboard()
    {
        /** @var User|null $user */
        $user = auth()->user();

        if ($user && ($user->isAgent() || $user->isSupervisor())) {
            // Dashboard pour agent/superviseur
            $bills = Bill::with(['company', 'user'])
                ->orderBy('created_at', 'desc')
                ->paginate(10);
            
            return view('dashboard.agent', compact('bills'));
        }

        // Dashboard pour client
        $userBills = $user ? $user->bills()->with('company')->orderBy('created_at', 'desc')->get() : collect();
        return view('dashboard.client', compact('userBills'));
    }
}
