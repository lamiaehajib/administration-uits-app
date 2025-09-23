<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQYbYMpwVNrGj39HPPcodSyE7KPLB7UqM1Ny6WFAQx1Q3pld0TUf9xj6am2DYspgZPXQ58&usqp=CAU" type="image/png">

    <title>nav-facturation</title>
    <style>
        body {
            margin: 0;
            font-family: 'Ubuntu', sans-serif;
            background-color: #ffffff;
        }

        .sidebar {
            width: 250px;
            color: #fff;
            height: 100vh;
            /* position: fixed; */
            top: 0;
            left: 0;
            display: flex;
            flex-direction: column;
            padding: 57px 0;
            background-color: none;
        }

        .sidebar-menu button {
            width: 100%;
            background-color: #000;
            color: #fbfbfb;
            border: none;
            padding: 15px 20px;
            text-align: left;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 15px;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .sidebar-menu button:hover {
            background-color: #202020;
        }

        .sidebar-menu button i {
            font-size: 20px;
        }

        .sidebar-menu span {
            font-size: 16px;
            font-weight: 500;
            text-transform: uppercase !important;
        }

        button {
            background: linear-gradient(135deg, #050505, #f30c0c);
            margin-bottom: 10px !important;
            display: flex;
            gap: 20px;
        }

        .sidebar-menu a {
            text-decoration: none !important;
            color: #ffffff;
        }

        .sidebar-menu a:hover {
            text-decoration: underline;
        }

        /* Align icons and text to the left */
        .sidebar-menu button {
            justify-content: flex-start;  /* Align items to the left */
        }

        .sidebar-menu button i {
            margin-right: 10px; /* Space between icon and text */
        }

        .dropdown-container {
            width: 100%;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            top: 100%;
            left: 0;
            background-color: #333;
            width: 100%;
        }

        .dropdown-menu a {
            color: #fff;
            padding: 10px 20px;
            display: block;
            text-decoration: none;
        }

        .dropdown-menu a:hover {
            background-color: #444;
        }

    </style>
</head>
<body>
    <nav class="sidebar">
        <div class="sidebar-menu">
            <!-- Sidebar buttons -->
            <button type="button">
                <a href="{{ route('dashboard') }}"><i class='bx bx-home'></i></a>
                <span><a href="{{ route('dashboard') }}">accueil</a></span>
            </button>

            <button type="button">
                <i class='bx bx-group'></i>
                <span><a href="{{ route('users.index') }}">Responsable Administratif</a></span>
            </button>

            <button type="button" id="attestationsButton">
                <i class="fas fa-certificate"></i>
                <span> attestations <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>
            
            <button type="button" id="recuButton">
                <i class="fas fa-receipt"></i>
                <span> les reçus <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>


            

            <button type="button" id="devisButton">
                <i class="fas fa-file-invoice"></i>
                <span> les devis <i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button" id="bonDeCommandeButton">
                <i class="fas fa-file-contract"></i>
                <span>Bon.Commande<i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button" id="facturButto">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>les factures<i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>

            <button type="button">
                <i class="fas fa-file-contract"></i>
                <span><a href="{{ route('bon_livraisons.index') }}">bon de livraisons</a></span>
            </button>
            
            <button type="button" id="produitButto">
                <i class="fas fa-box"></i>
                <span>produit ucgs<i id="i-fetch" class="fa fa-chevron-down"></i></span>
            </button>
           @can('role-list')
            <button>
                <a href="{{ route('roles.index') }}"><i class="fas fa-user-shield"></i></a>
                <span><a href="{{ route('roles.index') }}">roles</a></span>
            </button>
            @endcan

             @can('role-list')
            <button>
                <a href="{{ route('download.backup') }}"><i class="fas fa-user-shield"></i></a>
                <span><a href="{{ route('download.backup') }}">backup</a></span>
            </button>
            @endcan
            <!-- Profile dropdown -->
            <div class="dropdown-container">
                <button class="icon" id="profileButton">
                    <i class='bx bx-user'></i><br>
                    <p class="p-fetch">Profile <i id="i-fetch" class="fa fa-chevron-down"></i></p>
                </button>
              <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
    @csrf
</form>
            </div>
        </div>
    </nav>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Event listener for the sidebar buttons
        const buttons = [
            { id: 'attestationsButton', title: 'Liste des Attestations', links: [
                { href: '{{ route('attestations.index') }}', label: 'Attestations de Stage', icon: 'fas fa-file-alt', color: '#C2185B' },
                { href: '{{ route('attestations_formation.index') }}', label: 'Attestations de Formation', icon: 'fas fa-file-alt', color: '#35c218' },
                { href: '{{ route('attestations_allinone.index') }}', label: 'Attestations de Formation ALL IN ONE', icon: 'fas fa-file-alt', color: '#5683cd' }
            ]},
            { id: 'recuButton', title: 'Liste des Reçus', links: [
                { href: '{{ route('reussites.index') }}', label: 'Reçu de Stage', icon: 'fas fa-file-alt', color: '#C2185B' },
                { href: '{{ route('reussitesf.index') }}', label: 'Reçu de Formation', icon: 'fas fa-file-alt', color: '#4CAF50' },
                { href: '{{ route('ucgs.index') }}', label: 'Reçu de ucgs', icon: 'fas fa-file-alt', color: '#4CAF50' }
            ]},


            


            { id: 'devisButton', title: 'Liste des Devis', links: [
                { href: '{{ route('devis.index') }}', label: 'Devis de projet', icon: 'fas fa-file-alt', color: '#C2185B' },
                { href: '{{ route('devisf.index') }}', label: 'Devis de Formation', icon: 'fas fa-file-alt', color: '#4CAF50' }
            ]},



             {
                id: 'bonDeCommandeButton',
                title: 'Bon.Commande',
                links: [
                    { href: '{{ route('bon_de_commande.index') }}', label: 'Bon de Commande reçus', icon: 'fas fa-file-contract', color: '#FF9800' },
                    { href: '{{ route('bon_commande_r.index') }}', label: 'Bon de Commande envoyés', icon: 'fas fa-file-invoice-dollar', color: '#C2185B' },
                ]
            },


            { id: 'facturButto', title: 'Liste des Factures', links: [
                { href: '{{ route('factures.index') }}', label: 'Factures de projet', icon: 'fas fa-file-invoice-dollar', color: '#C2185B' },
                { href: '{{ route('facturefs.index') }}', label: 'Factures de Formation', icon: 'fas fa-file-invoice-dollar', color: '#4CAF50' }
            ]},
            { id: 'produitButto', title: 'Liste des produits', links: [
                { href: '{{ route('categories.index') }}', label: 'les categories de produits', icon: 'fa-solid fa-boxes-stacked', color: '#C2185B' },
                { href: '{{ route('produits.index') }}', label: 'les produits', icon: 'fa-solid fa-box', color: '#ffc107' },
                { href: '{{ route('achats.index') }}', label: 'les achats',  icon: 'fa-solid fa-cart-arrow-down',  color: '#0a58ca' },
                { href: '{{ route('ventes.index') }}', label: 'les vents', icon: 'fa-solid fa-cash-register',  color: '#62fd0d' },
                { href: '{{ route('produits.totals') }}', label: 'totals', icon: 'fa-solid fa-calculator', color: '#754E1A' },
            ]},
{ id: 'profileButton', title: 'Profile Options', links: [
    { href: '{{ route('profile.edit') }}', label: 'Profile', icon: 'bx bx-user-circle', color: '#C2185B' },
    { 
        href: '#', 
        label: 'Log Out', 
        icon: 'bx bx-log-out', 
        color: '#4CAF50', 
        onclick: 'showLogoutConfirmation()'
    }
]},
        ];

        buttons.forEach(button => {
    document.getElementById(button.id).addEventListener('click', () => {
        Swal.fire({
            title: button.title,
            html: button.links.map(link => `
                <a href="${link.href}" ${link.onclick ? `onclick="${link.onclick}"` : ''} style="display:block; margin-bottom: 15px; color: ${link.color}; font-size: 18px; text-decoration: none; background-color: ${link.color + '1f'}; padding: 10px 15px; border-radius: 5px; box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);">
                    <i class="${link.icon}" style="color: ${link.color};"></i> ${link.label}
                </a>
            `).join(''),
            showCloseButton: true,
            confirmButtonText: 'Fermer',
            background: '#ffffff',
            iconColor: '#C2185B',
            didOpen: () => {
                const popup = document.querySelector('.swal-popup');
                popup.style.padding = '30px';
                popup.style.borderRadius = '15px';
                popup.style.boxShadow = '0 8px 16px rgba(0, 0, 0, 0.1)';
            }
        });
    });
});


function showLogoutConfirmation() {
    Swal.fire({
        title: 'Êtes-vous sûr ?',
        text: 'Vous allez vous déconnecter du système !',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Oui, déconnexion !',
        cancelButtonText: 'Annuler'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('logoutForm').submit();
        }
    });
}

        // Profile hover event
      
    </script>
  
</body>
</html>
