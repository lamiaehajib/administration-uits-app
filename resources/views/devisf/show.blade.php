<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Devis PDF</title>
    <style>
        *{ margin: 0; }
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
            padding: 5px;
            text-align: center;
            font-size: 12.5px;
        }

        .devis-table thead th {
            background-color: rgb(203, 4, 4);
            color: rgb(255, 255, 255);
            font-weight: bold;
            text-transform: uppercase;
        }

        .devis-table tbody td {
            font-weight: bold;
        }

        /* ✅ Colonne remise — style spécial quand elle est affichée */
        .remise-cell {
            color: #c0392b;
            font-weight: bold;
        }

        .totals-table {
            width: 37%;
            border-collapse: collapse;
            top: 335px;
            margin-left: 500px !important;
        }

        .totals-table th,
        .totals-table td {
            border: 2px dashed red;
            padding: 3px;
            text-align: left;
            font-size: 13.5px;
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
            margin-top: -130px;
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
        }

        .cachet-signature {
            color: #000000;
            font-family: 'Arial', sans-serif;
            font-size: 16px;
            font-weight: bold;
            text-align: right;
            text-transform: uppercase;
            letter-spacing: 2px;
            display: inline-block;
            margin-top: 30px !important;
            margin-left: 480px;
        }

        .hed-con {
            display: inline-block;
            margin-left: 40px;
            text-transform: uppercase;
            font-weight: bold;
            font-size: 17px;
            color: rgb(195, 57, 57);
            width: 350px;
            margin-top: 100px;
        }

        .footer-container {
            text-align: center;
            margin-top: 180px;
        }
    </style>
</head>
<body>
    <div>
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>

    <div class="header-container">
        <h1 class="hed-con">{{ $devisf->titre }}</h1>
        <div class="client-info">
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">{{ $devisf->client }}</h4>
            <h5>{{ $devisf->contact }}</h5>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">
                <strong>{{ \Carbon\Carbon::parse($devisf->date)->format('d-m-Y') }}</strong>
            </h4>
        </div>
    </div>

    <div>
        <div style="background-color: rgb(203, 4, 4); color: white; padding: 10px; border-radius: 5px; text-align:center; margin-top:30px; text-transform:uppercase; width: 100%;">
            Devis N°: {{ $devisf->devis_num }}
        </div>

        @php
            // ✅ Vérifier si AU MOINS UNE ligne a une remise > 0
            $hasRemise = $devisf->items->contains(fn($item) => $item->remise > 0);

            // Header dynamique
            $labels = [];
            foreach ($devisf->items as $item) {
                if (!empty($item->formation))      $labels['Durée'] = true;
                if (!empty($item->nombre))         $labels['Collaborateurs'] = true;
                if (!empty($item->nombre_de_jours)) $labels['Jours'] = true;
            }
            $header = !empty($labels) ? implode('/', array_keys($labels)) : '-';
        @endphp

        <table class="devis-table">
            <thead>
                <tr>
                    <th>REF</th>
                    <th>Libellé</th>
                    <th>{{ $header }}</th>
                    <th>Prix Unitaire</th>
                    {{-- ✅ Colonne remise affichée UNIQUEMENT si au moins une ligne a une remise --}}
                    @if($hasRemise)
                        <th>Remise</th>
                    @endif
                    <th>Prix Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($devisf->items as $item)
                    <tr>
                        <td>{{ $devisf->ref }}</td>
                        <td>{!! nl2br(e($item->libele)) !!}</td>
                        <td>
                            @if (!empty($item->nombre))
                                {{ $item->nombre }} personnes
                            @elseif (!empty($item->formation))
                                {{ $item->formation }}
                            @elseif (!empty($item->nombre_de_jours))
                                {{ $item->nombre_de_jours }} jours
                            @else
                                -
                            @endif
                        </td>
                        <td>{{ number_format($item->prix_unitaire, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}</td>
                        {{-- ✅ Cellule remise — affichée seulement si $hasRemise --}}
                        @if($hasRemise)
                            <td class="{{ $item->remise > 0 ? 'remise-cell' : '' }}">
                                @if($item->remise > 0)
                                    -{{ number_format($item->remise, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}
                                @else
                                    —
                                @endif
                            </td>
                        @endif
                        <td>{{ number_format($item->prix_total, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table class="totals-table">
            <tr class="totals">
                <th>Total HT:</th>
                <td>{{ number_format($devisf->total_ht, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}</td>
            </tr>
            <tr class="totals">
                <th style="background-color: #dbdbdb;">TVA: 20%</th>
                <td style="background-color: #e1e1e1;">{{ number_format($devisf->tva, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}</td>
            </tr>
            <tr class="totals">
                <th>Total TTC:</th>
                <td>{{ number_format($devisf->total_ttc, 2) }} {{ str_replace('EUR', '€', $devisf->currency) }}</td>
            </tr>
        </table>

        <div style="display: flex; flex-direction: column; gap: 10px;">
            <div class="cachet-signature">
                Cachet et Signature :
            </div>
            <div style="margin-left:520px">
                <img src="{{ public_path('images/testcah-removebg-preview.png') }}" alt=""
                     style="position: relative; margin-top:-10px; width:150px; transform: rotate(27deg);">
            </div>

            <h5 class="important-title">Informations importantes:</h5>
            <ul class="important-list">
                @if($devisf->ImportantInfof->isNotEmpty())
                    @foreach($devisf->ImportantInfof as $info)
                        <li>{{ $info->info }}</li>
                    @endforeach
                @else
                    <li>No important information available.</li>
                @endif
            </ul>
        </div>
    </div>

    <footer style="text-align: center; position: fixed; bottom: 20; width: 100%; z-index: 1000;">
        <hr style="border: none; border-top: 2px solid #df0202; margin: 10px auto; width: 90%;" />
        <div>
            <h4 style="margin: 10px 0; color:#df0202; font-weight: bold; text-transform: uppercase;">Union IT Services</h4>
            <p style="margin: 5px 0; font-size: 13px; line-height: 1.6;">
                Siège Sociale : App 1, Etg 1, N° 68, Rue San Saëns, Belvedere, Casablanca (CP 20300) <br>
                Email : contact@uits.ma  |  Tél : 05 22 24 04 83 / 06 60 21 07 73  |  Site web :
                <a href="http://www.uits.ma" style="color: #df0202; text-decoration: none;">www.uits.ma</a><br>
                ICE : 001883395000003  |  RC : 372657  |  NIF : 20754203  |  CNSS : 5429693  |  Patente : 31292380 <br>
            </p>
        </div>
    </footer>
</body>
</html>