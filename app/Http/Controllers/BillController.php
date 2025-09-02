<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class BillController extends Controller
{
    public function create()
    {
        return view('bills.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'company_name' => 'required|string|max:255',
            'bill_number' => 'required|string',
            'client_number' => 'required|string',
            'client_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'amount' => 'required|numeric|min:0',
            'uploaded_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
        ]);

        $data = $request->only(['company_name', 'bill_number', 'client_number', 'client_name', 'phone', 'amount']);
        $data['status'] = 'pending'; // Statut par défaut

        // Associer à l'utilisateur connecté s'il existe
        if (Auth::check()) {
            $data['user_id'] = Auth::id();
        }

        // Gérer l'upload de fichier
        if ($request->hasFile('uploaded_file')) {
            $data['uploaded_file'] = $request->file('uploaded_file')->store('bills', 'public');
        }

        $bill = Bill::create($data);

        return redirect()->route('bills.success')->with([
            'success' => 'Votre demande de paiement a été soumise avec succès!',
            'bill' => $bill
        ]);
    }

    public function show(Bill $bill)
    {
        $bill->load(['user', 'payments.agent']);

        // Si c'est une requête AJAX, retourner une vue partielle
        if (request()->ajax()) {
            return view('bills.show-modal', compact('bill'));
        }

        return view('bills.show', compact('bill'));
    }

    public function updateStatus(Request $request, Bill $bill)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,paid',
            'cancellation_message' => 'required_if:status,cancelled'
        ]);

        $updateData = ['status' => $request->status];

        if ($request->status === 'cancelled' && $request->cancellation_message) {
            $updateData['cancellation_message'] = $request->cancellation_message;
        }

        $bill->update($updateData);

        // Pour les requêtes AJAX (agents), retourner JSON
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Statut de la facture mis à jour avec succès!',
                'status' => $request->status
            ]);
        }

        return redirect()->back()->with('success', 'Statut de la facture mis à jour!');
    }

    public function success()
    {
        $bill = session('bill');
        return view('bills.success', compact('bill'));
    }

    /**
     * Recherche de factures pour l'API
     */
    public function search(Request $request)
    {
        $search = $request->input('q');

        $bills = Bill::with(['user'])
            ->where('bill_number', 'like', "%{$search}%")
            ->orWhere('client_number', 'like', "%{$search}%")
            ->orWhere('company_name', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'bill_number', 'client_number', 'amount', 'status', 'company_name']);

        return response()->json($bills);
    }

    /**
     * Afficher les factures du client connecté
     */
    public function myBills()
    {
        $user = Auth::user();
        $bills = Bill::where(function($query) use ($user) {
                        $query->where('client_name', $user->name)
                              ->orWhere('phone', $user->phone);

                        // Si l'utilisateur a un email, chercher aussi par email dans les détails
                        if ($user->email) {
                            $query->orWhere('client_name', 'like', '%' . $user->email . '%');
                        }
                    })
                    ->with(['payments.receipt'])
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

        return view('bills.my-bills', compact('bills'));
    }
}
