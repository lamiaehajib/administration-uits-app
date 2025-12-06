<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Reçu {{ $recu->numero_recu }}</title>
    <style>
        @page {
            size: A4;
            margin: 0;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.3;
            color: #333;
            background: white;
        }

        .page {
            width: 210mm;
            height: 297mm;
            padding: 10mm;
            margin: 0 auto;
            background: white;
            position: relative;
        }

        /* HEADER - M9ad */
        .header {
            margin-bottom: 8px;
            padding-bottom: 6px;
            border-bottom: 2px solid #e74c3c;
        }

        .header-content {
            display: table;
            width: 100%;
        }

        .logo-section {
            display: table-cell;
            width: 50%;
            vertical-align: top;
        }

        .logo {
            width: 300px;
            height: auto;
            margin-bottom: 3px;
        }

        .company-name {
            font-size: 11pt;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 2px;
        }

        .company-details {
            font-size: 7pt;
            color: #666;
            line-height: 1.4;
        }

        .receipt-info {
            display: table-cell;
            width: 60%;
            text-align: center;
            vertical-align: top;
        }

        .receipt-title {
            font-size: 20pt;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 3px;
        }

        .receipt-number {
            font-size: 12pt;
            font-weight: bold;
            color: #333;
            margin-bottom: 4px;
        }

        .status-row {
            margin-top: 3px;
        }

        .status-badge {
            display: inline-block;
            padding: 3px 10px;
            font-size: 7pt;
            font-weight: bold;
            border-radius: 3px;
            margin-left: 4px;
            text-transform: uppercase;
        }

        .status-success { background: #27ae60; color: white; }
        .status-warning { background: #f39c12; color: white; }
        .status-danger { background: #e74c3c; color: white; }
        .status-info { background: #3498db; color: white; }

        /* CLIENT INFO - M9ad */
        .info-row {
            display: table;
            width: 100%;
            margin: 8px 0;
        }

        .info-box {
            display: table-cell;
            width: 48%;
            vertical-align: top;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .info-box:first-child {
            margin-right: 4%;
        }

        .info-box-title {
            font-size: 9pt;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
            padding-bottom: 3px;
            border-bottom: 1px solid #ddd;
        }

        .info-line {
            font-size: 8pt;
            margin-bottom: 3px;
        }

        .info-label {
            display: inline-block;
            width: 75px;
            color: #666;
            font-weight: bold;
        }

        .info-value {
            color: #333;
        }

        /* GARANTIE - M9ad */
        .warranty-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 4px;
            padding: 6px;
            text-align: center;
            margin: 6px 0;
            width: 90%;
        }

        .warranty-title {
            font-size: 9pt;
            font-weight: bold;
            color: #856404;
            margin-bottom: 2px;
        }

        .warranty-text {
            font-size: 8pt;
            color: #856404;
        }

        /* ITEMS TABLE - M9ad */
        .items-table {
            width: 91%;
            border-collapse: collapse;
            margin: 8px 0;
            font-size: 8pt;
        }

        .items-table thead {
            background: #e74c3c;
            color: white;
        }

        .items-table th {
            padding: 6px 5px;
            text-align: left;
            font-size: 8pt;
            font-weight: bold;
        }

        .items-table td {
            padding: 4px 5px;
            border-bottom: 1px solid #eee;
        }

        .items-table tbody tr:last-child td {
            border-bottom: 2px solid #e74c3c;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        /* TOTALS - M9ad */
        .totals {
            width: 200px;
            margin-left: 500px;
            margin-top: 8px;
            font-size: 8pt;
        }

        .total-row {
            display: table;
            width: 100%;
            padding: 3px 0;
        }

        .total-label {
            display: table-cell;
            text-align: left;
            color: #666;
            font-weight: bold;
        }

        .total-value {
            display: table-cell;
            text-align: right;
            color: #333;
            font-weight: bold;
        }

        .total-final {
            background: #e74c3c;
            color: white;
            padding: 6px;
            margin-top: 4px;
            border-radius: 3px;
        }

        .total-final .total-label,
        .total-final .total-value {
            color: white;
            font-size: 10pt;
        }

        /* PAYMENTS - M9ad */
        .payments {
            width: 90%;
            background: #d4edda;
            border: 2px solid #28a745;
            border-radius: 4px;
            padding: 6px;
            margin: 8px 0;
        }

        .payments-title {
            font-size: 9pt;
            font-weight: bold;
            color: #155724;
            margin-bottom: 5px;
        }

        .payment-line {
            font-size: 7.5pt;
            padding: 2px 0;
            border-bottom: 1px dashed #c3e6cb;
        }

        .payment-line:last-child {
            border-bottom: none;
            margin-top: 5px;
            padding-top: 5px;
            border-top: 2px solid #28a745;
            font-weight: bold;
        }

        .payment-amount {
            float: right;
            color: #155724;
            font-weight: bold;
        }

        /* NOTES - M9ad */
        .notes {
            background: #fff3cd;
            border-left: 3px solid #ffc107;
            padding: 6px;
            margin: 8px 0;
            font-size: 8pt;
            color: #856404;
        }

        .notes-title {
            font-weight: bold;
            margin-bottom: 3px;
        }

        /* FOOTER - M9ad o mndmaj */
        .footer {
            position: absolute;
            bottom: 90mm;
            left: 10mm;
            right: 10mm;
            border-top: 2px solid #eee;
            padding-top: 6px;
        }

        .footer-content {
            display: table;
            width: 100%;
        }

        .footer-left {
            display: table-cell;
            width: 65%;
            vertical-align: middle;
            font-size: 7pt;
            color: #666;
            line-height: 1.5;
        }

        .footer-right {
            display: table-cell;
            width: 35%;
            text-align: center;
            vertical-align: middle;
        }

        .qr-code {
            width: 70px;
            height: 70px;
            border: 2px solid #3498db;
            border-radius: 4px;
            padding: 2px;
        }

        .qr-label {
            font-size: 6pt;
            color: #666;
            margin-top: 2px;
        }

        .signatures {
            display: table;
            width: 100%;
            margin-top: 8px;
        }

        .signature-box {
            display: table-cell;
            width: 50%;
            text-align: center;
        }

        .signature-line {
            width: 120px;
            border-top: 1px solid #333;
            margin: 0 auto;
            padding-top: 3px;
            font-size: 7pt;
            color: #666;
        }

        @media print {
            .page {
                margin: 0;
                padding: 0mm;
            }
        }
         .footer-text {
            font-size: 10px; /* Texte plus petit pour économiser de l'espace */
            text-align: center;
            margin-top: 70px;
            
            
        }
    </style>
</head>
<body>
    <div class="page">
        <!-- HEADER -->
        <div class="header">
            <div class="header-content">
                <div class="logo-section">
                    <img src="{{ public_path('images/logo ugcs-09.png') }}" alt="Logo" class="logo">
                    
                    
                </div>
                <div class="receipt-info">
                    <div class="receipt-title">REÇU</div>
                    <div class="receipt-number">{{ $recu->numero_recu }}</div>
                    <div style="font-size: 8pt; color: #666;">{{ $recu->created_at->format('d/m/Y à H:i') }}</div>
                    
                </div>
            </div>
        </div>

        <!-- CLIENT & EQUIPMENT INFO -->
        <div class="info-row">
            <div class="info-box" style="display: table-cell; width: 48%;">
                <div class="info-box-title">INFORMATIONS CLIENT</div>
                <div class="info-line">
                    <span class="info-label">Nom complet:</span>
                    <span class="info-value">{{ $recu->client_nom }} {{ $recu->client_prenom }}</span>
                </div>
                @if($recu->client_telephone)
                <div class="info-line">
                    <span class="info-label">Téléphone:</span>
                    <span class="info-value">{{ $recu->client_telephone }}</span>
                </div>
                @endif
                @if($recu->client_email)
                <div class="info-line">
                    <span class="info-label">Email:</span>
                    <span class="info-value">{{ $recu->client_email }}</span>
                </div>
                @endif
                @if($recu->client_adresse)
                <div class="info-line">
                    <span class="info-label">Adresse:</span>
                    <span class="info-value">{{ $recu->client_adresse }}</span>
                </div>
                @endif
            </div>

            <div class="info-box" style="display: table-cell; width: 48%; margin-left: 4%;">
                <div class="info-box-title">DÉTAILS ÉQUIPEMENT</div>
                @if($recu->equipement)
                <div class="info-line">
                    <span class="info-label">Équipement:</span>
                    <span class="info-value">{{ $recu->equipement }}</span>
                </div>
                @endif
                @if($recu->details)
                <div class="info-line">
                    <span class="info-label">Détails:</span>
                    <span class="info-value">{{ $recu->details }}</span>
                </div>
                @endif
                <div class="info-line">
                    <span class="info-label">Mode paiement:</span>
                    <span class="info-value">{{ strtoupper($recu->mode_paiement) }}</span>
                </div>
                <div class="info-line">
                    <span class="info-label">Vendeur:</span>
                    <span class="info-value">{{ $recu->user->name ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- GARANTIE -->
        @if($recu->type_garantie !== 'sans_garantie')
        <div class="warranty-box">
            <div class="warranty-title">GARANTIE</div>
            <div class="warranty-text">
                @if($recu->type_garantie === '90_jours')
                    Garantie de 90 jours - Valable jusqu'au {{ $recu->created_at->addDays(90)->format('d/m/Y') }}
                @elseif($recu->type_garantie === '180_jours')
                    Garantie de 180 jours - Valable jusqu'au {{ $recu->created_at->addDays(180)->format('d/m/Y') }}
                @elseif($recu->type_garantie === '360_jours')
                    Garantie de 360 jours - Valable jusqu'au {{ $recu->created_at->addDays(360)->format('d/m/Y') }}
                    @elseif($recu->type_garantie === '30_jours')
                    Garantie de 30 jours - Valable jusqu'au {{ $recu->created_at->addDays(30)->format('d/m/Y') }}
                @endif
            </div>
        </div>
        @endif

        <!-- ARTICLES -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%">#</th>
                    <th style="width: 45%">Produit</th>
                    <th style="width: 15%" class="text-center">Quantité</th>
                    <th style="width: 17%" class="text-right">Prix Unit.</th>
                    <th style="width: 18%" class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recu->items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->produit->nom }}</td>
                    <td class="text-center">{{ $item->quantite }}</td>
                    <td class="text-right">{{ number_format($item->prix_unitaire, 2) }} DH</td>
                    <td class="text-right">{{ number_format($item->total, 2) }} DH</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- TOTAUX -->
        <div class="totals">
            <div class="total-row">
                <div class="total-label">Sous-total</div>
                <div class="total-value">{{ number_format($recu->total + $recu->remise - ($recu->total * $recu->tva / 100), 2) }} DH</div>
            </div>
            @if($recu->remise > 0)
            <div class="total-row">
                <div class="total-label">Remise</div>
                <div class="total-value">- {{ number_format($recu->remise, 2) }} DH</div>
            </div>
            @endif
            @if($recu->tva > 0)
            <div class="total-row">
                <div class="total-label">TVA ({{ $recu->tva }}%)</div>
                <div class="total-value">+ {{ number_format($recu->total * $recu->tva / 100, 2) }} DH</div>
            </div>
            @endif
            <div class="total-row total-final">
                <div class="total-label">TOTAL À PAYER</div>
                <div class="total-value">{{ number_format($recu->total, 2) }} DH</div>
            </div>
        </div>

        <!-- PAIEMENTS -->
        @if($recu->paiements->count() > 0)
        <div class="payments">
            <div class="payments-title">HISTORIQUE DES PAIEMENTS</div>
            @foreach($recu->paiements as $paiement)
            <div class="payment-line">
                {{ $paiement->date_paiement->format('d/m/Y H:i') }} - {{ strtoupper($paiement->mode_paiement) }}
                @if($paiement->reference) (Réf: {{ $paiement->reference }}) @endif
                <span class="payment-amount">{{ number_format($paiement->montant, 2) }} DH</span>
            </div>
            @endforeach
            <div class="payment-line">
                RESTE À PAYER
                <span class="payment-amount">{{ number_format($recu->reste, 2) }} DH</span>
            </div>
        </div>
        @endif

        <!-- NOTES -->
        @if($recu->notes)
        <div class="notes">
            <div class="notes-title">Notes:</div>
            {{ $recu->notes }}
        </div>
        @endif

        <!-- FOOTER -->
        <div class="footer">
            <div class="footer-content">
                <div class="footer-left">
                    
                    
                    <div class="signatures">
                        <div class="signature-box">
                            <img  src="{{ public_path('images/signature-r.png') }}" alt="Stamp" style="width: 40px; height: 40px; margin-top:-40px;position: absolute;margin-left: 110px;">
                            <div class="signature-line">Signature vendeur</div>

                        </div>
                        <div class="signature-box">
                            <div class="signature-line">Signature client</div>
                        </div>
                    </div>
                </div>
                <div class="footer-right">
                    <img src="{{ public_path('images/scanie-r.jpg') }}" alt="QR Code" class="qr-code">
                    <div class="qr-label">Scannez pour plus d'infos</div>
                </div>
            </div>
            <div class="footer-text">
            <p style="margin: 2px;">NB: L'écran et le clavier et Circuit Alimentation ne font pas partie de la garantie</p>
            <p style="margin: 2px;">1er Étage, App 1, N° 68, Rue San Saëns, Belvédère, Casablanca 20300</p>
            <p style="margin: 2px;">06 55 79 44 42 | contact@uits.ma | www.uits.ma</p>
        </div>
        </div>
    </div>
</body>
</html>