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
            'amount' => ''
        ];

        foreach ($lines as $index => $line) {
            $line = strtoupper($line);

            // Rechercher le nom de l'entreprise (souvent en début)
            if (empty($data['company_name']) && $index < 5) {
                if (strpos($line, 'CMA') !== false || strpos($line, 'COMPAGNIE') !== false) {
                    $data['company_name'] = $line;
                }
            }

            // Rechercher le numéro de facture
            if (strpos($line, 'FACTURE') !== false || strpos($line, 'NUMERO') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['bill_number'] = trim($lines[$index + 1]);
                }
            }

            // Rechercher le numéro client
            if (strpos($line, 'CLIENT') !== false || strpos($line, 'ABONNE') !== false) {
                if (isset($lines[$index + 1])) {
                    $data['client_number'] = trim($lines[$index + 1]);
                }
            }

            // Rechercher le montant
            if (strpos($line, 'MONTANT') !== false || strpos($line, 'TOTAL') !== false) {
                if (isset($lines[$index + 1])) {
                    $amount = $lines[$index + 1];
                    // Nettoyer le montant (enlever FCFA, espaces, etc.)
                    $amount = preg_replace('/[^\d,.]/', '', $amount);
                    $amount = str_replace(',', '.', $amount);
                    $data['amount'] = $amount;
                }
            }
        }

        return $data;
    }
}
