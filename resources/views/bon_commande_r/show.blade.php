
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Bon de Commande PDF</title>
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
            justify-content: center;
            align-items: flex-start;
            padding: 0px;
        }

        .client-info {
            border: 2px dashed red;
            padding: 15px;
            width: 40%;
            border-radius: 8px;
            margin-left: 400px;
            margin-top: -60px;
            text-align: center;
            box-shadow: 0 0 11px rgba(25, 25, 25, 0.1);
        }

        .client-info h4,
        .client-info h5 {
            margin: 5px 0;
        }

        .header-img {
            width: 170px;
            height: auto;
            object-fit: contain;
            position: relative;
            margin-top: 50px;
            margin-left: 15px;
        }

        .header-imgg {
            position: relative;
            margin-top: 50px;
            margin-left: -205px;
        }

        .hed-con {
            display: inline-block;
            margin-left: 50px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 14px;
            color: rgb(195, 57, 57);
            width: 350px;
            margin-top: 100px;
        }

        .devis-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 9px;
            border: 2px dashed rgb(179, 25, 25);
            margin-top: 8px;
        }

        .devis-table th,
        .devis-table td {
            border: 2px dashed red;
            padding: 2px;
            text-align: center;
        }

        .devis-table thead th {
            background-color: rgb(203, 4, 4);
            color: rgb(255, 255, 255);
            font-weight: bold;
            text-transform: uppercase;
            padding: 6px;
        }

        .devis-table tbody td {
            font-weight: bold;
        }

        .totals-table {
            width: 37%;
            border-collapse: collapse;
            border: 2px dashed rgb(179, 25, 25);
            margin-left: 500px;
        }

        .totals-table th,
        .totals-table td {
            border: 2px dashed red;
            padding: 10px;
            text-align: left;
        }

        .totals-table th {
            background-color: rgb(203, 4, 4);
            color: rgb(255, 255, 255);
            font-weight: bold;
            text-transform: uppercase;
        }

        .totals-table td {
            font-weight: bold;
        }

        .totals {
            font-weight: bold;
            color: #000000;
        }

        .important-title {
            font-size: 15px;
            font-weight: bold;
            color: #333;
            margin-top: -200px;
        }

        .important-list {
            padding-left: 0;
            list-style-type: none;
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
            font-family: 'Arial', sans-serif;
            font-size: 14px;
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 2px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            display: inline-block;
            margin-top: 10px;
        }

        .footer-container {
            text-align: center;
            margin-top: 200px;
        }

        .footer-line {
            border: none;
            border-top: 2px solid #df0202;
            margin: 10px auto;
            width: 90%;
          
        }

        .footer-title {
            margin: 10px 0;
            color: #df0202;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 40px;
          
        }

        .footer-text {
            margin: 1px 0;
            font-size: 13px;
            line-height: 1.6;
           
        }

        .footer-link {
            color: #df0202;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div style="margin-top: -50px;">
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>
   
    <div class="header-container">
        <h1 class="hed-con">{{ $bonCommandeR->titre ?? '-' }}</h1>
        <div class="client-info">
            <h4 style="font-size:14px; text-transform:uppercase; color:rgb(179, 41, 41);">Prestataire: {{ $bonCommandeR->prestataire ?? '-' }}</h4>
            <h5>telephone: {{ $bonCommandeR->tele ?? '-' }}</h5>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);"> date:
                <strong>{{ $bonCommandeR->date ? \Carbon\Carbon::parse($bonCommandeR->date)->format('d-m-Y') : '-' }}</strong>
            </h4>
        </div>
    </div>

    <div style="background-color: rgb(203, 4, 4); box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); color: white; padding: 2px; border-radius: 5px; text-align:center; text-transform:uppercase; width: 100%; height:40px; margin-bottom:10px; margin-top:10px;">
        Bon de Commande N°: {{ $bonCommandeR->bon_num ?? '-' }}
    </div>

    <table class="devis-table">
        <thead>
            <tr>
                <th>REF</th>
                <th>Libellé</th>
                <th>Quantité</th>
                <th>Prix HT</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bonCommandeR->items as $item)
                <tr>
                    <td>{{ $bonCommandeR->ref ?? '-' }}</td>
                    <td>{!! nl2br(e($item->libelle ?? '-')) !!}</td>
                    <td>{{ $item->quantite ?? '-' }}</td>
                    <td>{{ $item->prix_ht ? number_format($item->prix_ht, 2) : '-' }} {{ str_replace('EUR', '€', $bonCommandeR->currency ?? 'DH') }}</td>
                    <td>{{ $item->prix_total ? number_format($item->prix_total, 2) : '-' }} {{ str_replace('EUR', '€', $bonCommandeR->currency ?? 'DH') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals-table">
        <tr class="totals">
            <th>Total HT:</th>
            <td>{{ $bonCommandeR->total_ht ? number_format($bonCommandeR->total_ht, 2) : '-' }} {{ str_replace('EUR', '€', $bonCommandeR->currency ?? 'DH') }}</td>
        </tr>
        <tr class="totals">
            <th style="background-color: #dbdbdb;">TVA: {{ $bonCommandeR->tva ? number_format($bonCommandeR->tva, 2) : '20' }}%</th>
            <td style="background-color: #e1e1e1;">{{ $bonCommandeR->tva ? number_format($bonCommandeR->total_ht * ($bonCommandeR->tva / 100), 2) : '-' }} {{ str_replace('EUR', '€', $bonCommandeR->currency ?? 'DH') }}</td>
        </tr>
        <tr class="totals">
            <th>Total TTC:</th>
            <td>{{ $bonCommandeR->total_ttc ? number_format($bonCommandeR->total_ttc, 2) : '-' }} {{ str_replace('EUR', '€', $bonCommandeR->currency ?? 'DH') }}</td>
        </tr>
    </table>

    <div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
        <div style="margin-left:500px">
            <div class="cachet-signature">
                Cachet et Signature :
            </div>
            <div style="margin-left:220px">
                <img class="header-imgg" src="{{ public_path('images/testcah-removebg-preview.png') }}" alt="" style="position: relative; margin-top:-10px; width:150px; transform: rotate(27deg);">
            </div>
        </div>
        <div>
            <h5 class="important-title">Informations importantes:</h5>
            <ul class="important-list">
                @php
                    $importantItems = is_array($bonCommandeR->important) ? $bonCommandeR->important : (json_decode($bonCommandeR->important, true) ?? []);
                @endphp
                @forelse ($importantItems as $info)
                    <li>{{ $info }}</li>
                @empty
                    <li>-</li>
                @endforelse
            </ul>
        </div>
    </div>

    <footer class="footer-container">
        <hr class="footer-line" />
        <h4 class="footer-title">Union IT Services</h4>
        <p class="footer-text">
            Siège Sociale : App 1, Etg 1, N° 68, Rue San Saëns, Belvedere, Casablanca (CP 20300) <br>
            Email : contact@uits.ma | Tél : 05 22 24 04 83 / 06 60 21 07 73 | Site web : <a href="http://www.uits.ma" class="footer-link">www.uits.ma</a><br>
            ICE : 001883395000003 | RC : 372657 | NIF : 20754203 | CNSS : 5429693 | Patente : 31292380
        </p>
    </footer>
</body>
</html>