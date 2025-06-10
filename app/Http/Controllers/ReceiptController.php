<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class ReceiptController extends Controller
{
    public function generate(Payment $payment)
    {
        // Vérifier si un reçu existe déjà
        $receipt = $payment->receipt;
        
        if (!$receipt) {
            // Générer le numéro de reçu
            $receiptNumber = $this->generateReceiptNumber();
            
            // Créer le PDF
            $pdfContent = $this->generatePDF($payment, $receiptNumber);
            
            // Sauvegarder le PDF
            $fileName = 'receipt_' . $receiptNumber . '.pdf';
            $filePath = 'receipts/' . $fileName;
            Storage::disk('public')->put($filePath, $pdfContent);
            
            // Créer l'enregistrement du reçu
            $receipt = Receipt::create([
                'payment_id' => $payment->id,
                'receipt_number' => $receiptNumber,
                'client_name' => $payment->bill->user->name ?? 'Client',
                'file_path' => $filePath,
            ]);
        }
        
        return view('receipts.show', compact('receipt', 'payment'));
    }
    
    public function download(Receipt $receipt)
    {
        $filePath = storage_path('app/public/' . $receipt->file_path);
        
        if (!file_exists($filePath)) {
            abort(404, 'Reçu non trouvé');
        }
        
        return response()->download($filePath, 'recu_' . $receipt->receipt_number . '.pdf');
    }
    
    public function sendByEmail(Receipt $receipt, Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        // TODO: Implémenter l'envoi par email
        $receipt->update(['sent_by_email' => true]);
        
        return redirect()->back()->with('success', 'Reçu envoyé par email avec succès!');
    }
    
    public function sendByWhatsapp(Receipt $receipt, Request $request)
    {
        $request->validate([
            'phone' => 'required|string'
        ]);
        
        // TODO: Implémenter l'envoi par WhatsApp
        $receipt->update(['sent_by_whatsapp' => true]);
        
        return redirect()->back()->with('success', 'Reçu envoyé par WhatsApp avec succès!');
    }
    
    private function generateReceiptNumber(): string
    {
        $now = now();
        $year = $now->format('y');
        $month = $now->format('m');
        $day = $now->format('d');
        $hour = $now->format('H');
        $minute = $now->format('i');
        $second = $now->format('s');
        
        return "NR{$year}{$month}{$day}{$hour}{$minute}{$second}";
    }
    
    private function generatePDF(Payment $payment, string $receiptNumber): string
    {
        $payment->load(['bill.company', 'agent']);
        
        $data = [
            'payment' => $payment,
            'receipt_number' => $receiptNumber,
            'date' => now()->format('d/m/Y'),
            'company_name' => 'BICONSULTING',
            'company_email' => 'diarrabicons@gmail.com',
            'company_phone' => '+221 78 705 67 67'
        ];
        
        $pdf = Pdf::loadView('receipts.pdf', $data);
        
        return $pdf->output();
    }

    /**
     * Afficher les reçus du client connecté
     */
    public function myReceipts()
    {
        $user = Auth::user();
        
        // Récupérer les reçus via les paiements des factures du client
        $receipts = Receipt::whereHas('payment.bill', function($query) use ($user) {
            $query->where(function($subQuery) use ($user) {
                $subQuery->where('client_name', $user->name)
                         ->orWhere('phone', $user->phone);
                
                // Si l'utilisateur a un email, chercher aussi par email dans les détails
                if ($user->email) {
                    $subQuery->orWhere('client_name', 'like', '%' . $user->email . '%');
                }
            });
        })->with(['payment.bill.company'])
          ->orderBy('created_at', 'desc')
          ->paginate(10);

        return view('receipts.my-receipts', compact('receipts'));
    }
}
