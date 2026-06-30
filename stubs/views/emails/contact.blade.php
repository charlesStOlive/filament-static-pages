<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau message de contact</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #2563eb, #f97316);
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .content {
            background: #f8fafc;
            padding: 30px;
            border-radius: 0 0 8px 8px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 6px;
            border-left: 4px solid #2563eb;
        }
        .field label {
            font-weight: bold;
            color: #2563eb;
            display: block;
            margin-bottom: 5px;
        }
        .field .value {
            color: #374151;
        }
        .message-content {
            background: white;
            padding: 20px;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            white-space: pre-wrap;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 Nouveau message de contact</h1>
        <p>Reçu le {{ $date }}</p>
    </div>
    
    <div class="content">
        <div class="field">
            <label>👤 Nom complet :</label>
            <div class="value">{{ $prenom }} {{ $nom }}</div>
        </div>
        
        <div class="field">
            <label>📧 Email :</label>
            <div class="value">{{ $email }}</div>
        </div>
        
        @if($telephone)
        <div class="field">
            <label>📞 Téléphone :</label>
            <div class="value">{{ $telephone }}</div>
        </div>
        @endif
        
        <div class="field">
            <label>📋 Objet :</label>
            <div class="value">{{ $objet }}</div>
        </div>
        
        <div class="field">
            <label>💬 Message :</label>
            <div class="message-content">{{ $content }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p>Ce message a été envoyé depuis le formulaire de contact de votre site web.</p>
        <p>Vous pouvez répondre directement à cet email pour contacter {{ $prenom }}.</p>
    </div>
</body>
</html>