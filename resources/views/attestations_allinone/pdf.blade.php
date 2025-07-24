<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

    <title>Attestation de Formation</title>
    <style>
        *{
            margin: 0;

        }
        body {
            background-image: url('{{ public_path('images/FANAA.jpg') }}');
    background-repeat: no-repeat;
    background-size: cover; /* Ajuste l'image pour couvrir tout le fond */
    font-family: Arial, sans-serif;
    margin: 0;

        }
        .header-img {
            text-transform: uppercase;
          position: absolute;
            height: auto;
            object-fit: contain; /* Assure que l'image est contenue dans ses dimensions */
            margin-top: 298px;
            margin-left: 10px;
            color: #fff;
        }

        .div-att {
   position: absolute;
   
    padding: 20px;

   
    margin: 20px auto; /* Centré sur la page */
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Légère ombre */
 margin-top: 140px;
    margin-left: 310px;
}

.p-att {
    font-size: 29px;
    font-weight: bold;
    color: #d32f2f; /* Rouge vif */
    letter-spacing: 2px; /* Espacement entre les lettres */
    margin-bottom: 10px;
    text-transform: uppercase; /* Majuscule */
}

.h-att {
    font-size: 36px;
    font-weight: 600;
    color: #333; /* Texte gris foncé */
    margin-top: 0;
    text-transform: capitalize;
    font-style: italic; /* Texte en italique */
}
.pas-att{
    color: #747171;
    text-transform: uppercase;
    font-size: 17px;
    position: absolute;
   width: auto;
   margin-left: 400px;
   margin-top: 50px;
   font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
}
.divs-att{
   
    text-align: center;
    margin-top:200px;
}
.has-att{
    position: absolute;
  text-transform: uppercase;
  font-size: 27px;
  color: #fff;
  margin-top: 100px;
  margin-left: 400px !important;
}
.divb{
   
    padding: 15px 25px; /* Espace intérieur */
    font-family: 'Arial', sans-serif; /* Police lisible */
    width: 60%; /* Largeur fixe */
    margin: 20px auto; /* Centrer l'élément */
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Légère ombre */
    text-align: justify; /* Alignement justifié */
    color: #333; /* Couleur de texte */
    line-height: 1.6; /* Espacement des lignes */
    margin-top: 150px;
    
}

.pb{
    
    font-size: 15px; /* Taille du texte */
  color: #d32f2f;
  font-weight: bold;
    width: 120%;
    display: block;
    
}

.spanb{
    font-weight: bold;
    color: #d32f2f; /* Texte en rouge vif pour les informations importantes */
    font-size: 20px;
}
.stampa{
    position: absolute;
    margin-left: 170px;
    margin-top: 80px
}

.header-im{
    width: 190px;
    position: absolute;
    margin-left: 470px;
    margin-top: 70px;

}
i{
    color: #fff;
}


    </style>
</head>
<body>
    <img class="header-im" src="{{ public_path('images/im.png') }}" alt="Logo">
    <div>
        <h4 class="header-img" >S.N°: {{ $attestation->numero_de_serie }} </h4>
    </div>
    <div class="div-att">
        <p class="p-att"> ATTESTATION <b class="h-att">DE FORMATION</b></p>
       
    </div>
    <div class="divs-att">
     <p class="pas-att"> Cette attestation est attribuée à</p>
     <h4 class="has-att">{{ $attestation->personne_name }} , {{ $attestation->cin }}</h4>
    </div>
    <div class="divb">
        <p class="pb">Il a subi avec succès toutes les épreuves prévues pour l'obtention de l'attestation de formation ALL-IN-ONE :</p>
        <div style="">
            <div style="position: absolute; margin-left:-120px; font-size:14px; ">
                <ul>
                    <li>administration et gestion des routeurs et des suitches CISCO</li>
                    <li>Installation et administration de windows serveur 2022/2025</li>
                    <li>virtualisation de serveur avec VMWare VSPHERE (ESXI)</li>
                    <li>Conception et mise en place des architectures réseaux ( câblage, montage des raeles, surveillance,…)</li>
                </ul>
            </div>
            <div style="position: absolute; margin-left:370px; font-size:14px;">
                <ul>
                    <li>cloud computing avec Microsoft azure</li>
                    <li>sauvegarde et réplication des données avec</li>
                    <li>sécurité des infrastructures réseau avec fortigate</li>
                    
                </ul>
            </div>
        </div>
    </div>
    
    <div class="stampa">
        <p> Fait à Casablanca, le {{ date('d/m/Y') }}.</p>
    </div>
    <div style="position: absolute; margin-left:770px; font-weight: bold;
            font-size: 1rem;
            color: rgb(214, 20, 20); margin-top:80px;">
        <p>Cachet de l’entreprise</p>
        @if ($attestation->afficher_cachet)
        <div style="margin-left:10px">
            <img class="header-imgg" src="{{ public_path('images/testcah-removebg-preview.png') }}" alt="" style="position: relative; margin-top:-10px; width:150px; transform: rotate(27deg);">
        </div>
    @endif
    </div>
    <div style="text-align: center;margin-top:200px;  position:absolute; margin-left:320px">
        <hr style="border: none; border-top: 2px solid #df0202; margin: 0px auto; width: auto;" />
        <footer>
            <h4 style="margin: 10px 0; color:#df0202; font-weight: bold; text-transform:uppercase;">Union IT Services</h4>
            <p style="margin: 5px 0; font-size: 13px; line-height: 1.2;">
                Siège Sociale : App 1, Etg 1, N° 68, Rue San Saëns, Belvedere, Casablanca (CP 20300) <br>
                Email : contact@uits.ma  |  Tél : 05 22 24 04 83 / 06 60 21 07 73  |    Site web : <a href="http://www.uits.ma" style="color: #df0202; text-decoration: none;">www.uits.ma</a> <br>
                ICE : 001883395000003  |  RC : 372657  |  NIF : 20754203  |  CNSS : 5429693  |  Patente : 31292380 <br>
              
            </p>
        </footer>
    </div> 
</body>
</html>
