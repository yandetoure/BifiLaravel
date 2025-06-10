<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Models\Payment;
use App\Models\Receipt;
use App\Models\Balance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use thiagoalessio\TesseractOCR\TesseractOCR;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function create(Bill $bill)
    {
        // Toutes les factures non payées peuvent maintenant être payées
        if ($bill->isPaid()) {
            return redirect()->back()->with('error', 'Cette facture a déjà été payée.');
        }

        return view('payments.create', compact('bill'));
    }

    public function extractReceiptData(Request $request)
    {
        $request->validate([
            'receipt_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $image = $request->file('receipt_image');
            $imagePath = $image->storeAs('temp', uniqid() . '.' . $image->getClientOriginalExtension(), 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            // Utiliser Tesseract OCR
            $ocr = new TesseractOCR($fullPath);
            $ocr->lang('fra');
            $text = $ocr->run();

            $extractedData = $this->extractReceiptDataFromText($text);

            // Nettoyer le fichier temporaire
            Storage::disk('public')->delete($imagePath);

            return response()->json([
                'success' => true,
                'data' => $extractedData,
                'raw_text' => $text
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'extraction: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_id' => 'required|exists:bills,id',
            'client_name' => 'required|string|max:255',
            'transaction_reference' => 'required|string',
            'transaction_type' => 'required|string',
            'amount' => 'required|numeric|min:0',
            'fees' => 'required|numeric|min:0',
            'total' => 'required|numeric|min:0',
            'payment_method' => 'required|in:wizall,wave,orange_money,cash',
            'amount_received' => 'nullable|numeric|min:0',
            'change_method' => 'nullable|in:wave,om,cash|required_if:payment_method,cash',
            'transaction_date' => 'required|date',
            'proof_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'send_email' => 'nullable|boolean',
            'send_whatsapp' => 'nullable|boolean',
            'client_email' => 'nullable|email|required_if:send_email,true',
            'client_phone' => 'nullable|string|required_if:send_whatsapp,true'
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = $request->only([
                    'bill_id', 'client_name', 'transaction_reference', 'transaction_type',
                    'amount', 'fees', 'total', 'payment_method', 'transaction_date'
                ]);

                $data['agent_id'] = Auth::user()->id;
                $data['status'] = 'completed';

                // Gestion du calcul de monnaie pour les paiements en espèces
                if ($request->payment_method === 'cash' && $request->amount_received) {
                    $data['amount_received'] = $request->amount_received;
                    $changeAmount = max(0, $request->amount_received - $request->total);
                    $data['change_amount'] = $changeAmount;
                    $data['change_method'] = $request->change_method;

                    // Validation supplémentaire pour les espèces
                    if ($request->amount_received < $request->total) {
                        throw new \Exception('Le montant reçu ne peut pas être inférieur au total à payer.');
                    }
                }

                // Gérer l'upload de l'image de preuve
                if ($request->hasFile('proof_image')) {
                    $data['proof_image'] = $request->file('proof_image')->store('payment_proofs', 'public');
                }

                $payment = Payment::create($data);

                // Mettre à jour le statut de la facture
                $payment->bill->update(['status' => 'paid']);

                // Générer automatiquement le reçu
                $receipt = $this->generateReceipt($payment, $request->client_name);

                // Mettre à jour les soldes
                $this->updateBalances($payment);

                // Envoyer le reçu si demandé
                if ($request->send_email && $request->client_email) {
                    $this->sendReceiptByEmail($receipt, $request->client_email);
                }

                if ($request->send_whatsapp && $request->client_phone) {
                    $this->sendReceiptByWhatsapp($receipt, $request->client_phone);
                }

                session(['payment_id' => $payment->id]);
            });

            $payment = Payment::find(session('payment_id'));
            return redirect()->route('payments.success', $payment)->with('success', 'Paiement enregistré avec succès!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors du traitement du paiement: ' . $e->getMessage())->withInput();
        }
    }

    public function success(Payment $payment)
    {
        $payment->load(['bill.company', 'agent', 'receipt']);
        return view('payments.success', compact('payment'));
    }

    private function generateReceipt(Payment $payment, string $clientName): Receipt
    {
        // Générer le numéro de reçu
        $receiptNumber = $this->generateReceiptNumber();
        
        // Créer le PDF
        $pdfContent = $this->generatePDF($payment, $receiptNumber, $clientName);
        
        // Sauvegarder le PDF
        $fileName = 'receipt_' . $receiptNumber . '.pdf';
        $filePath = 'receipts/' . $fileName;
        Storage::disk('public')->put($filePath, $pdfContent);
        
        // Créer l'enregistrement du reçu
        return Receipt::create([
            'payment_id' => $payment->id,
            'receipt_number' => $receiptNumber,
            'client_name' => $clientName,
            'file_path' => $filePath,
        ]);
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
        
        $prefix = config('bifi.receipt.number_prefix');
        
        return "{$prefix}{$year}{$month}{$day}{$hour}{$minute}{$second}";
    }

    private function generatePDF(Payment $payment, string $receiptNumber, string $clientName): string
    {
        $payment->load(['bill.company', 'agent']);
        
        $data = [
            'payment' => $payment,
            'receipt_number' => $receiptNumber,
            'client_name' => $clientName,
            'date' => now()->format('d/m/Y'),
            'company_name' => config('bifi.company.name'),
            'company_email' => config('bifi.company.email'),
            'company_phone' => config('bifi.company.phone'),
        ];
        
        $pdf = Pdf::loadView('receipts.pdf', $data);
        
        return $pdf->output();
    }

    private function updateBalances(Payment $payment): void
    {
        $todayBalance = Balance::getTodayBalance();
        
        if (!$todayBalance) {
            $yesterdayBalance = Balance::getYesterdayBalance();
            $todayBalance = Balance::create([
                'date' => today(),
                'wizall_start_balance' => $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0,
                'wizall_current_balance' => $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0,
                'wizall_final_balance' => $yesterdayBalance ? $yesterdayBalance->wizall_final_balance : 0,
                'wave_start_balance' => $yesterdayBalance ? $yesterdayBalance->wave_final_balance : 0,
                'wave_final_balance' => $yesterdayBalance ? $yesterdayBalance->wave_final_balance : 0,
                'orange_money_balance' => 0,
                'cash_balance' => 0,
                'total_to_return' => 0,
            ]);
        }

        // Mettre à jour le solde selon la méthode de paiement
        if ($payment->payment_method === 'wizall') {
            $todayBalance->decrement('wizall_current_balance', $payment->total);
            $todayBalance->decrement('wizall_final_balance', $payment->total);
        }
        // Les autres méthodes (Wave, Orange Money, Cash) ne déduisent pas du solde car ils sont externes à Wizall
    }

    private function sendReceiptByEmail(Receipt $receipt, string $email): void
    {
        try {
            // TODO: Implémenter l'envoi par email avec Laravel Mail
            // Mail::to($email)->send(new ReceiptMail($receipt));
            $receipt->update(['sent_by_email' => true]);
        } catch (\Exception $e) {
            logger('Erreur envoi email: ' . $e->getMessage());
        }
    }

    private function sendReceiptByWhatsapp(Receipt $receipt, string $phone): void
    {
        try {
            // TODO: Implémenter l'envoi par WhatsApp API
            // $this->whatsappService->sendDocument($phone, $receipt->file_path);
            $receipt->update(['sent_by_whatsapp' => true]);
        } catch (\Exception $e) {
            logger('Erreur envoi WhatsApp: ' . $e->getMessage());
        }
    }

    /**
     * Recherche de paiements pour l'API
     */
    public function search(Request $request)
    {
        $search = $request->input('q');
        
        $payments = Payment::with(['bill.company', 'agent'])
            ->where('transaction_reference', 'like', "%{$search}%")
            ->orWhere('client_name', 'like', "%{$search}%")
            ->orWhereHas('bill', function($query) use ($search) {
                $query->where('bill_number', 'like', "%{$search}%")
                      ->orWhere('client_number', 'like', "%{$search}%");
            })
            ->orWhereHas('agent', function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get(['id', 'transaction_reference', 'client_name', 'amount', 'total', 'status', 'payment_method']);

        return response()->json($payments);
    }

    private function extractReceiptDataFromText(string $text): array
    {
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines);

        $data = [
            'transaction_reference' => '',
            'transaction_date' => '',
            'transaction_type' => '',
            'bill_reference' => '',
            'client_number' => '',
            'amount' => '',
            'fees' => '',
            'total' => ''
        ];

        foreach ($lines as $index => $line) {
            $line = strtoupper($line);

            // Référence de transaction
            if (strpos($line, 'REFERENCE') !== false && strpos($line, 'TRANSACTION') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['transaction_reference'] = trim($lines[$index + 1]);
                }
            }

            // Date de transaction
            if (strpos($line, 'DATE') !== false && strpos($line, 'TRANSACTION') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['transaction_date'] = trim($lines[$index + 1]);
                }
            }

            // Type de transaction
            if (strpos($line, 'TYPE') !== false && strpos($line, 'TRANSACTION') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['transaction_type'] = trim($lines[$index + 1]);
                }
            }

            // Référence facture
            if (strpos($line, 'REFERENCE') !== false && strpos($line, 'FACTURE') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['bill_reference'] = trim($lines[$index + 1]);
                }
            }

            // Numéro client
            if (strpos($line, 'NUMERO') !== false && strpos($line, 'CLIENT') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['client_number'] = trim($lines[$index + 1]);
                }
            }

            // Montant
            if (strpos($line, 'MONTANT') !== false && strpos($line, 'TOTAL') === false) {
                if (isset($lines[$index + 1])) {
                    $amount = $lines[$index + 1];
                    $amount = preg_replace('/[^\d,.]/', '', $amount);
                    $amount = str_replace(',', '.', $amount);
                    $data['amount'] = $amount;
                }
            }
        }

        // Calculer les frais (1% du montant)
        if (!empty($data['amount'])) {
            $amount = floatval($data['amount']);
            $fees = $amount * 0.01;
            $data['fees'] = number_format($fees, 0, '.', '');
            $data['total'] = number_format($amount + $fees, 0, '.', '');
        }

        return $data;
    }
}
