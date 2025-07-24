<x-app-layout>
    <style>
        /* Style général pour les boutons */
.div-flex {
    display: flex;
    gap: 15px; /* Espace entre les boutons */
    align-items: center;
    justify-content: center; /* Alignement des boutons */
    padding: 10px;
    margin-top: 34px;
}

.has-borderr {
    display: flex;
    align-items: center;
    background-color: #ffffff;
    color: #f7f7f7;
    border: 2px solid #ddd;
    border-radius: 8px;
    padding: 10px 15px;
    font-size: 16px;
    cursor: pointer;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    
}

.has-border:hover {
    background-color: #f5f5f5;
    border-color: #bbb;
    box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
}

.has-border i {
    margin-right: 8px; /* Espace entre l'icône et le texte */
    font-size: 18px;
}

.has-border span {
    font-weight: 500;
    text-transform: capitalize;
}

.has-border a {
    color: inherit;
    text-decoration: none;
    margin-left: 5px; /* Espacement si plusieurs liens */
}

/* Adaptation pour mobile */
@media (max-width: 768px) {
    .div-flex {
        flex-direction: column;
        gap: 10px;
    }
}


        .swal-popup {
            background-color: #f9f9f9;
        }

        .swal-html-container a {
            text-decoration: none;
            color: #007bff;
            margin-bottom: 10px;
            display: block;
            font-size: 14px;
        }

        .swal-html-container a:hover {
            color: #0056b3;
        }

        .swal-confirm-btn {
            background-color: #D32F2F;
            color: #fff;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .swal-confirm-btn:hover {
            background-color: #b71c1c;
        }
        a {
            text-decoration: none !important;
            color: #ffffff;
            text-transform: uppercase;
        }
        span{
            text-transform: uppercase;
        }
        .card-container {
  display: flex;
  gap: 30px;
  justify-content: center;
}

.card {
    width: 496px;
    height: 401px;
    perspective: 1500px;
    border: none;
}


.card .card-content {
  width: 100%;
  height: 100%;
  background-color: #fff;
  border-radius: 15px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
  
  justify-content: center;
  align-items: center;
  text-align: center;
  transition: transform 0.5s;
  transform-style: preserve-3d;
  

    position: absolute;
}




.card-content h2 {
  font-size: 24px;
  color: #333;
  margin-bottom: 20px;
  text-transform: uppercase;
    border-bottom: 1px dashed #d2d1d1;
    width: 300px;
    margin-left: 100px;
    margin-top: 32px;
}

.card-container {
    display: flex;
    gap: 114px;
    margin-bottom: 57px;
    justify-content: center;
}

