<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Devis PDF</title>
    <style>
        *{
        
            margin: 0;
        }
        body {
    background-image: url('{{ public_path('images/a.jpg') }}');
    background-repeat: no-repeat;
    background-size: cover; /* Ajuste l'image pour couvrir tout le fond */
    font-family: Arial, sans-serif;
    margin: 0;
}

        .header-container {
            display: flex;
            justify-content: space-between; /* Espace entre les deux sections */
            align-items: flex-start; /* Alignement en haut */
            padding: 20px;
           
        }

        .client-info {
            border: 2px dashed red; /* Bordure rouge stylisée */
            padding: 15px;
            width: 40%; /* Largeur réduite pour un meilleur espacement */
             /* Effet ombré */
            border-radius: 8px; /* Coins arrondis */
           
            margin-left: 400px;
            margin-top: -60px;
            text-align: center;
            box-shadow: 20 0 11px rgba(25, 25, 25, 0.1);

        }

        .client-info h4,
        .client-info h5 {
            margin: 5px 0; /* Espacement entre les textes */
        }

        .header-img {
            width: 170px;
            height: auto;
            object-fit: contain; /* Assure que l'image est contenue dans ses dimensions */
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
            font-size: 14px
        }

        .devis-table tbody td {
            font-weight: bold;
        }

        .totals-table {
            width: 42%;
            border-collapse: collapse;
          
            top: 335px;
            margin-left:460px !important;
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
        color: #c0392b; /* لون بارز */
        text-transform: uppercase;
        margin-bottom: 10px;
        border-left: 5px solid #e74c3c; /* شريط تزييني على اليسار */
        padding-left: 10px; /* مسافة بين الشريط والنص */
    }


