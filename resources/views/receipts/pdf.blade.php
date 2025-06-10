<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reçu de Paiement #{{ $receipt_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 9px;
            line-height: 1.3;
            color: #2d3748;
            padding: 15px;
            background: #ffffff;
        }
        
        .container {
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Header compact */
        .header {
            display: table;
            width: 100%;
            margin-bottom: 20px;
            border-bottom: 2px solid #3182ce;
            padding-bottom: 10px;
        }
        
        .logo-section {
            display: table-cell;
            width: 30%;
            vertical-align: top;
        }
        
        .logo {
            width: 80px;
            height: auto;
        }
        
        .company-info {
            display: table-cell;
            width: 40%;
            vertical-align: top;
            text-align: center;
            padding: 0 10px;
        }
        
        .company-name {
            font-size: 14px;
            font-weight: bold;
            color: #3182ce;
            margin-bottom: 3px;
        }
        
        .company-details {
            font-size: 8px;
            color: #4a5568;
            line-height: 1.4;
        }
        
        .receipt-header {
            display: table-cell;
            width: 30%;
            vertical-align: top;
            text-align: right;
        }
        
        .receipt-title {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
        }
        
        .receipt-number {
            font-size: 10px;
            color: #3182ce;
            font-weight: bold;
        }
        
        .receipt-date {
            font-size: 8px;
            color: #718096;
            margin-top: 3px;
        }
        
        /* Grid layout pour optimiser l'espace */
        .content-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }
        
        .left-column, .right-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding: 0 5px;
        }
        
        /* Sections compactes */
        .section {
            background: #f7fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            padding: 8px;
            margin-bottom: 10px;
        }
        
        .section-title {
            font-size: 9px;
            font-weight: bold;
            color: #2d3748;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #cbd5e0;
            padding-bottom: 2px;
        }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 3px;
        }
        
        .info-label {
            display: table-cell;
            width: 50%;
            font-size: 8px;
            color: #4a5568;
            font-weight: 600;
        }
        
        .info-value {
            display: table-cell;
            width: 50%;
            font-size: 8px;
            color: #2d3748;
            text-align: right;
        }
        
        /* Section montants avec couleurs */
        .amounts-section {
            background: linear-gradient(135deg, #ebf8ff 0%, #e6fffa 100%);
            border: 2px solid #3182ce;
            border-radius: 6px;
            padding: 10px;
            margin: 15px 0;
        }
        
        .amount-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .amount-label {
            display: table-cell;
            font-size: 9px;
            color: #2d3748;
            font-weight: 600;
        }
        
        .amount-value {
            display: table-cell;
            font-size: 9px;
            color: #2d3748;
            text-align: right;
            font-weight: bold;
        }
        
        .total-row {
            border-top: 2px solid #3182ce;
            padding-top: 5px;
            margin-top: 8px;
        }
        
        .total-row .amount-label,
        .total-row .amount-value {
            font-size: 11px;
            color: #1a365d;
            font-weight: bold;
        }
        
        /* Section agent */
        .agent-section {
            background: #edf2f7;
            border-left: 4px solid #3182ce;
            padding: 8px;
            margin: 10px 0;
        }
        
        .agent-title {
            font-size: 8px;
            color: #4a5568;
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .agent-name {
            font-size: 9px;
            color: #2d3748;
            font-weight: bold;
        }
        
        /* Footer compact */
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
        }
        
        .footer-left {
            display: table-cell;
            width: 60%;
            vertical-align: bottom;
        }
        
        .footer-right {
            display: table-cell;
            width: 40%;
            text-align: center;
            vertical-align: bottom;
        }
        
        .signature-section {
            text-align: center;
        }
        
        .signature-label {
            font-size: 7px;
            color: #4a5568;
            margin-bottom: 5px;
        }
        
        .signature-image {
            width: 60px;
            height: auto;
        }
        
        .signature-name {
            font-size: 7px;
            font-weight: bold;
            color: #3182ce;
            margin-top: 3px;
        }
        
        .footer-notes {
            font-size: 7px;
            color: #718096;
            line-height: 1.3;
        }
        
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 60px;
            color: rgba(49, 130, 206, 0.05);
            z-index: -1;
            font-weight: bold;
        }
        
        /* Badges de statut */
        .status-badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-paid {
            background: #c6f6d5;
            color: #22543d;
            border: 1px solid #9ae6b4;
        }
        
        .payment-method {
            display: inline-block;
            padding: 2px 6px;
            background: #bee3f8;
            color: #2c5282;
            border-radius: 3px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        @media print {
            body {
                padding: 10px;
            }
            .watermark {
                font-size: 50px;
            }
        }
    </style>
</head>
<body>
    <div class="watermark">BICONSULTING</div>
    
    <div class="container">
        <!-- Header compact -->
        <div class="header">
            <div class="logo-section">
                <img src="{{ public_path('images/logobi.png') }}" alt="Logo" class="logo">
            </div>
            
            <div class="company-info">
                <div class="company-name">{{ $company_name ?? 'BICONSULTING' }}</div>
                <div class="company-details">
                    <div>Service administratif: +221 76 159 19 59</div>
                    <div>Service commercial: {{ $company_phone ?? '+221 77 XXX XX XX' }}</div>
                    <div>Email: {{ $company_email ?? 'info@biconsulting.com' }}</div>
                    <div>Mermoz, Dakar - BP 15350 DAKAR-FANN</div>
                </div>
            </div>
            
            <div class="receipt-header">
                <div class="receipt-title">REÇU DE PAIEMENT</div>
                <div class="receipt-number">#{{ $receipt_number }}</div>
                <div class="receipt-date">{{ $date ?? now()->format('d/m/Y') }}</div>
                <div class="receipt-date">Émis à {{ now()->format('H:i') }}</div>
            </div>
        </div>

        <!-- Contenu en deux colonnes -->
        <div class="content-grid">
            <!-- Colonne gauche -->
            <div class="left-column">
                <!-- Informations client -->
                <div class="section">
                    <div class="section-title">Informations Client</div>
                    <div class="info-row">
                        <div class="info-label">Nom :</div>
                        <div class="info-value">{{ $client_name ?? $payment->bill->client_name ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Email :</div>
                        <div class="info-value">{{ $payment->bill->user->email ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Téléphone :</div>
                        <div class="info-value">{{ $payment->bill->phone ?? 'N/A' }}</div>
                    </div>
                </div>

                <!-- Informations facture -->
                <div class="section">
                    <div class="section-title">Détails Facture</div>
                    <div class="info-row">
                        <div class="info-label">N° Facture :</div>
                        <div class="info-value">{{ $payment->bill->bill_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">N° Client :</div>
                        <div class="info-value">{{ $payment->bill->client_number ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Entreprise :</div>
                        <div class="info-value">{{ $payment->bill->company->name ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Colonne droite -->
            <div class="right-column">
                <!-- Informations transaction -->
                <div class="section">
                    <div class="section-title">Transaction</div>
                    <div class="info-row">
                        <div class="info-label">Référence :</div>
                        <div class="info-value">{{ $payment->transaction_reference ?? 'N/A' }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Mode :</div>
                        <div class="info-value">
                            <span class="payment-method">{{ strtoupper($payment->payment_method ?? 'N/A') }}</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Statut :</div>
                        <div class="info-value">
                            <span class="status-badge status-paid">PAYÉ</span>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Date :</div>
                        <div class="info-value">{{ $payment->transaction_date ? \Carbon\Carbon::parse($payment->transaction_date)->format('d/m/Y H:i') : 'N/A' }}</div>
                    </div>
                </div>

                <!-- Agent traitant -->
                @if($payment->agent)
                <div class="agent-section">
                    <div class="agent-title">AGENT TRAITANT</div>
                    <div class="agent-name">{{ $payment->agent->name }}</div>
                    <div style="font-size: 7px; color: #4a5568; margin-top: 2px;">
                        {{ $payment->agent->email }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Section montants en pleine largeur -->
        <div class="amounts-section">
            <div class="section-title" style="color: #1a365d; margin-bottom: 8px;">DÉTAIL DES MONTANTS</div>
            
            <div class="amount-row">
                <div class="amount-label">Montant HT (Hors Taxes) :</div>
                <div class="amount-value">{{ number_format($payment->amount ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
            
            <div class="amount-row">
                <div class="amount-label">Frais de service (1%) :</div>
                <div class="amount-value">{{ number_format($payment->fees ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
            
            @if($payment->payment_method === 'cash' && isset($payment->amount_received))
            <div class="amount-row">
                <div class="amount-label">Montant reçu :</div>
                <div class="amount-value">{{ number_format($payment->amount_received, 0, ',', ' ') }} FCFA</div>
            </div>
            
            @if(isset($payment->change_amount) && $payment->change_amount > 0)
            <div class="amount-row">
                <div class="amount-label">Monnaie rendue ({{ $payment->change_method }}) :</div>
                <div class="amount-value">{{ number_format($payment->change_amount, 0, ',', ' ') }} FCFA</div>
            </div>
            @endif
            @endif
            
            <div class="amount-row total-row">
                <div class="amount-label">TOTAL TTC (Toutes Taxes Comprises) :</div>
                <div class="amount-value">{{ number_format($payment->total ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-left">
                <div class="footer-notes">
                    <strong>Note importante :</strong><br>
                    • Ce reçu certifie le paiement complet de la facture mentionnée<br>
                    • Pour toute réclamation, contactez-nous dans les 48h<br>
                    • Document généré automatiquement le {{ now()->format('d/m/Y à H:i') }}<br>
                    • Conservez ce reçu comme preuve de paiement
                </div>
            </div>
            
            <div class="footer-right">
                <div class="signature-section">
                    <div class="signature-label">Signature autorisée</div>
                    <img src="{{ public_path('images/signature.jpeg') }}" alt="Signature" class="signature-image">
                    <div class="signature-name">BICONSULTING</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 