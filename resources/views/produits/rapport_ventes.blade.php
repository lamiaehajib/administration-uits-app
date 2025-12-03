<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Rapport de Ventes Produits - {{ \Carbon\Carbon::parse($dateFin)->format('M Y') }}</title>
    
    <style>
        /* Styles CSS pour DomPDF */
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10px;
            margin: 20px;
            padding: 0;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            padding: 15px;
            background-color: #D32F2F;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            font-weight: bold;
        }
        .period-info {
            text-align: center;
            margin-bottom: 25px;
            font-size: 11px;
            color: #555;
            line-height: 1.6;
        }
        .period-info strong {
            color: #D32F2F;
        }
        .table-container {
            margin: 0 auto;
            width: 100%;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
            color: #333;
            font-weight: bold;
            font-size: 10px;
        }
        .total-row td {
            background-color: #E8F5E9;
            font-weight: bold;
            font-size: 11px;
            color: #2E7D32;
        }
        .marge-column {
            background-color: #e6ffe6;
            font-weight: bold;
            color: #2E7D32;
        }
        .vendu-column {
            background-color: #f0f8ff;
        }
        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 30px;
        }
        .summary-box {
            margin-top: 30px;
            padding: 15px;
            background-color: #f9f9f9;
            border: 2px solid #ddd;
            border-radius: 5px;
        }
        .summary-box h3 {
            margin: 0 0 10px 0;
            color: #D32F2F;
            font-size: 14px;
        }
        .summary-item {
            display: inline-block;
            width: 48%;
            margin: 5px 0;
            font-size: 11px;
        }
        .summary-item strong {
            color: #333;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>RAPPORT MENSUEL DES VENTES DE PRODUITS</h1>
    </div>

    <div class="period-info">
        <p><strong>Généré le:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        <p><strong>Période couverte:</strong> Du {{ \Carbon\Carbon::parse($dateDebut)->format('d/m/Y') }} au {{ \Carbon\Carbon::parse($dateFin)->format('d/m/Y') }}</p>
    </div>

    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th style="width: 8%;">Réf.</th>
                    <th style="width: 25%;">Nom du Produit</th>
                    <th style="width: 15%;">Catégorie</th>
                    <th class="vendu-column" style="width: 10%;">Qté Vendue</th>
                    <th class="vendu-column" style="width: 15%;">Total Ventes (MAD)</th>
                    <th style="width: 12%;">Prix Achat Moyen</th>
                    <th class="marge-column" style="width: 15%;">Marge Totale (MAD)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $total_quantite_vendue = 0;
                    $total_vendu_montant = 0;
                    $total_marge_totale = 0;
                @endphp
                
                @forelse ($produits as $produit)
                    @php
                        $total_quantite_vendue += $produit->quantite_vendue ?? 0;
                        $total_vendu_montant += $produit->total_vendu_montant ?? 0;
                        $total_marge_totale += $produit->marge_totale ?? 0;
                    @endphp
                    <tr>
                        <td>{{ $produit->reference ?? 'N/A' }}</td>
                        <td style="text-align: left; padding-left: 5px;">{{ $produit->nom }}</td>
                        <td>{{ $produit->categorie_nom ?? 'N/A' }}</td>
                        <td class="vendu-column"><strong>{{ number_format($produit->quantite_vendue ?? 0, 0) }}</strong></td>
                        <td class="vendu-column">{{ number_format($produit->total_vendu_montant ?? 0, 2, ',', ' ') }}</td>
                        <td>{{ number_format($produit->prix_achat_moyen ?? 0, 2, ',', ' ') }}</td>
                        <td class="marge-column"><strong>{{ number_format($produit->marge_totale ?? 0, 2, ',', ' ') }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="no-data">
                            ⚠️ Aucune vente enregistrée pour les produits actifs durant cette période.
                        </td>
                    </tr>
                @endforelse
                
                @if(count($produits) > 0)
                <tr class="total-row">
                    <td colspan="3" style="text-align: right; font-weight: bold;">📊 TOTAUX PÉRIODE:</td>
                    <td><strong>{{ number_format($total_quantite_vendue, 0) }}</strong></td>
                    <td><strong>{{ number_format($total_vendu_montant, 2, ',', ' ') }}</strong></td>
                    <td>-</td>
                    <td><strong>{{ number_format($total_marge_totale, 2, ',', ' ') }}</strong></td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>

    @if(count($produits) > 0)
    <div class="summary-box">
        <h3>📈 Résumé de la Période</h3>
        <div class="summary-item">
            <strong>Nombre de produits vendus:</strong> {{ count($produits) }}
        </div>
        <div class="summary-item">
            <strong>Chiffre d'affaires total:</strong> {{ number_format($total_vendu_montant, 2, ',', ' ') }} MAD
        </div>
        <div class="summary-item">
            <strong>Marge brute totale:</strong> {{ number_format($total_marge_totale, 2, ',', ' ') }} MAD
        </div>
        <div class="summary-item">
            <strong>Taux de marge moyen:</strong> 
            @if($total_vendu_montant > 0)
                {{ number_format(($total_marge_totale / $total_vendu_montant) * 100, 2, ',', ' ') }}%
            @else
                0%
            @endif
        </div>
    </div>
    @endif

</body>
</html>