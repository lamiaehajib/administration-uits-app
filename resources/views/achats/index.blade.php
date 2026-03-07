<x-app-layout>
    <style>
        /* ===== PAGE ACHATS ===== */
        .achats-header {
            background: linear-gradient(135deg, #D32F2F 0%, #C2185B 100%);
            padding: 40px 30px;
            border-radius: 20px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(211,47,47,0.3);
            position: relative;
            overflow: hidden;
        }
        .achats-header::before {
            content: '';
            position: absolute;
            top: -50%; right: -10%;
            width: 300px; height: 300px;
            background: rgba(255,255,255,0.1);
            border-radius: 50%;
        }
        .achats-header::after {
            content: '';
            position: absolute;
            bottom: -30%; left: -5%;
            width: 250px; height: 250px;
            background: rgba(255,255,255,0.08);
            border-radius: 50%;
        }
        .achats-header h1 {
            color: white; font-weight: 700; font-size: 2.5rem;
            margin: 0 0 10px; position: relative; z-index: 1;
            display: flex; align-items: center; gap: 15px;
        }
        .achats-header p {
            color: rgba(255,255,255,0.9); margin: 0;
            font-size: 1.1rem; position: relative; z-index: 1;
        }

        /* Stats */
        .achats-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px; margin-bottom: 25px;
        }
        .achat-stat-card {
            background: white; padding: 22px;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            position: relative; overflow: hidden;
        }
        .achat-stat-card:hover { transform: translateY(-5px); box-shadow: 0 10px 30px rgba(0,0,0,0.15); }
        .achat-stat-card.blue  { border-left: 4px solid #2196F3; color: #2196F3; }
        .achat-stat-card.green { border-left: 4px solid #4CAF50; color: #4CAF50; }
        .achat-stat-card.orange{ border-left: 4px solid #FF9800; color: #FF9800; }
        .achat-stat-card.red   { border-left: 4px solid #D32F2F; color: #D32F2F; }
        .achat-stat-card.purple{ border-left: 4px solid #9C27B0; color: #9C27B0; }
        .stat-achat-icon { font-size: 1.8rem; margin-bottom: 8px; opacity: 0.8; }
        .stat-achat-value { font-size: 1.8rem; font-weight: 700; margin: 8px 0 4px; color: #333; }
        .stat-achat-label { font-size: 0.82rem; color: #666; text-transform: uppercase; letter-spacing: 0.5px; font-weight: 600; }

        /* Filter bar */
        .filter-bar-achats {
            background: white;
            border-radius: 15px;
            margin-bottom: 25px;
            box-shadow: 0 3px 15px rgba(0,0,0,0.08);
            padding: 16px 20px;
            display: flex; flex-direction: column; gap: 12px;
        }
        .filter-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }
        .filter-label-achat {
            font-size: .68rem; font-weight: 700;
            text-transform: uppercase; letter-spacing: 1px;
            color: #999; white-space: nowrap;
        }
        .filter-group-achat { display: flex; align-items: center; gap: 6px; }
        .filter-select-achat, .filter-input-achat {
            border: 1.5px solid #e0e0e0; border-radius: 9px;
            padding: 7px 13px; font-size: .84rem; color: #333;
            background: #fafaf8; outline: none; cursor: pointer;
            transition: border-color .2s, box-shadow .2s;
        }
        .filter-select-achat:focus, .filter-input-achat:focus {
            border-color: #D32F2F;
            box-shadow: 0 0 0 3px rgba(211,47,47,.1);
        }
        .filter-divider-v { width: 1px; height: 26px; background: #e0e0e0; flex-shrink: 0; }
        .search-wrap-achat { position: relative; flex: 1; min-width: 220px; }
        .search-wrap-achat input {
            width: 100%; padding: 10px 40px 10px 18px;
            border: 2px solid #e0e0e0; border-radius: 10px;
            font-size: .92rem; transition: all 0.3s;
        }
        .search-wrap-achat input:focus {
            outline: none; border-color: #D32F2F;
            box-shadow: 0 0 0 3px rgba(211,47,47,.1);
        }
        .search-wrap-achat i {
            position: absolute; right: 14px; top: 50%;
            transform: translateY(-50%); color: #aaa;
        }
        .period-badge-achat {
            margin-left: auto;
            background: #fff0f0; color: #D32F2F;
            font-size: .72rem; font-weight: 700;
            padding: 5px 13px; border-radius: 50px;
            white-space: nowrap;
        }

        /* Buttons */
        .btn-achat {
            padding: 10px 20px; border-radius: 10px;
            font-weight: 600; border: none; cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex; align-items: center; gap: 7px;
            text-decoration: none; font-size: .88rem;
        }
        .btn-achat-primary {
            background: linear-gradient(135deg, #D32F2F, #C2185B); color: white;
        }
        .btn-achat-primary:hover {
            opacity: .9; transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(211,47,47,.3); color: white;
        }
        .btn-achat-outline {
            background: white; color: #D32F2F; border: 2px solid #D32F2F;
        }
        .btn-achat-outline:hover { background: #D32F2F; color: white; transform: translateY(-2px); }
        .btn-achat-reset {
            background: white; color: #888; border: 1.5px solid #ddd;
        }
        .btn-achat-reset:hover { border-color: #D32F2F; color: #D32F2F; background: #fff0f0; }

        /* Table */
        .achats-table-card {
            background: white; border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        .achats-table { margin: 0; width: 100%; }
        .achats-table thead { background: linear-gradient(135deg, #D32F2F, #C2185B); }
        .achats-table thead th {
            color: white; font-weight: 600; padding: 16px 14px;
            text-transform: uppercase; font-size: .8rem;
            letter-spacing: .5px; border: none; text-align: center;
        }
        .achats-table tbody tr { transition: all 0.2s; border-bottom: 1px solid #f0f0f0; }
        .achats-table tbody tr:hover { background: #fdf6f6; }
        .achats-table tbody td { padding: 14px; vertical-align: middle; color: #333; text-align: center; }

        .product-table-info { display: flex; align-items: center; gap: 10px; text-align: left; }
        .product-table-avatar {
            width: 40px; height: 40px; border-radius: 10px;
            background: linear-gradient(135deg, #D32F2F, #C2185B);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 700; font-size: 1rem; flex-shrink: 0;
        }
        .product-table-details h6 { margin: 0 0 3px; font-weight: 600; color: #333; font-size: .9rem; }
        .product-table-details small { color: #999; font-size: .78rem; font-family: monospace; }

        .fournisseur-badge {
            background: linear-gradient(135deg, #FFF3E0, #FFE0B2);
            padding: 5px 13px; border-radius: 10px; color: #E65100;
            font-weight: 600; display: inline-flex; align-items: center; gap: 5px;
            font-size: .82rem;
        }
        .badge-achat { padding: 5px 12px; border-radius: 20px; font-weight: 600; font-size: .78rem; display: inline-block; }
        .badge-info-achat   { background: #E3F2FD; color: #1565C0; }
        .badge-success-achat{ background: #E8F5E9; color: #2E7D32; }
        .badge-danger-achat { background: #FFEBEE; color: #C62828; }

        .quantite-box {
            background: linear-gradient(135deg, #E8F5E9, #C8E6C9);
            padding: 7px 14px; border-radius: 9px; color: #2E7D32;
            font-weight: 700; font-size: 1rem;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .date-badge {
            background: #F5F5F5; padding: 5px 11px; border-radius: 8px;
            color: #555; font-weight: 600; font-size: .82rem;
            display: inline-flex; align-items: center; gap: 5px;
        }
        .prix-display { font-weight: 700; font-size: 1rem; }
        .prix-unitaire { color: #D32F2F; }
        .prix-total    { color: #4CAF50; font-size: 1.1rem; }

        .progress-wrap { text-align: center; min-width: 80px; }
        .progress-pct  { font-weight: 700; font-size: .82rem; margin-bottom: 4px; }

        .action-btns-achats { display: flex; gap: 7px; justify-content: center; }
        .btn-icon-achat {
            width: 34px; height: 34px; border-radius: 8px;
            display: inline-flex; align-items: center; justify-content: center;
            border: none; cursor: pointer; transition: all .2s; text-decoration: none;
        }
        .btn-icon-achat:hover { transform: translateY(-2px); }
        .btn-edit-achat   { background: #FFF3E0; color: #F57C00; }
        .btn-edit-achat:hover   { background: #F57C00; color: white; }
        .btn-delete-achat { background: #FFEBEE; color: #C62828; }
        .btn-delete-achat:hover { background: #C62828; color: white; }

        .empty-achats { text-align: center; padding: 55px 20px; }
        .empty-achats i { font-size: 3.5rem; color: #ccc; margin-bottom: 15px; display: block; }
        .empty-achats h4 { color: #999; margin-bottom: 8px; }
        .empty-achats p  { color: #bbb; }

        .pagination-achats { padding: 18px; display: flex; justify-content: center; gap: 8px; }

        @media (max-width: 768px) {
            .achats-header h1 { font-size: 1.8rem; }
            .achats-stats     { grid-template-columns: 1fr 1fr; }
            .filter-row       { flex-direction: column; align-items: stretch; }
        }
        @media (max-width: 480px) {
            .achats-stats { grid-template-columns: 1fr; }
        }
    </style>

    <div class="container-fluid">

        {{-- ── HEADER ── --}}
        <div class="achats-header">
            <h1><i class="fas fa-shopping-cart"></i> Gestion des Achats</h1>
            <p>Suivez et gérez tous vos achats et approvisionnements</p>
        </div>

        {{-- ── STAT CARDS (filtrées) ── --}}
        <div class="achats-stats">
            <div class="achat-stat-card blue">
                <div class="stat-achat-icon"><i class="fas fa-receipt"></i></div>
                <div class="stat-achat-value">{{ number_format($statsFilters['total_achats']) }}</div>
                <div class="stat-achat-label">Total Achats</div>
            </div>
            <div class="achat-stat-card green">
                <div class="stat-achat-icon"><i class="fas fa-boxes"></i></div>
                <div class="stat-achat-value">{{ number_format($statsFilters['total_quantite']) }}</div>
                <div class="stat-achat-label">Unités Achetées</div>
            </div>
            <div class="achat-stat-card orange">
                <div class="stat-achat-icon"><i class="fas fa-coins"></i></div>
                <div class="stat-achat-value" style="font-size:1.4rem;">
                    {{ number_format($statsFilters['total_montant'], 0) }}
                    <small style="font-size:.75rem;font-weight:500;">MAD</small>
                </div>
                <div class="stat-achat-label">Montant Total</div>
            </div>
            <div class="achat-stat-card red">
                <div class="stat-achat-icon"><i class="fas fa-layer-group"></i></div>
                <div class="stat-achat-value">{{ number_format($statsFilters['total_restant']) }}</div>
                <div class="stat-achat-label">Unités Restantes</div>
            </div>
            <div class="achat-stat-card purple">
                <div class="stat-achat-icon"><i class="fas fa-wallet"></i></div>
                <div class="stat-achat-value" style="font-size:1.3rem;">
                    {{ number_format($statsFilters['valeur_restante'], 0) }}
                    <small style="font-size:.75rem;font-weight:500;">MAD</small>
                </div>
                <div class="stat-achat-label">Valeur Stock Restant</div>
            </div>
        </div>

        {{-- ── FILTER BAR ── --}}
       {{-- ── FILTER BAR ── --}}
<form method="GET" action="{{ route('achats.index') }}">
<div style="
    background: white;
    border-radius: 15px;
    margin-bottom: 25px;
    box-shadow: 0 3px 15px rgba(0,0,0,0.08);
    overflow: hidden;
">
    {{-- Bande titre --}}
    <div style="
        background: linear-gradient(135deg, #D32F2F, #C2185B);
        padding: 10px 20px;
        display: flex; align-items: center; justify-content: space-between;
    ">
        <span style="color:white;font-weight:700;font-size:.85rem;letter-spacing:.5px;text-transform:uppercase;">
            <i class="fas fa-filter me-2"></i>Filtres & Recherche
        </span>
        @if(($search ?? '') || ($fournisseur ?? '') || ($category_id ?? '') || ($produit_id ?? '') || ($mois ?? '') || ($annee ?? ''))
        <a href="{{ route('achats.index') }}" style="
            color:rgba(255,255,255,.85);font-size:.78rem;font-weight:600;
            text-decoration:none;display:flex;align-items:center;gap:5px;
        ">
            <i class="fas fa-times-circle"></i> Réinitialiser
        </a>
        @endif
    </div>

    <div style="padding: 16px 20px; display:flex; flex-direction:column; gap:12px;">

        {{-- Ligne 1 : Recherche + boutons action --}}
        <div style="display:flex;gap:10px;flex-wrap:wrap;align-items:center;">

            {{-- Search --}}
            <div style="position:relative;flex:1;min-width:220px;">
                <i class="fas fa-search" style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#bbb;font-size:.82rem;"></i>
                <input type="text" name="search"
                       placeholder="Rechercher par produit / référence..."
                       value="{{ $search ?? '' }}"
                       style="
                           width:100%;padding:10px 18px 10px 38px;
                           border:2px solid #e8e4df;border-radius:10px;
                           font-size:.88rem;background:#fafaf8;outline:none;
                           transition:border-color .2s,box-shadow .2s;
                       "
                       onfocus="this.style.borderColor='#D32F2F';this.style.boxShadow='0 0 0 3px rgba(211,47,47,.1)'"
                       onblur="this.style.borderColor='#e8e4df';this.style.boxShadow='none'">
            </div>

            <button type="submit" class="btn-achat btn-achat-primary">
                <i class="fas fa-filter"></i> Filtrer
            </button>

            <div style="margin-left:auto;display:flex;gap:8px;">
                <a href="{{ route('achats.create') }}" class="btn-achat btn-achat-primary">
                    <i class="fas fa-plus-circle"></i> Nouvel Achat
                </a>
                <a href="{{ route('produits.index') }}" class="btn-achat btn-achat-outline">
                    <i class="fas fa-box"></i> Produits
                </a>
            </div>
        </div>

        {{-- Divider --}}
        <div style="height:1px;background:#f0ebe6;"></div>

        {{-- Ligne 2 : Filtres avancés --}}
        <div style="display:flex;gap:8px;flex-wrap:wrap;align-items:center;">

            {{-- Fournisseur --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#bbb;white-space:nowrap;">
                    <i class="fas fa-truck"></i> Fourn.
                </span>
                <select name="fournisseur" onchange="this.form.submit()" style="
                    border:1.5px solid #e8e4df;border-radius:9px;
                    padding:6px 12px;font-size:.82rem;color:#333;
                    background:#fafaf8;outline:none;cursor:pointer;
                    transition:border-color .2s;
                " onfocus="this.style.borderColor='#D32F2F'" onblur="this.style.borderColor='#e8e4df'">
                    <option value="">— Tous —</option>
                    @foreach($fournisseursListe as $f)
                        <option value="{{ $f }}" {{ ($fournisseur ?? '') == $f ? 'selected' : '' }}>{{ $f }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:1px;height:24px;background:#e8e4df;flex-shrink:0;"></div>

            {{-- Catégorie --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#bbb;white-space:nowrap;">
                    <i class="fas fa-layer-group"></i> Catég.
                </span>
                <select name="category_id" onchange="this.form.submit()" style="
                    border:1.5px solid #e8e4df;border-radius:9px;
                    padding:6px 12px;font-size:.82rem;color:#333;
                    background:#fafaf8;outline:none;cursor:pointer;
                    transition:border-color .2s;
                " onfocus="this.style.borderColor='#D32F2F'" onblur="this.style.borderColor='#e8e4df'">
                    <option value="">— Toutes —</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ ($category_id ?? '') == $cat->id ? 'selected' : '' }}>{{ $cat->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:1px;height:24px;background:#e8e4df;flex-shrink:0;"></div>

            {{-- Produit --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#bbb;white-space:nowrap;">
                    <i class="fas fa-box"></i> Produit
                </span>
                <select name="produit_id" onchange="this.form.submit()" style="
                    border:1.5px solid #e8e4df;border-radius:9px;
                    padding:6px 12px;font-size:.82rem;color:#333;
                    background:#fafaf8;outline:none;cursor:pointer;
                    transition:border-color .2s;
                " onfocus="this.style.borderColor='#D32F2F'" onblur="this.style.borderColor='#e8e4df'">
                    <option value="">— Tous —</option>
                    @foreach($produitsListe as $p)
                        <option value="{{ $p->id }}" {{ ($produit_id ?? '') == $p->id ? 'selected' : '' }}>{{ $p->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div style="width:1px;height:24px;background:#e8e4df;flex-shrink:0;"></div>

            {{-- Mois --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#bbb;white-space:nowrap;">
                    <i class="fas fa-calendar-day"></i> Mois
                </span>
                <input type="month" name="mois"
                       value="{{ $mois ?? '' }}"
                       onchange="document.getElementById('f-annee').value=''; this.form.submit();"
                       style="
                           border:1.5px solid #e8e4df;border-radius:9px;
                           padding:6px 12px;font-size:.82rem;color:#333;
                           background:#fafaf8;outline:none;cursor:pointer;
                       "
                       onfocus="this.style.borderColor='#D32F2F'" onblur="this.style.borderColor='#e8e4df'">
            </div>

            <div style="width:1px;height:24px;background:#e8e4df;flex-shrink:0;"></div>

            {{-- Année --}}
            <div style="display:flex;align-items:center;gap:6px;">
                <span style="font-size:.65rem;font-weight:700;text-transform:uppercase;letter-spacing:1px;color:#bbb;white-space:nowrap;">
                    <i class="fas fa-calendar"></i> Année
                </span>
                <select name="annee" id="f-annee" onchange="this.form.submit()" style="
                    border:1.5px solid #e8e4df;border-radius:9px;
                    padding:6px 12px;font-size:.82rem;color:#333;
                    background:#fafaf8;outline:none;cursor:pointer;
                    transition:border-color .2s;
                " onfocus="this.style.borderColor='#D32F2F'" onblur="this.style.borderColor='#e8e4df'">
                    <option value="">— Toutes —</option>
                    @foreach($anneesDisponibles as $a)
                        <option value="{{ $a }}" {{ ($annee ?? '') == $a ? 'selected' : '' }}>{{ $a }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Badge période active --}}
            @if(($mois ?? '') || ($annee ?? ''))
            <span style="
                margin-left:auto;
                background:linear-gradient(135deg,#fff0f0,#fce8e8);
                color:#D32F2F;border:1px solid rgba(211,47,47,.2);
                font-size:.7rem;font-weight:700;
                padding:5px 13px;border-radius:50px;
                white-space:nowrap;display:flex;align-items:center;gap:5px;
            ">
                <i class="fas fa-clock"></i>
                {{ ($mois ?? '') ? \Carbon\Carbon::parse($mois.'-01')->locale('fr')->isoFormat('MMMM YYYY') : 'Année '.($annee ?? '') }}
            </span>
            @endif

        </div>
    </div>
</div>
</form>

        {{-- ── TABLE ── --}}
        <div class="achats-table-card">
            <div class="table-responsive">
                <table class="achats-table table">
                    <thead>
                        <tr>
                            <th>Produit</th>
                            <th>Fournisseur</th>
                            <th>N° Bon</th>
                            <th>Date</th>
                            <th>Quantité</th>
                            <th>Stock Restant</th>
                            <th>Utilisation</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($achats as $achat)
                    @php
                        $tauxUtil = $achat->quantite > 0
                            ? round((($achat->quantite - $achat->quantite_restante) / $achat->quantite) * 100)
                            : 0;
                        $barColor = $tauxUtil >= 100 ? '#f44336' : ($tauxUtil >= 70 ? '#ff9800' : '#4caf50');
                    @endphp
                    <tr>
                        <td>
                            <div class="product-table-info">
                                <div class="product-table-avatar">
                                    {{ strtoupper(substr($achat->produit->nom ?? '??', 0, 2)) }}
                                </div>
                                <div class="product-table-details">
                                    <h6>{{ $achat->produit->nom ?? 'N/A' }}</h6>
                                    <small>{{ $achat->produit->reference ?? '' }}</small>
                                </div>
                            </div>
                        </td>

                        <td>
                            @if($achat->fournisseur)
                                <span class="fournisseur-badge">
                                    <i class="fas fa-truck"></i> {{ $achat->fournisseur }}
                                </span>
                            @else
                                <span class="text-muted"><i class="fas fa-minus"></i></span>
                            @endif
                        </td>

                        <td>
                            @if($achat->numero_bon)
                                <span class="badge-achat badge-info-achat">
                                    <i class="fas fa-hashtag"></i> {{ $achat->numero_bon }}
                                </span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td>
                            <span class="date-badge">
                                <i class="fas fa-calendar"></i>
                                {{ \Carbon\Carbon::parse($achat->date_achat)->format('d/m/Y') }}
                            </span>
                        </td>

                        <td>
                            <span class="quantite-box">
                                <i class="fas fa-cube"></i> {{ number_format($achat->quantite) }}
                            </span>
                        </td>

                        <td>
                            @if($achat->quantite_restante > 0)
                                <span class="badge-achat badge-success-achat" style="font-size:.85rem;padding:6px 14px;">
                                    <i class="fas fa-boxes"></i> {{ number_format($achat->quantite_restante) }}
                                </span>
                                <div style="font-size:.7rem;color:#888;margin-top:3px;font-family:monospace;">
                                    {{ number_format($achat->quantite_restante * $achat->prix_achat, 2) }} MAD
                                </div>
                            @else
                                <span class="badge-achat badge-danger-achat">
                                    <i class="fas fa-times-circle"></i> Épuisé
                                </span>
                            @endif
                        </td>

                        <td>
                            <div class="progress-wrap">
                                <div class="progress-pct" style="color:{{ $barColor }}">{{ $tauxUtil }}%</div>
                                <div class="progress" style="height:6px;border-radius:99px;">
                                    <div class="progress-bar" role="progressbar"
                                         style="width:{{ $tauxUtil }}%;background:{{ $barColor }};border-radius:99px;">
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <span class="prix-display prix-unitaire">
                                {{ number_format($achat->prix_achat, 2) }} MAD
                            </span>
                        </td>

                        <td>
                            <span class="prix-display prix-total">
                                {{ number_format($achat->total_achat, 2) }} MAD
                            </span>
                        </td>

                        <td>
                            <div class="action-btns-achats">
                                <a href="{{ route('achats.edit', $achat->id) }}"
                                   class="btn-icon-achat btn-edit-achat" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <button onclick="confirmDeleteAchat({{ $achat->id }})"
                                        class="btn-icon-achat btn-delete-achat" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10">
                            <div class="empty-achats">
                                <i class="fas fa-shopping-cart"></i>
                                <h4>Aucun achat trouvé</h4>
                                <p>Modifiez les filtres ou créez un nouvel achat</p>
                                <a href="{{ route('achats.create') }}" class="btn-achat btn-achat-primary" style="margin-top:15px;">
                                    <i class="fas fa-plus"></i> Créer un Achat
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

           
            <div>
                    {{ $achats->links('pagination.custom') }}
                </div>
        </div>

    </div>

    <script>
        function confirmDeleteAchat(id) {
            Swal.fire({
                title: 'Supprimer cet achat ?',
                html: '<strong style="color:#d32f2f;">⚠️ Attention :</strong><br>Le stock sera automatiquement ajusté lors de la suppression.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C62828',
                cancelButtonColor: '#6c757d',
                confirmButtonText: '<i class="fas fa-trash"></i> Oui, supprimer',
                cancelButtonText:  '<i class="fas fa-times"></i> Annuler',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/achats/${id}`;
                    form.innerHTML = `@csrf @method('DELETE')`;
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }

        @if(session('success'))
        Swal.fire({
            icon: 'success', title: 'Succès !',
            text: "{{ session('success') }}",
            timer: 3000, showConfirmButton: false,
            toast: true, position: 'top-end'
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error', title: 'Erreur !',
            text: "{{ session('error') }}",
            timer: 4000, showConfirmButton: true
        });
        @endif
    </script>

</x-app-layout>