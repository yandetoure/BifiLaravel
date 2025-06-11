<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $user = Auth::user();

        if ($user && ($user->isAgent() || $user->isSupervisor())) {
            // Rediriger vers le dashboard approprié
            if ($user->isAgent()) {
                return redirect()->route('agent.dashboard');
            } elseif ($user->isSupervisor()) {
                return redirect()->route('supervisor.dashboard');
            }
        }

        if ($user && $user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        // Dashboard pour client - récupérer les factures par nom/phone
        $userBills = $user ? Bill::where(function($query) use ($user) {
            $query->where('client_name', $user->name)
                  ->orWhere('phone', $user->phone);
        })->with('company')->orderBy('created_at', 'desc')->get() : collect();
        
        return view('dashboard.client', compact('userBills'));
    }
}
