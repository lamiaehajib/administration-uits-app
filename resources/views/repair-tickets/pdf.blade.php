<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket #{{ $repairTicket->id }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 11px; 
            color: #333; 
            padding: 15px; 
        }
        
        .header { 
            text-align: center; 
            border-bottom: 3px solid #D32F2F; 
            padding-bottom: 10px; 
            margin-bottom: 12px; 
        }
        .header-img {
            width: 100px;
            height: auto;
            margin-bottom: 6px;
        }
        .header h1 { 
            font-size: 16px; 
            margin-bottom: 5px;
            color: #D32F2F;
        }
        .header p { 
            color: #666; 
            font-size: 9px;
            line-height: 1.4;
        }
        
        .ticket-number { 
            background: linear-gradient(135deg, #D32F2F 0%, #C2185B 100%);
            color: black;
            padding: 8px; 
            text-align: center; 
            margin-bottom: 12px; 
            border-radius: 4px; 
        }
        .ticket-number span { 
            font-size: 14px; 
            font-weight: bold; 
            letter-spacing: 0.5px;
        }
        
        .section { 
            margin-bottom: 10px; 
        }
        .section-title { 
            background: #D32F2F;
            color: white; 
            padding: 6px 10px; 
            font-weight: bold; 
            margin-bottom: 6px; 
            border-radius: 3px;
            font-size: 11px;
        }
        
        .row { 
            display: table; 
            width: 100%; 
            margin-bottom: 5px; 
        }
        .label { 
            display: table-cell; 
            width: 40%; 
            color: #666; 
            font-size: 10px;
        }
        .value { 
            display: table-cell; 
            width: 60%; 
            font-weight: bold; 
            font-size: 10px;
        }
        
        .payment-box { 
            border: 2px solid #D32F2F; 
            padding: 10px; 
            margin-top: 12px; 
            border-radius: 4px;
            background: #fff5f8;
        }
        .payment-row { 
            display: table; 
            width: 100%; 
            margin-bottom: 6px; 
        }
        .payment-label { 
            display: table-cell; 
            width: 50%; 
            font-size: 11px;
        }
        .payment-value { 
            display: table-cell; 
            width: 50%; 
            text-align: right; 
            font-size: 12px; 
            font-weight: bold; 
        }
        .payment-reste { 
            color: #C2185B; 
            font-size: 14px; 
        }
        
        .footer { 
            margin-top: 20px; 
            border-top: 2px solid #D32F2F; 
            padding-top: 12px; 
        }
        .signatures { 
            display: table; 
            width: 100%; 
            margin-top: 15px; 
        }
        .signature { 
            display: table-cell; 
            width: 50%; 
            text-align: center; 
        }
        .signature-line { 
            border-top: 2px solid #D32F2F; 
            width: 120px; 
            margin: 25px auto 5px; 
        }
        .signature p {
            font-size: 9px;
        }
        
        .terms { 
            margin-top: 15px; 
            padding: 8px; 
            background: #f9f9f9; 
            font-size: 8px; 
            color: #666; 
            border-left: 3px solid #D32F2F;
        }
        .terms h4 { 
            margin-bottom: 4px; 
            color: #D32F2F;
            font-size: 9px;
        }
        .terms p {
            line-height: 1.4;
        }
        
        .detail-box {
            background: #f9f9f9;
            padding: 8px;
            border-left: 3px solid #ef4444;
            margin-top: 6px;
            font-size: 10px;
        }
    </style>
</head>
<body>
    {{-- Header --}}
    <div class="header">
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
        <h1>SERVICE DE RÉPARATION</h1>
        <p><strong>Adresse:</strong> 1er Étage, App 1, N° 68, Rue San Saëns, Belvédère, Casablanca 20300</p>
        <p><strong>Tél:</strong> 06 55 79 44 42</p>
    </div>

    {{-- Ticket Number --}}
    <div class="ticket-number">
        <span>BON DE RÉPARATION N° {{ str_pad($repairTicket->id, 6, '0', STR_PAD_LEFT) }}</span>
    </div>

    {{-- Client Info --}}
    <div class="section">
        <div class="section-title">INFORMATIONS CLIENT</div>
        <div class="row">
            <div class="label">Nom Complet:</div>
            <div class="value">{{ $repairTicket->nom_complet }}</div>
        </div>
        <div class="row">
            <div class="label">Téléphone:</div>
            <div class="value">{{ $repairTicket->phone ?? '-' }}</div>
        </div>
    </div>

    {{-- Device Info --}}
    <div class="section">
        <div class="section-title">INFORMATIONS APPAREIL</div>
        <div class="row">
            <div class="label">Type d'appareil:</div>
            <div class="value">{{ $repairTicket->device_type }}</div>
        </div>
        <div class="row">
            <div class="label">Marque:</div>
            <div class="value">{{ $repairTicket->device_brand ?? '-' }}</div>
        </div>
        <div class="row">
            <div class="label">Problème:</div>
            <div class="value">{{ $repairTicket->problem_description ?? '-' }}</div>
        </div>
    </div>

    {{-- Dates --}}
    <div class="section">
        <div class="section-title">DATES</div>
        <div class="row">
            <div class="label">Date de dépôt:</div>
            <div class="value">{{ $repairTicket->date_depot->format('d/m/Y') }} à {{ $repairTicket->time_depot }}</div>
        </div>
        <div class="row">
            <div class="label">Date estimée de fin:</div>
            <div class="value">{{ $repairTicket->estimated_completion?->format('d/m/Y') ?? '-' }}</div>
        </div>
    </div>

    {{-- Payment --}}
    <div class="payment-box">
        <div class="payment-row">
            <div class="payment-label">Montant Total:</div>
            <div class="payment-value">{{ number_format($repairTicket->montant_total, 2) }} DH</div>
        </div>
        <div class="payment-row">
            <div class="payment-label">Avance versée:</div>
            <div class="payment-value">{{ number_format($repairTicket->avance, 2) }} DH</div>
        </div>
        <div class="payment-row">
            <div class="payment-label"><strong>RESTE À PAYER:</strong></div>
            <div class="payment-value payment-reste">{{ number_format($repairTicket->reste, 2) }} DH</div>
        </div>
    </div>

    {{-- Details --}}
    @if($repairTicket->details)
        <div class="section" style="margin-top: 12px;">
            <div class="section-title">DÉTAILS / REMARQUES</div>
            <div class="detail-box">{{ $repairTicket->details }}</div>
        </div>
    @endif

    {{-- Signatures --}}
    <div class="footer">
        <div class="signatures">
            <div class="signature">
                <div class="signature-line"></div>
                <p>Signature Client</p>
            </div>
            <div class="signature">
                <div class="signature-line"></div>
                <p>Signature Technicien</p>
            </div>
        </div>
    </div>

    {{-- Terms --}}
    <div class="terms">
        <h4>Conditions:</h4>
        <p>- Ce bon doit être présenté lors du retrait de l'appareil.</p>
        <p>- L'appareil doit être récupéré dans un délai de 30 jours après notification.</p>
        <p>- Le reste du paiement est dû lors de la récupération de l'appareil.</p>
    </div>
</body>
</html>