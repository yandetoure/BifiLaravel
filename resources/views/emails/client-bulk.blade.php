<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #e3f2fd;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #1976d2;
            margin-bottom: 10px;
        }
        .content {
            margin-bottom: 30px;
        }
        .greeting {
            font-size: 18px;
            color: #1976d2;
            margin-bottom: 15px;
        }
        .message {
            white-space: pre-wrap;
            margin-bottom: 20px;
            font-size: 16px;
            line-height: 1.8;
        }
        .footer {
            border-top: 1px solid #eee;
            padding-top: 20px;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        .contact-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #1976d2;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">Bifi - Système de Paiement</div>
            <p>Service de paiement de factures</p>
        </div>

        <div class="content">
            @if($recipient)
            <div class="greeting">
                Bonjour {{ $recipient->name }},
            </div>
            @else
            <div class="greeting">
                Bonjour,
            </div>
            @endif

            <div class="message">{{ $messageContent }}</div>

            <div class="contact-info">
                <strong>Besoin d'aide ?</strong><br>
                Connectez-vous à votre espace client pour consulter vos factures et effectuer vos paiements.<br>
                <a href="{{ url('/login') }}" class="btn">Accéder à mon espace</a>
            </div>
        </div>

        <div class="footer">
            <p><strong>Bifi - Système de Paiement</strong></p>
            <p>Email automatique - Ne pas répondre à cet email</p>
            <p>Pour toute question, contactez notre support client</p>
            
            <div style="margin-top: 15px; font-size: 12px; color: #999;">
                Cet email a été envoyé le {{ now()->format('d/m/Y à H:i') }}
            </div>
        </div>
    </div>
</body>
</html> 