.important-list li {
    background: linear-gradient(45deg, #ff9a9e, #fad0c4); /* Dégradé attrayant */
    color: #845858; /* Texte blanc */
    margin: 10px 0; /* Espacement entre les éléments */
    padding: 3px; /* Espace intérieur pour chaque élément */
    width: 350px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre douce */
    font-family: 'Arial', sans-serif; /* Police élégante */
    font-size: 12px; /* Taille du texte */
    font-weight: bold; /* Texte en gras */
    transition: transform 0.2s, box-shadow 0.2s; /* Animation au survol */
    
}
.cachet-signature {
   /* Dégradé élégant */
    color: #000000; /* Texte blanc */
    padding: 15px 20px; /* Espacement intérieur */
    margin: 20px 0; /* Espacement extérieur */
    border-radius: 8px; /* Coins arrondis */
    font-family: 'Arial', sans-serif; /* Police élégante */
    font-size: 16px; /* Taille du texte */
    font-weight: bold; /* Texte en gras */
    text-align: right; /* Centrer le texte */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Ombre douce */
    text-transform: uppercase; /* Texte en majuscules */
    letter-spacing: 2px; /* Espacement des lettres */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animation au survol */
   
    display: inline-block;
   
    margin-top: 30px !important;
    margin-left: 480px;
    /* transform: rotate(-10deg); */
}

.hed-con{
    display: inline-block;
    margin-left: 100px;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 17px; /* Une taille plus grande pour voir clairement */
    color: rgb(195, 57, 57);
    width: 350px;
    margin-top: 100px;

}
.h-kim{
   margin-left: 50px;
   
    
}

    </style>
</head>
<body>
    <div>
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>
   
    <div class="header-container">

        <h1 class="hed-con"> {{ $facturef->titre }}</h1>
        
        <div class="client-info">
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">CLIENT:<strong style="color: #000000; text-transform: uppercase;"> {{ $facturef->client }}</strong></h4>
            <h5 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">address: <strong style="color: #000000;  text-transform: uppercase;">{{ $facturef->adresse }}</strong></h5>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">ICE:<strong style="color: #000000; text-transform: uppercase;"> {{ $facturef->ice}}</strong></h4>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);">tele:<strong style="color: #000000 ; text-transform: uppercase;"> {{ $facturef->tele}}</strong></h4>
        </div>

        
    </div>
    <h4 class="h-kim">Date: <strong>{{ \Carbon\Carbon::parse($facturef->date)->format('d-m-Y') }}</strong></h4>
<div>
    <div style="background-color: rgb(203, 4, 4); box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); color: white; padding: 10px; border-radius: 5px; text-align:center; margin-top:10px;text-transform:uppercase;
    width: 100%;">
        Facture N°: {{ $facturef->facturef_num }}
    </div>
    
    <table class="devis-table">
        <thead>
            <tr>
                <th>REF</th>
                <th>Libellé</th>
                <th>
                    @php
                        // Collecter les champs non vides parmi tous les éléments
                        $labels = [];
                        foreach ($facturef->items as $item) {
                            if (!empty($item->duree)) {
                                $labels['Durée'] = true;
                            }
                            if (!empty($item->nombre_collaborateurs)) {
                                $labels['collaborateurs'] = true;
                            }
                            if (!empty($item->nombre_jours)) {
                                $labels['jours'] = true;
                            }
                        }
                        // Construire la chaîne combinée
                        $header = !empty($labels) ? implode('/', array_keys($labels)) : '-';
                    @endphp
                    {{ $header }}
                </th>
                <th>prix HT</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturef->items as $item)
                <tr>
                    <td>{{ $facturef->ref }}</td>
                    <td>{{ $item->libelle }}</td>
                    <td>
                        @if (!empty($item->nombre_collaborateurs))
                            {{ $item->nombre_collaborateurs }} personnes
                        @elseif (!empty($item->duree))
                            {{ $item->duree }}
                        @elseif (!empty($item->nombre_jours))
                            {{ $item->nombre_jours }} jours
                        @else
                            - <!-- أو أي قيمة افتراضية -->
                        @endif
                    </td>
                    <td>{{ $item->prix_ht }} {{ str_replace('EUR', '€', $facturef->currency) }}</td>
                    <td>{{ $item->prix_total }} {{ str_replace('EUR', '€', $facturef->currency) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="totals-table">
        <tr class="totals">
            <th>Total HT:</th>
            <td>{{ $facturef->total_ht }} {{ str_replace('EUR', '€', $facturef->currency) }}</td>
        </tr>
        <tr class="totals">
            <th style="background-color: #dbdbdb;">
                @if ($facturef->tva > 0)
                    TVA: 20%
                @else
                    Aucune TVA
                @endif
            </th>
            <td style="background-color: #e1e1e1;">
                {{ number_format($facturef->tva, 2) }} {{ str_replace('EUR', '€', $facturef->currency) }}
            </td>
        </tr>
        <tr class="totals">
            <th>Total TTC:</th>
            <td>{{ $facturef->total_ttc }} {{ str_replace('EUR', '€', $facturef->currency) }}</td>
        </tr>
    </table>
    <div style="display: flex; flex-direction: column; gap: 10px;">
        <div class="cachet-signature">
            Cachet et Signature :
        </div>
        @if ($facturef->afficher_cachet)
        <div style="margin-left:550px">
            <img class="header-imgg" src="{{ public_path('images/testcah-removebg-preview.png') }}" alt="" style="position: relative; margin-top:-10px; width:150px; transform: rotate(27deg);">
        </div>
    @endif
   
   
        <h5 class="important-title">Informations importantes:</h5>
        <ul class="important-list">
            @if($facturef->importantInfo->isNotEmpty())
                @foreach($facturef->importantInfo as $info)
                    <li>{{ $info->info }}</li>
                @endforeach
            @else
                <li>No important information available.</li>
            @endif
        </ul>

    </div>
      
    
    <div style="text-align: center !important; position: fixed; bottom: 180; margin-left:200px">
     <h5>{{ $facturef->vide}}</h5>
    </div>
</div>
<footer style="text-align: center;  position: fixed; bottom: 20; width: 100%;  z-index: 1000;">
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

  
</body>
</html>