.card-content::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: linear-gradient(235deg, #d81c1c21, #fefefe33);
  border-radius: 15px;
  z-index: -1;
}
.info-text {
        font-size: 1.2em;
        color: #444;
        margin: 10px 0;
        font-family: Arial, sans-serif;
    }

    .highlight {
        background: linear-gradient(135deg, #f60404, #000000);
    -webkit-background-clip: text;
    color: transparent;  /* Rend le texte transparent pour afficher uniquement le dégradé */
    font-weight: bold;
    }

    .custom-btn {
        background: linear-gradient(135deg, #f60404, #000000);
        color: white;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 5px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        transition: background-color 0.3s ease;
        margin-left: 83px;
    }

    .custom-btn:hover {
        background-color: #21867a;
    }

    .hight{
     background: linear-gradient(135deg, #f60404, #000000);
    -webkit-background-clip: text;
    color: transparent;  /* Rend le texte transparent pour afficher uniquement le dégradé */
    font-weight: bold;
    text-align: center;
}
.div-car{

}
div#div-attestation {
    margin-left: 300px;
}
div#card-div {
    width: 400px;
}

    /* .search-form {
        max-width: 500px;
        margin: auto;
        padding: 15px;
    }
    .search-form .input-group {
        overflow: hidden;
    }
    .search-form .form-control {
        border-radius: 0;
        height: 45px;
    }
    .search-form .btn-primary {
        background-color: #007bff;
        border: none;
        border-radius: 0;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 45px;
    }
    .search-form .btn-primary:hover {
        background-color: #0056b3;
    } */

    .search-form {
        display: flex;
    align-items: center;
    background-color: #fff;
    width: 68%;
    text-align: center;
    margin-left: 100px;
    border-radius: 6px;
}

.input-wrapper {
    flex: 1;
}

.search-form input.form-control {
    width: 92%;
    border: none;
    height: 38px;
}
.search-form button {
    display: flex;
    align-items: center;
    justify-content: center;
}

button.btn-nin {
    margin-right: 23px;
    height: 26px;
    color: #8f0606;
    width: 40px;
    margin-top: 10px;
    background: none;
    border:none;
}
i.fas.fa-search {
    font-size: 28px;
}
.d-fl{
    margin-top: 10px;
}
.my-4 {
    margin-top: -0.5rem !important;
    margin-bottom: 1.5rem !important;
}
/* CSS */
.modal {
    display: none; /* Hides modal by default */
    position: fixed;
    z-index: 999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent background */
}

.modal-content {
    background-color: white;
    margin: 15% auto;
    padding: 20px;
    border-radius: 10px;
    width: 80%;
    max-width: 500px;
    position: relative;
}

.close-btn {
    position: absolute;
    top: 10px;
    right: 15px;
    font-size: 20px;
    cursor: pointer;
}

.btn-open-search {
    margin: 20px;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    border: none;
    border-radius: 5px;
    cursor: pointer;
}

.btn-open-search:hover {
    background-color: #0056b3;
}
#clasd-m{
   
   background: none; 
}

