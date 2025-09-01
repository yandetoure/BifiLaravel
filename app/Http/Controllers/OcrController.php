<?php declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Smalot\PdfParser\Parser;
use Illuminate\Support\Facades\Storage;

class OcrController extends Controller
{
    public function extractBillData(Request $request)
    {
        $request->validate([
            'bill_image' => 'required|file|mimes:jpeg,png,jpg,pdf|max:2048'
        ]);

        try {
            $file = $request->file('bill_image');
            $extension = strtolower($file->getClientOriginalExtension());
            $imagePath = $file->storeAs('temp', uniqid() . '.' . $extension, 'public');
            $fullPath = storage_path('app/public/' . $imagePath);

            if ($extension === 'pdf') {
                // Extraction PDF simple
                $parser = new Parser();
                $pdf = $parser->parseFile($fullPath);
                $text = $pdf->getText();
            } else {
                // Pour les images, on retourne juste un message indiquant qu'il faut utiliser un PDF
                Storage::disk('public')->delete($imagePath);
                return response()->json([
                    'success' => false,
                    'message' => 'Veuillez utiliser un fichier PDF pour l\'extraction automatique. Les images ne sont pas supportées pour le moment.'
                ], 400);
            }

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
        // Nettoyage du texte
        $text = preg_replace('/[ ]{2,}/', ' ', $text); // espaces multiples -> un espace
        $text = str_replace("\r", '', $text);
        $text = preg_replace('/\n+/', "\n", $text); // lignes vides multiples -> une seule
        $lines = explode("\n", $text);
        $lines = array_map('trim', $lines);
        $lines = array_filter($lines); // Enlever les lignes vides

        $data = [
            'company_name' => '',
            'bill_number' => '',
            'client_number' => '',
            'client_name' => '',
            'amount' => '',
            'bill_date' => ''
        ];

        // Entreprise : première ligne contenant 'CMA CGM'
        foreach ($lines as $line) {
            if (stripos($line, 'CMA CGM') !== false) {
                $data['company_name'] = trim($line);
                break;
            }
        }

        // Numéro de facture : gérer le cas où il est sur la ligne suivante après 'Facture No ORIGINAL'
        foreach ($lines as $i => $line) {
            if (preg_match('/Facture\s*No[^\n]*ORIGINAL/i', $line)) {
                // Chercher sur la ligne suivante un mot commençant par SNIM
                if (isset($lines[$i+1]) && preg_match('/S?NIM[0-9A-Z]+/i', $lines[$i+1], $m)) {
                    $data['bill_number'] = trim($m[0]);
                }
            }
        }
        // Si toujours vide, chercher le premier mot SNIM... dans tout le texte
        if (empty($data['bill_number']) && preg_match('/S?NIM[0-9A-Z]+/i', $text, $m)) {
            $data['bill_number'] = trim($m[0]);
        }

        // Numéro client
        if (preg_match('/Client\s*[:\s]*([0-9\/]+)/i', $text, $m)) {
            $data['client_number'] = trim($m[1]);
        }
        // Nom du client : chercher après "Doit:" et prendre toute la ligne
        if (preg_match('/Doit\s*[:\s]*([^\n]+)/i', $text, $m)) {
            $data['client_name'] = trim($m[1]);
        }

        // Montant TTC : chercher tous les 'Montant Total' et 'Total T.T.C.', prendre le plus grand
        $amounts = [];
        if (preg_match_all('/Montant\s*Total[:\s]*([0-9\., ]+)/iu', $text, $matches1)) {
            foreach ($matches1[1] as $val) {
                $val = str_replace([' ', ','], ['', '.'], trim($val));
                $amounts[] = floatval($val);
            }
        }
        if (preg_match_all('/Total\s*T\.?T\.?C?\.?\s*[:\.]?\s*([0-9\., ]+)/iu', $text, $matches2)) {
            foreach ($matches2[1] as $val) {
                $val = str_replace([' ', ','], ['', '.'], trim($val));
                $amounts[] = floatval($val);
            }
        }
        if (!empty($amounts)) {
            $data['amount'] = max($amounts);
        }
        // Date de la facture
        if (preg_match('/Date\s*[:\s]*([0-9]{2}-[A-Z]{3}-[0-9]{4})/i', $text, $m)) {
            $data['bill_date'] = trim($m[1]);
        }

        return $data;
    }
}
