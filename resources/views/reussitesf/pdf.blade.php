<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
         *{
        
        margin: 0;
    }
        body {
            font-family: Arial, sans-serif; 
            margin: 10px; /* Réduction des marges pour PDF */
            font-size: 12px; /* Réduction de la taille de police pour économiser de l'espace */
        }
        .section {
            margin-bottom: 8px; /* Espacement réduit */
        }
        .content-container {
            background-image: url('{{ public_path('images/resu.jpg') }}');
            background-size: cover;
            background-position: center;
            height: 65%;
        }
        .dotted-line {
            border-bottom: 1px dotted #000; 
            width: 55%;
            display: inline-block;
        }
        .header-img {
            position: absolute;
            width: 100px; /* Taille réduite */
            margin: 0;
        }
        .footer-text {
            font-size: 10px; /* Texte plus petit pour économiser de l'espace */
            text-align: center;
            margin-top: 65px;
            
            
        }
    </style>
</head>
<body>
    <div class="content-container">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            <div><img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo"></div>
            <div style="margin-left:250px; margin-top:30px !important;width=70px;height:40px !important ; position: absolute;">
                <img width="50px" style="margin-left:200px; margin-top:20px !important; " src="{{ public_path('images/scanie-r.jpg') }}" alt="Logo"/>
             </div>
        </div>
        
        <div style="margin-top: 100px;">
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Nom et Prénom :</span>
                <span class="dotted-line">{{ $reussite->nom }} {{ $reussite->prenom }}</span>
            </div>
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Nom de la formation:</span>
                <span class="dotted-line"> {{ $reussite->formation }}</span>
            </div>
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Montant payé :</span>
                <span class="dotted-line">{{ $reussite->montant_paye }} DH</span>
            </div>
              <div class="section" style="display: flex; justify-content: space-between;">
    <span  style="font-weight: 500;text-transform: uppercase;">Mode de Paiement :</span>
    <span class="dotted-line">{{ $reussite->mode_paiement }}</span>
</div>
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Reste a payé :</span>
                <span class="dotted-line">{{ $reussite->rest }}DH</span>
            </div>
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Date de paiement :</span>
                <span class="dotted-line">{{ $reussite->date_paiement }}</span>
            </div>
            <div class="section" style="display: flex; justify-content: space-between;">
                <span style="font-weight: 500;text-transform: uppercase;">Prochaine paiement :</span>
                <span class="dotted-line">{{ $reussite->prochaine_paiement }}</span>
            </div>
            
        </div>

        <div style="display: flex; justify-content: center; margin-top: 10px; gap:100px">
            <div style="text-align: center;margin-right:250px !important; margin-top:20px; position: absolute;">
                <p style="margin: 0;">Signature Client</p>
            </div>
            <div style="text-align: center; margin-left:250px !important;">
                <p style="margin: 0;">Signature d'entreprise</p>
                <img  src="{{ public_path('images/signature-r.png') }}" alt="Stamp" style="width: 60px; height: 60px; margin-top: 10px;">
            </div>
        </div>

        <div class="footer-text">
            <p style="margin: 2px;">NB: Dépense des sessions de formations et d'engagement non remboursables dans tous cas.</p>
            <p style="margin: 2px;">1er Étage, App 1, N° 68, Rue San Saëns, Belvédère, Casablanca 20300</p>
            <p style="margin: 2px;">05 22 24 04 83 | contact@uits.ma | www.uits.ma</p>
        </div>
       
    </div>
    <hr style="border: none; border-top: 1px dotted #000; margin: 10px 0;"/>

    <!-- Informations résumées -->
    <div style="text-align: center; text-transform:uppercase;">
        <h4>Reçu de formation</h4>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Nom et Prénom :</span>
        <span class="dotted-line">{{ $reussite->nom }} {{ $reussite->prenom }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Montant payé:</span>
        <span class="dotted-line">{{ $reussite->montant_paye }}DH</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Mode de Paiement :</span>
        <span class="dotted-line">{{ $reussite->mode_paiement }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Nom de la formation:</span>
        <span class="dotted-line">{{ $reussite->formation }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span style="font-weight: 500;text-transform: uppercase;">Reste a payé :</span>
        <span class="dotted-line">{{ $reussite->rest }}DH</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Date :</span>
        <span class="dotted-line">{{ $reussite->date_paiement }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">CIN :</span>
        <span class="dotted-line">{{ $reussite->CIN }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Téléphone :</span>
        <span class="dotted-line">{{ $reussite->tele }}</span>
    </div>
    <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">Gmail :</span>
        <span class="dotted-line">{{ $reussite->gmail }}</span>
    </div>
     <div class="section" style="display: flex; justify-content: space-between;">
        <span  style="font-weight: 500;text-transform: uppercase;">le Reçus Créé par :</span>
        <span class="dotted-line">{{ $reussite->user->name ?? 'Utilisateur inconnu' }}</span>
    </div>
</body>
</html>
