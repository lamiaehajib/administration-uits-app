<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport des Ventes</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body { 
            font-family: 'Arial', sans-serif; 
            font-size: 14px; 
            color: #333; 
            margin: 20px; 
            padding: 20px; 
            background-color: #f8f9fa;
        }

        .header {
            text-align: center;
            padding-bottom: 10px;
            border-bottom: 2px solid #C2185B;
            margin-bottom: 20px;
        }

        h2 {
            color: #C2185B;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        p {
            font-size: 16px;
            font-weight: bold;
            color: #555;
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: #fff;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: center;
        }

        th {
            background-color: #C2185B;
            color: white;
            text-transform: uppercase;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }
        .header-img {
            position: absolute;
            width: 150px; /* Taille réduite */
            margin: 0;
            margin-top: -55px;
        }
    </style>
</head>
<body>
    <div><img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo"></div>
    <div class="header">
        <h2><i class="fas fa-chart-bar"></i> Rapport des Ventes</h2>
        <p><i class="fas fa-calendar-alt"></i> Période : {{ $dateDebut->format('d/m/Y') }} - {{ $dateFin->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Prix Achat</th>
                <th>Quantité Vendue</th>
                <th>Prix vente</th>
                <th>Total Vente</th>
                <th>Bénéfice</th>
                <th>Stock Restant</th>
            </tr>
        </thead>
        <tbody>
            @foreach($ventes as $vente)
            <tr>
                <td>{{ $vente->produit_nom }}</td>
                <td>{{ $vente->prix_achat }}</td>
                <td>{{ $vente->quantite_vendue }}</td>
                <td>{{ number_format($vente->prix_vendu, 2) }} DH</td>
                <td>{{ number_format($vente->total_vendu, 2) }} DH</td>
                <td>{{ $vente->marge }} DH</td>
                <td>{{ $vente->quantite_stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p><i class="fas fa-paperclip"></i> Rapport généré automatiquement | {{ now()->format('d/m/Y H:i') }}</p>
    </div>

</body>
</html>
