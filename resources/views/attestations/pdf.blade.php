<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attestation de Stage</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Serif:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* Style global */
      *{
        margin: 0;
      }
        body {
            background-image: url('{{ public_path('images/G.jpg') }}');
    background-repeat: no-repeat;
    background-size: cover; /* Ajuste l'image pour couvrir tout le fond */
    font-family: Arial, sans-serif;
    margin: 0;
        }


        .header-img {
            
            width: 200px;
            height: auto;
            object-fit: contain; /* Assure que l'image est contenue dans ses dimensions */
        }

        /* Titre avec style professionnel */
        .header {
            text-align: center;
            margin-top: 120px;
            margin-bottom: 30px;
            
        }

        .header h1 {
            font-size: 2.2rem;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 0;
           
        }

        .header .subtitle {
            font-size: 1.2rem;
            font-weight: bold;
            margin-top: 5px;
            text-transform: uppercase;
        }

        .header .divider {
            width: 60%;
            height: 2px;
            background-color: #000;
            margin: 10px auto;
        }

        /* Contenu */
        .content {
            margin: 20px;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            font-size: 1rem;
        }

        .content p {
            margin-bottom: 15px;
            text-align: justify;
        }

        .content b {
            color: #b71c1c;
        }

        /* Cachet */
        .stamp {
            text-align: center;
            margin-top: 40px;
            font-weight: bold;
            font-size: 1rem;
            color: #b71c1c;
            
        }
    </style>
</head>
<body>
    
    <div>
        <img class="header-img" src="{{ public_path('images/im.png') }}" alt="Logo">
    </div>
    <div  >
    <div class="header">
        <h1>ATTESTATION</h1>
        <div class="divider"></div>
        <div class="subtitle">DE STAGE</div>
    </div>
    <div class="content" style=" font-family: serif; font-size: 18px; text-align: justify; margin: 20px; margin-top:40px !important; ">
        
        <p style="line-height: 2.8; ">
            Je soussigné <strong style="color: rgb(214, 20, 20);">Monsieur Khalid KATKOUT</strong> agissant en tant que gérant et fondateur de la société <strong>(Union IT Services)</strong>, certifie par la présente attestation que <strong style="color: red;">M.{{ $attestation->stagiaire_name }}</strong>, titulaire de la CIN <strong style="color: red;">{{ $attestation->stagiaire_cin }}</strong>, a effectué un stage au sein de notre entreprise du <strong style="color: red;">{{ $attestation->date_debut }}</strong> au <strong style="color: red;">{{ $attestation->date_fin }}</strong> en qualité de <strong style="color: red;">{{ $attestation->poste }}</strong>.
        </p>
        <p>
            Cette attestation est délivrée à l'intéressée pour servir et valoir ce que de droit.
        </p>
    </div>
    
    <div style="margin-left:40px;">
        <p> Fait à Casablanca, le {{ date('d/m/Y') }}.</p>
    </div>
    <div style="position: absolute; margin-left:500px; font-weight: bold;
            font-size: 1rem;
            color: rgb(214, 20, 20); margin-top:60px;">
        <p>Cachet de l’entreprise</p>
        @if ($attestation->afficher_cachet)
        <div style="margin-left:10px">
            <img class="header-imgg" src="{{ public_path('images/testcah-removebg-preview.png') }}" alt="" style="position: relative; margin-top:-10px; width:150px; transform: rotate(27deg);">
        </div>
    @endif
    </div>
    <footer style="text-align: center;  position: fixed; bottom: 30; width: 100%;  z-index: 1000;">
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
