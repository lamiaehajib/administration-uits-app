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
            justify-content: center; /* Espace entre les deux sections */
            align-items: flex-start; /* Alignement en haut */
            padding: 0px;
           
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
        /* h1 {
    text-transform: uppercase;
    font-weight: bold;
    font-size: 17px;
    position: absolute; 
    margin-top: 100px;
    margin-right: 105px;
width: 350px;
color: rgb(195, 57, 57);
    text-align: center; 
} */


.devis-table {
    
    width: 100%;
    border-collapse: collapse;
 font-size: 13px;
    border: 2px dashed rgb(179, 25, 25);
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

      padding: 13px;
}

.devis-table tbody td {
    font-weight: bold;
    
   
}


.totals-table {
    width: 37%;
    border-collapse: collapse;
   /* Same margin as the other table */
    border: 2px dashed rgb(179, 25, 25); /* Same dashed border color */
    margin-left:500px;
}

.totals-table th,
.totals-table td {
    border: 2px dashed red; /* Same dashed border for cells */
    padding: 10px;
    text-align: left;
}

.totals {
    font-weight: bold;
    color: #000000;
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
    font-size: 14px; /* Taille du texte */
    font-weight: bold; /* Texte en gras */
    transition: transform 0.2s, box-shadow 0.2s; /* Animation au survol */
}

.cachet-signature {
    color: #000000; /* Texte noir */
    font-family: 'Arial', sans-serif; /* Police élégante */
    font-size: 14px; /* Taille du texte */
    font-weight: bold; /* Texte en gras */
    text-align: right; /* Aligné à droite */
    text-transform: uppercase; /* Texte en majuscules */
    letter-spacing: 2px; /* Espacement des lettres */
    transition: transform 0.3s ease, box-shadow 0.3s ease; /* Animation au survol */
    display: inline-block;
    margin-top: 10px !important;
}

.header-img {
    position: relative;
    margin-top: 50px;
    margin-left: 15px !important;
}
.header-imgg {
    position: relative;
    margin-top: 50px;
    margin-left: -205px !important;
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

div {
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 20px;
}


.hed-con{
    display: inline-block;
    margin-left: 50px;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 14px; /* Une taille plus grande pour voir clairement */
    color: rgb(195, 57, 57);
    width: 350px;
    margin-top: 100px;

}

.footer-container {
    text-align: center;
    margin-top: 180px;
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
  }

  .footer-text {
    margin: 5px 0;
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
    <div style="margin-top: -50px;"
    <div>
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>
   
    <div class="header-container">
        <h1 class="hed-con" >{{ $devis->titre }}</h1>
        <div class="client-info">
            <h4 style="font-size:14px; text-transform:uppercase; color:rgb(179, 41, 41);">{{ $devis->client }}</h4>
            <h5>{{ $devis->contact }}</h5>
            <h4 style="font-size:16px; text-transform:uppercase; color:rgb(179, 41, 41);"><strong>{{ \Carbon\Carbon::parse($devis->date)->format('d-m-Y') }}
            </strong></h4>
        </div>
        
    </div>
<div>
    <div style="background-color: rgb(203, 4, 4); box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1); color: white; padding: 2px; border-radius: 5px; text-align:center; text-transform:uppercase;
    width: 100%; height:40px; margin-bottom:10px; margin-top:10px;">
        Devis N°: {{ $devis->devis_num }}
    </div>
    <table class="devis-table">
        <thead>
            <tr>
                <th>REF</th>
                <th>Libellé</th>
                <th>Quantité</th>
                <th>Prix Unitaire</th>
                <th>Prix Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($devis->items as $item)
                <tr>
                    <td>{{ $devis->ref }}</td>
                    <td>{!! nl2br(e($item->libele)) !!}</td>
                    <td>{{ $item->quantite }}</td>
                    <td>{{ $item->prix_unitaire }} {{ str_replace('EUR', '€', $devis->currency) }}</td>
                    <td>{{ $item->prix_total }} {{ str_replace('EUR', '€', $devis->currency) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <table class="totals-table">
    <tr class="totals">
        <th>Total HT:</th>
        <td>{{ $devis->total_ht }} {{ str_replace('EUR', '€', $devis->currency) }}</td>
    </tr>
    <tr class="totals">
        <th style="background-color: #dbdbdb;">TVA: 20%</th>
        <td style="background-color: #e1e1e1;">{{ $devis->tva }} {{ str_replace('EUR', '€', $devis->currency) }}</td>
    </tr>
    <tr class="totals">
        <th>Total TTC:</th>
        <td>{{ $devis->total_ttc }} {{ str_replace('EUR', '€', $devis->currency) }}</td>
    </tr>
</table>

<div style="display: flex; justify-content: space-between; align-items: center; gap: 10px;">
    <div style="margin-left:500px">
        <div class="cachet-signature">
            Cachet et Signature :
        </div>
        <div style="margin-left:220px">
        <img class="header-imgg"  src="{{ public_path('images/testcah-removebg-preview.png') }}" alt="" style="position: relative;  margin-top:-10px; width:150px; transform: rotate(27deg);">
</div>
    </div>
    <div>
        <h5 class="important-title">Informations importantes:</h5>
        <ul class="important-list">
            @foreach($devis->importantInfos as $info)
                <li>{{ $info->info }}</li>
            @endforeach
        </ul>
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