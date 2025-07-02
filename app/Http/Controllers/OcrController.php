<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use thiagoalessio\TesseractOCR\TesseractOCR;

class OcrController extends Controller
{
    public function extractBillData(Request $request)
    {
        $request->validate([
            'bill_image' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $image = $request->file('bill_image');
            $imagePath = $image->storeAs('temp', uniqid() . '.' . $image->getClientOriginalExtension(), 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            // Utiliser Tesseract OCR pour extraire le texte
            $ocr = new TesseractOCR($fullPath);
            $ocr->lang('fra'); // Français
            $text = $ocr->run();

            // Extraire les données de la facture
            $extractedData = $this->extractDataFromText($text);

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

    private function extractDataFromText(string $text): array
    {
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines); // Enlever les lignes vides

        $data = [
            'company_name' => '',
            'bill_number' => '',
            'client_number' => '',
            'client_name' => '',
            'amount' => ''
        ];

        foreach ($lines as $index => $line) {
            $upperLine = strtoupper($line);

            // Entreprise (Payable à ou en-tête)
            if (empty($data['company_name'])) {
                if (strpos($upperLine, 'PAYABLE') !== false && isset($lines[$index + 1])) {
                    $data['company_name'] = trim($lines[$index + 1]);
                } elseif ($index < 5 && (strpos($upperLine, 'CMA') !== false || strpos($upperLine, 'COMPAGNIE') !== false)) {
                    $data['company_name'] = $line;
                }
            }

            // Numéro de facture (Facture No)
            if (empty($data['bill_number']) && preg_match('/FACTURE\s*NO/i', $line)) {
                // Cherche le numéro sur la même ligne ou la suivante
                if (preg_match('/FACTURE\s*NO\s*[:#-]?\s*([A-Z0-9]+)/i', $line, $matches)) {
                    $data['bill_number'] = $matches[1];
                } elseif (isset($lines[$index + 1])) {
                    $possible = trim($lines[$index + 1]);
                    if (preg_match('/^[A-Z0-9-]+$/', $possible)) {
                        $data['bill_number'] = $possible;
                    }
                }
            }

            // Numéro client (Client)
            if (empty($data['client_number']) && preg_match('/CLIENT/i', $line)) {
                if (preg_match('/CLIENT\s*[:#-]?\s*([0-9A-Z\/]+)/i', $line, $matches)) {
                    $data['client_number'] = $matches[1];
                } elseif (isset($lines[$index + 1])) {
                    $possible = trim($lines[$index + 1]);
                    if (preg_match('/^[0-9A-Z\/]+$/', $possible)) {
                        $data['client_number'] = $possible;
                    }
                }
            }

            // Nom du client (Doit:)
            if (empty($data['client_name']) && preg_match('/DOIT\s*:/i', $line)) {
                // Prend la partie après le ':' ou la ligne suivante
                $parts = explode(':', $line, 2);
                if (isset($parts[1]) && trim($parts[1]) !== '') {
                    $data['client_name'] = trim($parts[1]);
                } elseif (isset($lines[$index + 1])) {
                    $data['client_name'] = trim($lines[$index + 1]);
                }
            }

            // Montant TTC (Montant Total)
            if (empty($data['amount']) && preg_match('/MONTANT\s*TOTAL/i', $line)) {
                // Cherche le montant sur la même ligne ou la suivante
                if (preg_match('/MONTANT\s*TOTAL\s*[:#-]?\s*([\d., ]+)/i', $line, $matches)) {
                    $amount = $matches[1];
                } elseif (isset($lines[$index + 1])) {
                    $amount = $lines[$index + 1];
                } else {
                    $amount = '';
                }
                // Nettoyer le montant (enlever FCFA, XOF, espaces, etc.)
                $amount = preg_replace('/[^\d,.]/', '', $amount);
                $amount = str_replace(',', '.', $amount);
                $data['amount'] = $amount;
            }
        }

        return $data;
    }
}