.daily-reussites-count {
    text-transform: uppercase;
}
.btnsearch{
background: none;
border: none;
}
button#open-search-modal {
    width: 40px;
    height: 21px;
    background: no-repeat;
    border: none;
    font-size: 41px;
}
#span-ji {
    font-size: 27px;
}

    </style>
    <div class="container my-4">
    <div class="card shadow-lg border-0" style="background-color: black; color: white; height: 204px !important; display: flex; flex-direction: column; justify-content: space-between;    width: 100% !important;
    margin-top: 0px !important;">
        <div class="card-header text-center" style="background: #cedce7;
        background: -webkit-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
        background: -o-linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
        background: linear-gradient(45deg,  #cedce7 0%,#596a72 100%);
        background: linear-gradient(178deg,rgb(18, 17, 17),#f60404,#f60404,rgb(13, 13, 13)); color: white;">
            <h1 class="font-semibold">
                <?php
                    $hour = \Carbon\Carbon::now()->format('H');
                    $greeting = ($hour >= 6 && $hour < 19) ? 'Bonjour' : 'Bonsoir';
                ?>
                {{ $greeting }}, {{ Auth::user()->name }}
            </h1>
        </div>
       



<div class="div-flex">
        <button type="button" id="arecuButton" class="has-borderr">
            <i class="fas fa-receipt"></i>
            <span> les reçus <i id="i-fetch" class="fa fa-chevron-down"></i></span>
        </button>

        <button type="button" id="adevisButton" class="has-borderr">
            <i class="fas fa-file-invoice"></i>
            <span> les devis <i id="i-fetch" class="fa fa-chevron-down"></i></span>
        </button>

        <button type="button" id="facturButton" class="has-borderr">
           
            <i class="fas fa-file-invoice-dollar"></i>
            <span>les factures<i id="i-fetch" class="fa fa-chevron-down"></i></span>
        </button>
</div>
         <div class="div-car">
        <h3 class="hight">
            les reçus
        </h3>
        <div class="card-container"> 
            <!-- بطاقة Reçus de Formation -->
            <div class="card">
                <div class="card-content">
                    <h2>Reçus de Formation
                        <button data-open-modal="search-modal-formation" class="btnsearch">
                            <i id="clasd-m" class="fas fa-search"></i>
                        </button>
                    </h2>
                    <div id="search-modal-formation" class="modal">
                        <div class="modal-content">
                            <span class="close-btn">&times;</span>
                            <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                                <div class="input-wrapper">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher dans Reçus de Formation">
                                </div>
                                <input type="hidden" name="filter" value="fomationre">
                                <button class="btn-nin" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="daily-reussites-count">
                        <h4>le nombre de reçus d'aujourd'hui:    <span id="span-ji" class="highlight">{{ $dailyfomationreCount }}</span></h4>
                    </div>
                    <!-- عرض النتائج -->
                    @forelse($fomationre as $fomationr)
                        <h5 class="info-text">- Nom et prénom : <span class="highlight">{{ $fomationr->nom }} {{ $fomationr->prenom }}</span></h5>
                        <div style="display: flex; justify-content:center;">
                            <h5 class="info-text">cin: <span class="highlight">{{ $fomationr->CIN }}</span></h5>
                            <h5>
                                <a href="{{ route('reussitesf.pdf', $fomationr->id) }}" class="btn btn-info custom-btn"><i class="fa fa-download"></i></a>
                            </h5>
                        </div>
                    @empty
                        <p>Aucune donnée trouvée.</p>
                    @endforelse
                    <div class="d-fl">
                        <nav aria-label="Page navigation">
                            {{ $fomationre->links('pagination.custom') }}
                        </nav>
                    </div>
                </div>
            </div>
            
            <!-- بطاقة Reçus de Stage -->
            <div class="card">
                <div class="card-content">
                    <div class="cas-mar">
                        <h2 class="h-stage">Reçus de Stage
                            <button data-open-modal="search-modal-stage" class="btnsearch">
                                <i id="clasd-m" class="fas fa-search"></i>
                            </button>
                        </h2>
                    </div>
                    <div id="search-modal-stage" class="modal">
                        <div class="modal-content">
                            <span class="close-btn">&times;</span>
                            <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                                <div class="input-wrapper">
                                    <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher dans Reçus de Stage">
                                </div>
                                <input type="hidden" name="filter" value="reussites">
                                <button class="btn-nin" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="daily-reussites-count">
                        <h4>le nombre de reçus d'aujourd'hui:      <span id="span-ji" class="highlight">{{ $dailyReussitesCount }}</span></h4>
                    </div>
                    <!-- عرض النتائج -->
                    @forelse($reussites as $reussite)
                        <h5 class="info-text">Nom et prénom : <span class="highlight">{{ $reussite->nom }} {{ $reussite->prenom }}</span></h5>
                        <div style="display: flex; justify-content:center;">
                            <h5 class="info-text">cin: <span class="highlight">{{ $reussite->CIN }}</span></h5>
                            <h5>
                                <a href="{{ route('reussites.pdf', $reussite->id) }}" class="btn btn-info custom-btn"><i class="fa fa-download"></i></a>
                            </h5>
                        </div>
                    @empty
                        <p>Aucune donnée trouvée. </p>
                    @endforelse
                    <div class="d-fl">
                        <nav aria-label="Page navigation">
                            {{ $reussites->links('pagination.custom') }}
                        </nav>
                    </div>
                </div>
            </div>
        </div>


        <div class="card-container">
    <!-- بطاقة Devis -->
    <div class="card">
        <div class="card-content">
            <h2>Devis
                <button data-open-modal="search-modal-devis" class="btnsearch">
                    <i id="clasd-m" class="fas fa-search"></i>
                </button>
            </h2>
            <div id="search-modal-devis" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                        <div class="input-wrapper">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher dans Devis">
                        </div>
                        <input type="hidden" name="filter" value="devis">
                        <button class="btn-nin" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="daily-reussites-count">
                <h4>le nombre de devis d'aujourd'hui: <span id="span-ji" class="highlight">{{ $dailyDevisCount }}</span></h4>
            </div>
            
            @forelse($devis as $devi)
    <h5 class="info-text">Devis N°: <span class="highlight">{{ $devi->devis_num }}</span></h5>
    <div style="display: flex; justify-content:center;">
        <h5 class="info-text">Client: <span class="highlight">{{ $devi->client }}</span></h5>
        <h5>
            <a href="{{ route('devis.downloadPDF', $devi->id) }}" class="btn btn-info custom-btn"><i class="fa fa-download"></i></a>
        </h5>
    </div>
@empty
    <p>Aucune donnée trouvée.</p>
@endforelse
            <div class="d-fl">
                <nav aria-label="Page navigation">
                    {{ $devis->links('pagination.custom') }}
                </nav>
            </div>
        </div>
    </div>

 
    <div class="card">
        <div class="card-content">
            <h2>Factures
                <button data-open-modal="search-modal-factures" class="btnsearch">
                    <i id="clasd-m" class="fas fa-search"></i>
                </button>
            </h2>
            <div id="search-modal-factures" class="modal">
                <div class="modal-content">
                    <span class="close-btn">&times;</span>
                    <form method="GET" action="{{ route('dashboard') }}" class="search-form">
                        <div class="input-wrapper">
                            <input type="text" name="search" value="{{ request('search') }}" class="form-control" placeholder="Rechercher dans Factures">
                        </div>
                        <input type="hidden" name="filter" value="factures">
                        <button class="btn-nin" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>
            <div class="daily-reussites-count">
                <h4>le nombre de factures d'aujourd'hui: <span id="span-ji" class="highlight">{{ $dailyFacturesCount }}</span></h4>
            </div>
            
            @forelse($factures as $facture)
                <h5 class="info-text">Facture N°: <span class="highlight">{{ $facture->facture_num }}</span></h5>
                <div style="display: flex; justify-content:center;">
                    <h5 class="info-text">client: <span class="highlight">{{ $facture->client }}</span></h5>
                    <h5>
                        <a href="{{ route('factures.downloadPDF', $facture->id) }}" class="btn btn-info custom-btn"><i class="fa fa-download"></i></a>
                       
                    </h5>
                </div>
            @empty
                <p>Aucune donnée trouvée.</p>
            @endforelse
            <div class="d-fl">
                <nav aria-label="Page navigation">
                    {{ $factures->links('pagination.custom') }}
                </nav>
            </div>
        </div>
    </div>
</div>

        
        </div>
            
        
        
   <div>
    <script>
        document.getElementById('facturButton').addEventListener('click', function () {
            Swal.fire({
            title: 'Liste des Factures',
            html: `
                <a href="{{ route('factures.index') }}" style="display:block; margin-bottom: 15px; color: #C2185B; font-size: 18px; text-decoration: none; background-color: #f8f0f3; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-invoice-dollar" style="color: #C2185B;"></i> factures de projet
                   
                </a>
                <a href="{{ route('facturefs.index') }}" style="display:block; margin-bottom: 15px; color: #4CAF50; font-size: 18px; text-decoration: none; background-color: #f1f8f0c2; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-invoice-dollar" style="color: #35c218;"></i> factures de formation
                   
                </a>
            `,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Fermer',
            customClass: {
                popup: 'swal-popup',
                title: 'swal-title',
                htmlContainer: 'swal-html-container',
                closeButton: 'swal-close-btn',
                confirmButton: 'swal-confirm-btn'
            },
            background: '#ffffff',
            iconColor: '#C2185B',
            buttonsStyling: false,
            didOpen: () => {
                const popup = document.querySelector('.swal-popup');
                const title = document.querySelector('.swal-title');
                
                // Apply custom styles to the title
                title.style.display = 'block';
                title.style.marginBottom = '30px';
                title.style.borderBottom = '2px dashed #cfcfcf';
                title.style.textTransform = 'uppercase';

                popup.style.padding = '30px';
                popup.style.borderRadius = '15px';
                popup.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            }

            });
        });
    </script>



    <script>
        document.getElementById('arecuButton').addEventListener('click', function () {
            Swal.fire({
            title: 'Liste des Reçus',
            html: `
                <a href="{{ route('reussites.index') }}" style="display:block; margin-bottom: 15px; color: #C2185B; font-size: 18px; text-decoration: none; background-color: #f8f0f3; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-alt" style="color: #C2185B;"></i> Reçu de Stage
                </a>
                <a href="{{ route('reussitesf.index') }}"  style="display:block; margin-bottom: 15px; color: #4CAF50; font-size: 18px; text-decoration: none; background-color: #f0fff4; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-alt" style="color: #4CAF50;"></i> Reçu de Formation
                </a>
                 <a href="{{ route('ucgs.index') }}"  style="display:block; margin-bottom: 15px; color: #4CAF50; font-size: 18px; text-decoration: none; background-color: #f0fff4; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-alt" style="color: #4CAF50;"></i> Reçu de ucgs
                </a>
            `,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Fermer',
            customClass: {
                popup: 'swal-popup',
                title: 'swal-title',
                htmlContainer: 'swal-html-container',
                closeButton: 'swal-close-btn',
                confirmButton: 'swal-confirm-btn'
            },
            background: '#ffffff',
            iconColor: '#C2185B',
            buttonsStyling: false,
            didOpen: () => {
                const popup = document.querySelector('.swal-popup');
                const title = document.querySelector('.swal-title');
                
                // Apply custom styles to the title
                title.style.display = 'block';
                title.style.marginBottom = '30px';
                title.style.borderBottom = '2px dashed #cfcfcf';
                title.style.textTransform = 'uppercase';

                popup.style.padding = '30px';
                popup.style.borderRadius = '15px';
                popup.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            }

            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.getElementById('adevisButton').addEventListener('click', function () {
            Swal.fire({
            title: 'Liste des Devis',
            html: `
                <a href="{{ route('devis.index') }}" style="display:block; margin-bottom: 15px; color: #C2185B; font-size: 18px; text-decoration: none; background-color: #f8f0f3; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-alt" style="color: #C2185B; margin-right: 10px;"></i> Devis de projet
                </a>
                <a href="{{ route('devisf.index') }}" style="display:block; margin-bottom: 15px; color: #4CAF50; font-size: 18px; text-decoration: none; background-color: #f0fff4; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="fas fa-file-alt" style="color: #4CAF50; margin-right: 10px;"></i> Devis de Formation
                </a>
            `,
            showCloseButton: true,
            focusConfirm: false,
            confirmButtonText: 'Fermer',
            customClass: {
                popup: 'swal-popup',
                title: 'swal-title',
                htmlContainer: 'swal-html-container',
                closeButton: 'swal-close-btn',
                confirmButton: 'swal-confirm-btn'
            },
            background: '#ffffff',
            iconColor: '#C2185B',
            buttonsStyling: false,
            didOpen: () => {
                const popup = document.querySelector('.swal-popup');
                const title = document.querySelector('.swal-title');
                
                // Apply custom styles to the title
                title.style.display = 'block';
                title.style.marginBottom = '30px';
                title.style.borderBottom = '2px dashed #cfcfcf';
                title.style.textTransform = 'uppercase';

                popup.style.padding = '30px';
                popup.style.borderRadius = '15px';
                popup.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            }
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
    // التعامل مع النقر على أزرار فتح النوافذ
    document.querySelectorAll("[data-open-modal]").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const modalId = btn.getAttribute("data-open-modal");
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.style.display = "block";
            }
        });
    });

    // التعامل مع أزرار الإغلاق
    document.querySelectorAll(".close-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const modal = btn.closest(".modal");
            if (modal) {
                modal.style.display = "none";
            }
        });
    });

    // إغلاق النموذج عند النقر خارج النوافذ
    window.addEventListener("click", function (event) {
        document.querySelectorAll(".modal").forEach(function (modal) {
            if (event.target === modal) {
                modal.style.display = "none";
            }
        });
    });
});


    </script>
</x-app-layout>
