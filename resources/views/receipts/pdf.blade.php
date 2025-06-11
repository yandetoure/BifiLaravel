<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu #{{ $receipt_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            line-height: 1.4;
            color: #2d3748;
            padding: 10mm;
            background: #ffffff;
        }
        
        .container {
            max-width: 190mm;
            margin: 0 auto;
        }
        
        /* Header moderne avec gradient */
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 15px;
            display: table;
            width: 100%;
        }
        
        .header-left { display: table-cell; width: 25%; vertical-align: middle; }
        .header-center { display: table-cell; width: 50%; text-align: center; vertical-align: middle; }
        .header-right { display: table-cell; width: 25%; text-align: right; vertical-align: middle; }
        
        .logo { width: 50px; height: auto; border-radius: 4px; }
        .company-name { font-size: 16px; font-weight: bold; margin-bottom: 2px; }
        .company-tagline { font-size: 8px; opacity: 0.9; }
        
        .receipt-badge {
            background: rgba(255,255,255,0.9);
            color: #2d3748;
            padding: 8px;
            border-radius: 6px;
        }
        .receipt-title { font-size: 11px; font-weight: bold; }
        .receipt-number { font-size: 9px; color: #667eea; font-weight: bold; }
        .receipt-date { font-size: 7px; color: #4a5568; margin-top: 2px; }
        
        /* Contact bar */
        .contact-bar {
            background: #f8f9fa;
            padding: 6px 12px;
            margin-bottom: 12px;
            border-radius: 4px;
            border-left: 4px solid #667eea;
            font-size: 7px;
            color: #4a5568;
            text-align: center;
        }
        
        /* Layout en deux colonnes */
        .main-content { display: table; width: 100%; }
        .left-section { display: table-cell; width: 65%; vertical-align: top; padding-right: 10px; }
        .right-section { display: table-cell; width: 35%; vertical-align: top; }
        
        /* Cards modernes */
        .card {
            background: white;
            border-radius: 6px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            border: 1px solid #e2e8f0;
            margin-bottom: 10px;
            overflow: hidden;
        }
        
        .card-header {
            background: linear-gradient(135deg, #f7fafc 0%, #edf2f7 100%);
            padding: 6px 10px;
            border-bottom: 1px solid #e2e8f0;
        }
        
        .card-title {
            font-size: 9px;
            font-weight: bold;
            color: #2d3748;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .card-body { padding: 8px 10px; }
        
        .info-row {
            display: table;
            width: 100%;
            margin-bottom: 4px;
        }
        
        .info-label {
            display: table-cell;
            width: 45%;
            font-size: 8px;
            color: #4a5568;
            font-weight: 600;
        }
        
        .info-value {
            display: table-cell;
            width: 55%;
            font-size: 8px;
            color: #2d3748;
            font-weight: 500;
        }
        
        /* Section montants spéciale */
        .amounts-card {
            background: linear-gradient(135deg, #ebf8ff 0%, #e6fffa 100%);
            border: 2px solid #4299e1;
        }
        
        .amounts-card .card-header {
            background: linear-gradient(135deg, #4299e1 0%, #3182ce 100%);
            color: white;
        }
        
        .amount-row { display: table; width: 100%; margin-bottom: 4px; }
        .amount-label { display: table-cell; font-size: 9px; color: #2d3748; font-weight: 600; }
        .amount-value { display: table-cell; font-size: 9px; color: #2d3748; text-align: right; font-weight: bold; }
        
        .total-row {
            border-top: 2px solid #4299e1;
            margin-top: 6px;
            padding-top: 6px;
            background: rgba(66,153,225,0.1);
            border-radius: 4px;
            padding: 6px;
        }
        
        .total-row .amount-label,
        .total-row .amount-value {
            font-size: 11px;
            color: #1a365d;
            font-weight: bold;
        }
        
        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 10px;
            font-size: 7px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .badge-success {
            background: linear-gradient(135deg, #68d391 0%, #38a169 100%);
            color: white;
        }
        
        .badge-primary {
            background: linear-gradient(135deg, #63b3ed 0%, #3182ce 100%);
            color: white;
        }
        
        /* Agent discret */
        .agent-info {
            background: #f8f9fa;
            border-left: 3px solid #6c757d;
            padding: 4px 6px;
            margin: 6px 0;
            border-radius: 0 3px 3px 0;
        }
        
        .agent-label {
            font-size: 6px;
            color: #6c757d;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 1px;
        }
        
        .agent-name {
            font-size: 7px;
            color: #495057;
            font-weight: 600;
        }
        
        /* Footer */
        .footer {
            margin-top: 15px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: table;
            width: 100%;
        }
        
        .footer-info { display: table-cell; width: 70%; vertical-align: bottom; }
        .footer-signature { display: table-cell; width: 30%; text-align: center; vertical-align: bottom; }
        
        .footer-notes {
            font-size: 6px;
            color: #718096;
            line-height: 1.3;
        }
        
        .signature-box {
            padding: 6px;
            border: 1px dashed #cbd5e0;
            border-radius: 4px;
            background: #f7fafc;
        }
        
        .signature-title { font-size: 6px; color: #4a5568; font-weight: bold; margin-bottom: 2px; }
        .signature-image { width: 40px; height: auto; opacity: 0.8; }
        .signature-company { font-size: 6px; font-weight: bold; color: #667eea; margin-top: 1px; }
        
        /* Watermark */
        .watermark {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) rotate(-45deg);
            font-size: 35px;
            color: rgba(102,126,234,0.03);
            z-index: -1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="watermark">BICONSULTING</div>
    
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="header-left">
                <img src="{{ public_path('images/logobi.png') }}" alt="Logo" class="logo">
            </div>
            <div class="header-center">
                <div class="company-name">{{ $company_name ?? 'BICONSULTING' }}</div>
                <div class="company-tagline">Solutions de paiement digitales</div>
            </div>
            <div class="header-right">
                <div class="receipt-badge">
                    <div class="receipt-title">REÇU DE PAIEMENT</div>
                    <div class="receipt-number">#{{ $receipt_number }}</div>
                    <div class="receipt-date">{{ $date ?? now()->format('d/m/Y') }} à {{ now()->format('H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Contact -->
        <div class="contact-bar">
            📞 Admin: +221 76 159 19 59 • 📱 Commercial: {{ $company_phone ?? '+221 77 XXX XX XX' }} • ✉️ {{ $company_email ?? 'info@biconsulting.com' }} • 📍 Mermoz, Dakar
        </div>

        <!-- Contenu principal -->
        <div class="main-content">
            <!-- Gauche -->
            <div class="left-section">
                <!-- Client -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">👤 Informations Client</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Nom :</div>
                            <div class="info-value">{{ $client_name ?? $payment->bill->client_name ?? 'Non spécifié' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Email :</div>
                            <div class="info-value">{{ $payment->bill->user->email ?? 'Non fourni' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Téléphone :</div>
                            <div class="info-value">{{ $payment->bill->phone ?? 'Non fourni' }}</div>
                        </div>
                    </div>
                </div>

                <!-- Facture -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">📋 Facture</h3>
                    </div>
                    <div class="card-body">
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

                <!-- Transaction -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">💳 Transaction</h3>
                    </div>
                    <div class="card-body">
                        <div class="info-row">
                            <div class="info-label">Référence :</div>
                            <div class="info-value">{{ $payment->transaction_reference ?? 'N/A' }}</div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Méthode :</div>
                            <div class="info-value">
                                <span class="badge badge-primary">{{ strtoupper($payment->payment_method ?? 'N/A') }}</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Statut :</div>
                            <div class="info-value">
                                <span class="badge badge-success">✓ PAYÉ</span>
                            </div>
                        </div>
                        <div class="info-row">
                            <div class="info-label">Date :</div>
                            <div class="info-value">{{ $payment->transaction_date ? \Carbon\Carbon::parse($payment->transaction_date)->format('d/m/Y H:i') : 'N/A' }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Droite -->
            <div class="right-section">
                <!-- Montants -->
                <div class="card amounts-card">
                    <div class="card-header">
                        <h3 class="card-title">💰 Montants</h3>
                    </div>
                    <div class="card-body">
                        <div class="amount-row">
                            <div class="amount-label">Montant HT :</div>
                            <div class="amount-value">{{ number_format($payment->amount ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        <div class="amount-row">
                            <div class="amount-label">Frais (1%) :</div>
                            <div class="amount-value">{{ number_format($payment->fees ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                        
                        @if($payment->payment_method === 'cash' && isset($payment->amount_received))
                        <div class="amount-row">
                            <div class="amount-label">Reçu :</div>
                            <div class="amount-value">{{ number_format($payment->amount_received, 0, ',', ' ') }} FCFA</div>
                        </div>
                        @if(isset($payment->change_amount) && $payment->change_amount > 0)
                        <div class="amount-row">
                            <div class="amount-label">Rendu :</div>
                            <div class="amount-value">{{ number_format($payment->change_amount, 0, ',', ' ') }} FCFA</div>
                        </div>
                        @endif
                        @endif
                        
                        <div class="amount-row total-row">
                            <div class="amount-label">TOTAL TTC :</div>
                            <div class="amount-value">{{ number_format($payment->total ?? 0, 0, ',', ' ') }} FCFA</div>
                        </div>
                    </div>
                </div>

                <!-- Vérification -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">🔍 Vérification</h3>
                    </div>
                    <div class="card-body" style="text-align: center;">
                        <div style="font-size: 7px; color: #4a5568; margin-bottom: 3px;">Code de vérification</div>
                        <div style="font-family: monospace; font-size: 7px; background: #f1f5f9; padding: 3px; border-radius: 3px;">
                            {{ strtoupper(substr(md5($receipt_number . $payment->transaction_reference), 0, 10)) }}
                        </div>
                    </div>
                </div>

                <!-- Agent (très discret) -->
                @if($payment->agent)
                <div class="agent-info">
                    <div class="agent-label">Agent traitant</div>
                    <div class="agent-name">{{ $payment->agent->name }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-info">
                <div class="footer-notes">
                    <strong>📌 Important :</strong> Ce reçu certifie le paiement intégral • Conservez-le comme preuve<br>
                    Pour réclamation, contactez-nous sous 48h • Généré le {{ now()->format('d/m/Y à H:i') }}
                </div>
            </div>
            <div class="footer-signature">
                <div class="signature-box">
                    <div class="signature-title">Signature Autorisée</div>
                    <img src="{{ public_path('images/signature.jpeg') }}" alt="Signature" class="signature-image">
                    <div class="signature-company">BICONSULTING</div>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 