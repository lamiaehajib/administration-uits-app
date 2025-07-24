<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<style>
    /* body {
      background-image: url('{{public_path('images/resu.jpg') }}');
      background-size: cover;
      background-position: center;
    } */
    hr {
            border: none;
            border-top: 1px dotted #000; /* Couleur et style des points */
            margin: 20px 0; /* Espacement */
        }
  </style>
  
<body style="font-family: Arial, sans-serif; margin: 10px;  ">
    <div style=" background-image: url('{{public_path('images/resu.jpg') }}');
      background-size: cover;
      background-position: center; height: 78%;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; gap:100px;">
        <div style="text-align: left;">
            <img width="150px" style="position: absolute;" src="{{ public_path('images/im.png') }}" alt="Logo"/>
        </div>
        <div style="margin-left:250px; margin-top:30px !important;width=70px;height:40px !important ; position: absolute;">

          
            <img width="50px" style="margin-left:100px; margin-top:30px !important; " src="{{ public_path('images/scanie-r.jpg') }}" alt="Logo"/>

         </div>
    </div>

    <div style="margin-top: 130px !important;">
        

        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Nom et Prénom :</span>
            <span style="border-bottom: 1px dotted #000; width: 60%; display: inline-block;">{{ $reussite->nom }} {{ $reussite->prenom }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Durée de stage :</span>
            <span style="border-bottom: 1px dotted #000; width: 60%; display: inline-block;">{{ $reussite->duree_stage }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Montant payé :</span>
            <span style="border-bottom: 1px dotted #000; width: 60%; display: inline-block;">{{ $reussite->montant_paye }}DH</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Date de paiement :</span>
            <span style="border-bottom: 1px dotted #000; width: 60%; display: inline-block;">{{ $reussite->date_paiement }}</span>
        </div>
        <div style="display: flex; justify-content: space-between; margin-bottom: 10px;">
            <span>Prochaine paiement :</span>
            <span style="border-bottom: 1px dotted #000; width: 60%; display: inline-block;">{{ $reussite->prochaine_paiement }}</span>
        </div>
    </div>

    <div style="display: flex; justify-content: center; margin-top: 20px; gap:100px">
        <div style="text-align: center;margin-right:250px !important; margin-top:20px; position: absolute;">
            <p style="margin: 0;">Signature Client</p>
        </div>
        <div style="text-align: center; margin-left:250px !important;">
            <p style="margin: 0;">Signature d'entreprise</p>
            <img  src="{{ public_path('images/signature-r.png') }}" alt="Stamp" style="width: 60px; height: 60px; margin-top: 10px;">
        </div>
    </div>

    <div style=" font-size: 12px; text-align: center;margin-top:30px;">
        <p style="margin: 0;">NB: Dépense des sessions de formations et d'engagement non remboursables dans tous cas.</p>
        <p style="margin: 0;">1er Étage, App 1, N° 68, Rue San Saëns, Belvédère, Casablanca 20300</p>
        <p style="margin: 0;">05 22 24 04 83 | contact@uits.ma | www.uits.ma</p>
    </div>
  
</div>
   
   
</body>

</html>
