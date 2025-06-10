<?php declare(strict_types=1); 

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bill;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ThirdPartyPaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();
            if (!in_array($user->role, ['agent', 'supervisor', 'admin'])) {
                abort(403, 'Accès non autorisé. Cette fonctionnalité est réservée aux agents, superviseurs et administrateurs.');
            }
            return $next($request);
        });
    }

    public function showThirdPartyForm()
    {
        // Vérifier que l'utilisateur est agent, superviseur ou admin
        $user = Auth::user();
        if (!in_array($user->role, ['agent', 'supervisor', 'admin'])) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('bills.third-party-payment');
    }
    
    public function searchClient(Request $request)
    {
        $request->validate([
            'search' => 'required|string|min:2',
        ]);
        
        $search = $request->search;
        
        // Rechercher par nom, email ou téléphone
        $clients = User::where('role', 'client')
            ->where(function($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                      ->orWhere('email', 'LIKE', "%{$search}%")
                      ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'name', 'email', 'phone']);
        
        return response()->json($clients);
    }
    
    public function processThirdPartyPayment(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'client_name' => 'required|string|max:255',
            'client_email' => 'required|email|max:255',
            'client_phone' => 'required|string|max:20',
            'description' => 'required|string|max:1000',
            'amount' => 'required|numeric|min:0.01',
            'facturier' => 'required|in:CMA CGM,RAPIDOSTAR,TIME,SDPWORLD,COSEC',
            'due_date' => 'required|date|after:today',
            'existing_client_id' => 'nullable|integer|exists:users,id',
        ]);
        
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!in_array($user->role, ['agent', 'supervisor', 'admin'])) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }
        
        $clientUser = null;
        
        // Si un client existant est sélectionné
        if ($request->existing_client_id) {
            $clientUser = User::find($request->existing_client_id);
        } else {
            // Vérifier si un client avec cet email existe déjà
            $clientUser = User::where('email', $request->client_email)->first();
            
            // Si le client n'existe pas, le créer
            if (!$clientUser) {
                $clientUser = User::create([
                    'name' => $request->client_name,
                    'email' => $request->client_email,
                    'phone' => $request->client_phone,
                    'role' => 'client',
                    'password' => Hash::make(Str::random(12)), // Mot de passe temporaire
                    'email_verified_at' => now(),
                ]);
                
                // Envoyer un email de création de compte
                // TODO: Implémenter l'envoi d'email avec mot de passe temporaire
            }
        }
        
        // Créer ou récupérer l'entreprise
        $company = \App\Models\Company::firstOrCreate(
            ['name' => $request->company_name],
            [
                'name' => $request->company_name,
                'address' => 'Adresse non spécifiée',
                'phone' => 'Non spécifié',
                'email' => 'contact@' . strtolower(str_replace(' ', '', $request->company_name)) . '.com',
            ]
        );
        
        // Créer la facture
        $bill = Bill::create([
            'company_id' => $company->id,
            'bill_number' => 'BILL-' . date('Y') . '-' . str_pad((string)(Bill::count() + 1), 6, '0', STR_PAD_LEFT),
            'client_number' => 'CLIENT-' . str_pad((string)$clientUser->id, 6, '0', STR_PAD_LEFT),
            'invoice_number' => 'INV-' . date('Y') . '-' . str_pad((string)(Bill::count() + 1), 6, '0', STR_PAD_LEFT),
            'company_name' => $request->company_name,
            'client_name' => $request->client_name,
            'phone' => $request->client_phone,
            'description' => $request->description,
            'amount' => $request->amount,
            'due_date' => $request->due_date,
            'facturier' => $request->facturier,
            'status' => 'pending', // Les paiements tiers peuvent être payés même en statut pending
            'user_id' => $user->id, // Créateur de la facture
            'client_user_id' => $clientUser->id, // Client bénéficiaire
            'paid_by_user_id' => $user->id, // Personne qui paie
            'is_third_party_payment' => true,
        ]);
        
        // Rediriger vers la page de paiement
        return redirect()->route('payments.create', $bill)
            ->with('success', 'Facture créée pour le client ' . $clientUser->name . '. Procédez au paiement.');
    }
    
    public function payForClient(Bill $bill)
    {
        $user = Auth::user();
        
        // Vérifier les permissions et que c'est bien un paiement tiers
        if (!in_array($user->role, ['agent', 'supervisor', 'admin']) || !$bill->is_third_party_payment) {
            abort(403, 'Accès non autorisé');
        }
        
        return view('bills.pay-for-client', compact('bill'));
    }
    
    public function processClientPayment(Request $request, Bill $bill)
    {
        $request->validate([
            'payment_method' => 'required|in:mobile_money,bank_card,bank_transfer',
            'phone' => 'required_if:payment_method,mobile_money|string|max:20',
            'operator' => 'required_if:payment_method,mobile_money|in:orange,free,expresso',
        ]);
        
        $user = Auth::user();
        
        // Vérifier les permissions
        if (!in_array($user->role, ['agent', 'supervisor', 'admin']) || !$bill->is_third_party_payment) {
            return response()->json(['error' => 'Accès non autorisé'], 403);
        }
        
        // Traiter le paiement (simulation)
        $transactionRef = 'TXN-' . date('YmdHis') . '-' . strtoupper(Str::random(6));
        
        $bill->update([
            'status' => 'paid',
            'paid_at' => now(),
            'transaction_reference' => $transactionRef,
            'payment_method' => $request->payment_method,
        ]);
        
        // Créer une notification pour le client
        \App\Models\Notification::create([
            'user_id' => $bill->client_user_id,
            'title' => 'Facture payée',
            'message' => "Votre facture #{$bill->invoice_number} a été payée par notre équipe.",
            'type' => 'success',
            'priority' => 'normal',
            'metadata' => [
                'bill_id' => $bill->id,
                'paid_by' => $user->name,
                'transaction_ref' => $transactionRef,
            ],
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Paiement effectué avec succès',
            'transaction_reference' => $transactionRef,
            'redirect_url' => route('bills.receipt', $bill)
        ]);
    }
}
