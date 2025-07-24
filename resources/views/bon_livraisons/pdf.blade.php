<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bon de Livraison PDF</title>
    <style>
        * {
            margin: 0;
        }
        body {
            background-image: url('{{ public_path('images/a.jpg') }}');
            background-repeat: no-repeat;
            background-size: cover;
            font-family: Arial, sans-serif;
            margin: 0;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            padding: 20px;
        }

        .client-info {
            border: 2px dashed red;
            padding: 15px;
            width: 40%;
            border-radius: 8px;
            margin-left: 400px;
            margin-top: -60px;
            text-align: center;
            box-shadow: 20 0 11px rgba(25, 25, 25, 0.1);
        }

        .client-info h4,
        .client-info h5 {
            margin: 5px 0;
        }

        .header-img {
            width: 170px;
            height: auto;
            object-fit: contain;
        }

        .devis-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            border: 2px dashed rgb(179, 25, 25);
        }

        .devis-table th,
        .devis-table td {
            border: 2px dashed red;
            padding: 10px;
            text-align: center;
        }

        .devis-table thead th {
            background-color: rgb(203, 4, 4);
            color: rgb(255, 255, 255);
            font-weight: bold;
            text-transform: uppercase;
            font-size: 14px;
        }

        .devis-table tbody td {
            font-weight: bold;
        }

        .totals-table {
            width: 42%;
            border-collapse: collapse;
            margin-left: 460px;
            margin-top: 20px;
        }

        .totals-table th,
        .totals-table td {
            border: 2px dashed red;
            padding: 6px;
            text-align: left;
        }

        .totals {
            font-weight: bold;
            color: #000000;
        }

        .important-title {
            font-size: 18px;
            font-weight: bold;
            color: #c0392b;
            text-transform: uppercase;
            margin-bottom: 10px;
            border-left: 5px solid #e74c3c;
            padding-left: 10px;
        }

        .important-list li {
            background: linear-gradient(45deg, #ff9a9e, #fad0c4);
            color: #845858;
            margin: 10px 0;
            padding: 3px;
            width: 350px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            font-weight: bold;
            transition: transform 0.2s, box-shadow 0.2s;
        }

        .cachet-signature {
            color: #000000;
            padding: 15px 20px;
            margin: 20px 0;
            border-radius: 8px;
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
            margin-top: 30px;
            margin-left: 480px;
        }

        .hed-con {
            display: inline-block;
            margin-left: 100px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 17px;
            color: rgb(195, 57, 57);
            width: 350px;
            margin-top: 100px;
        }

        .h-kim {
            margin-left: 50px;
        }
    </style>
</head>
<body>
    <div>
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>

    <div class="header-container">
        <h1 class="hed-con">{{ $bonLivraison->titre }}</h1>
        <div class="client-info">
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">Client: {{ $bonLivraison->client }}</h4>
            <h5>Téléphone: {{ $bonLivraison->tele ?? 'N/A' }}</h5>
            <h5>Adresse: {{ $bonLivraison->adresse ?? 'N/A' }}</h5>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">ICE: <strong>{{ $bonLivraison->ice ?? 'N/A' }}</strong></h4>
        </div>
    </div>

    <h4 class="h-kim">Date: <strong>{{ \Carbon\Carbon::parse($bonLivraison->date)->format('d-m-Y') }}</strong></h4>

    <div>
        <div style="background-color: rgb(203, 4, 4); box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); color: white; padding: 10px; border-radius: 5px; text-align:center; margin-top:10px; text-transform:uppercase; width: 100%;">
            Bon de Livraison N°: {{ $bonLivraison->bon_num }}
        </div>

        <table class="devis-table">
            <thead>
                <tr>
                    <th>RÉFÉRENCE</th>
                    <th>Libellé</th>
                    <th>Quantité</th>
                    <th>Prix HT</th>
                    <th>Prix Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($bonLivraison->items as $item)
                    <tr>
                        <td>{{ $bonLivraison->ref ?? 'N/A' }}</td>
                        <td>{!! nl2br(e($item->libelle)) !!}</td>
                        <td>{{ $item->quantite }}</td>
                        <td>{{ number_format($item->prix_ht, 2) }}</td>
                        <td>{{ number_format($item->prix_total, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr class="totals">
                <th>Total HT:</th>
                <td>{{ number_format($bonLivraison->total_ht, 2) }}</td>
            </tr>
            <tr class="totals">
                <th style="background-color: #dbdbdb;">TVA: 20%</th>
                <td style="background-color: #e1e1e1;">{{ number_format($bonLivraison->tva, 2) }}</td>
            </tr>
            <tr class="totals">
                <th>Total TTC:</th>
                <td>{{ number_format($bonLivraison->total_ttc, 2) }}</td>
            </tr>
        </table>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div class="cachet-signature">
                Cachet et Signature :
            </div>

            @if (!empty($bonLivraison->important))
                <h5 class="important-title">Informations importantes:</h5>
                <ul class="important-list">
                    @foreach ($bonLivraison->important as $info)
                        <li>{{ $info }}</li>
                    @endforeach
                </ul>
            @endif
        </div>

        <footer style="text-align: center; position: fixed; bottom: 20; width: 100%; z-index: 1000;">
            <hr style="border: none; border-top: 2px solid #df0202; margin: 10px auto; width: 90%;" />
            <div>
                <h4 style="margin: 10px 0; color:#df0202; font-weight: bold; text-transform: uppercase;">Union IT Services</h4>
                <p style="margin: 5px 0; font-size: 13px; line-height: 1.6;">
                    Siège Sociale : App 1, Etg 1, N° 68, Rue San Saëns, Belvedere, Casablanca (CP 20300) <br>
                    Email : contact@uits.ma  |  Tél : 05 22 24 04 83 / 06 60 21 07 73  |  Site web : <a href="http://www.uits.ma" style="color: #df0202; text-decoration: none;">www.uits.ma</a><br>
                    ICE : 001883395000003  |  RC : 372657  |  NIF : 20754203  |  CNSS : 5429693  |  Patente : 31292380 <br>
                </p>
            </div>
        </footer>
    </div>
</body>
</html>