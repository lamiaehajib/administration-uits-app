<x-app-layout>
    <div class="container mt-5">
        <div class="card shadow-lg p-4">
            <h2 class="fw-bold text-center text-primary mb-4">
                <i class="fas fa-chart-bar" style="font-size: 1.5rem;"></i> Total des Achats, Ventes, Stock et Bénéfice
            </h2>

            <table class="table table-striped table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th><i class="fas fa-shopping-cart" style="font-size: 1.2rem;"></i> Total Achats (DH)</th>
                        <th><i class="fas fa-cash-register" style="font-size: 1.2rem;"></i> Total Ventes (DH)</th>
                        <th><i class="fas fa-coins" style="font-size: 1.2rem;"></i> Bénéfice (DH)</th> 
                        <th><i class="fas fa-boxes" style="font-size: 1.2rem;"></i> Total Stock (Quantité)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="fw-bold">
                        <td class="text-danger">{{ number_format($totalAchat, 2) }} DH</td>
                        <td class="text-success">{{ number_format($totalVente, 2) }} DH</td>
                        <td class="text-warning">{{ number_format($bénéfice, 2) }} DH</td> 
                        <td class="text-info">{{ number_format($totalStock, 0) }}</td>
                    </tr>
                </tbody>
            </table>

            <!-- Formulaire pour afficher les totaux -->
            <div class="d-flex justify-content-between">
                <form action="{{ route('produits.totals') }}" method="GET" class="form-card p-4 w-48 bg-light rounded shadow-lg">
                    <label for="date" class="form-label fw-bold">📅 Sélectionnez le mois :</label>
                    <input type="month" name="date" id="date" value="{{ $date }}" class="form-control mb-3" style="width: 100%;">

                    <button type="submit" class="btn btn-danger w-100 mb-3 hover-shadow">
                        <i class="fas fa-search"></i> Afficher les Totaux
                    </button>
                </form>

                <form action="{{ route('rapport.pdf') }}" method="GET" class="form-card p-4 w-48 bg-light rounded shadow-lg">
                    <label for="date" class="form-label fw-bold">📅 Sélectionnez la date :</label>
                    <input type="date" name="date" id="date" value="{{ now()->format('Y-m-d') }}" class="form-control mb-3" style="width: 100%;">

                    <button type="submit" class="btn btn-danger w-100 hover-shadow">
                        <i class="fas fa-file-pdf"></i> Télécharger le Rapport PDF
                    </button>
                </form>
            </div>
            
        </div>
    </div>

    <!-- FontAwesome pour les icônes -->
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>

    <!-- Styles supplémentaires pour les formulaires et effets -->
    <style>
        .hover-shadow {
            transition: all 0.3s ease;
        }

        .hover-shadow:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
            background-color: #e02f2f;
            transform: translateY(-2px);
        }

        .form-card {
            border: 1px solid #ddd;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .form-card input {
            padding-left: 30px; /* Space for the icon */
        }

        .form-card .form-label {
            font-size: 1rem;
            font-weight: 600;
            color: #555;
        }

        .form-card button {
            background-color: #C2185B;
            border: none;
        }

        .d-flex.justify-content-between {
            gap: 20px;
        }

    </style>
</x-app-layout>
