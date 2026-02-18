{{-- resources/views/categories/_products_table.blade.php --}}
@php
    // Charger les stats de ventes pour chaque produit
    $produitIds = $produits->pluck('id');
    $ventesStats = \App\Models\RecuItem::whereIn('produit_id', $produitIds)
        ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
        ->select('produit_id',
            \Illuminate\Support\Facades\DB::raw('SUM(quantite) as total_vendu'),
            \Illuminate\Support\Facades\DB::raw('SUM(sous_total) as ca_total'),
            \Illuminate\Support\Facades\DB::raw('SUM(marge_totale) as marge_totale')
        )
        ->groupBy('produit_id')
        ->get()
        ->keyBy('produit_id');

    // Calculer max stock pour les barres de progression
    $maxStock = $produits->max('quantite_stock') ?: 1;
@endphp

@if($produits->isEmpty())
<div class="empty-products">
    <i class="fas fa-box-open"></i>
    Aucun produit dans cette catégorie
</div>
@else
<div style="overflow-x:auto;">
<table class="products-table">
    <thead>
        <tr>
            <th style="width:35%">Produit</th>
            <th>Stock</th>
            <th>Vendus</th>
            <th>CA (MAD)</th>
            <th>Marge (MAD)</th>
            <th>Taux marge</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
    @foreach($produits as $produit)
    @php
        $stats   = $ventesStats[$produit->id] ?? null;
        $vendu   = $stats ? $stats->total_vendu : 0;
        $ca      = $stats ? $stats->ca_total : 0;
        $marge   = $stats ? $stats->marge_totale : 0;
        $tauxMarge = $ca > 0 ? ($marge / $ca * 100) : 0;

        $stockPct = $maxStock > 0 ? min(100, ($produit->quantite_stock / $maxStock) * 100) : 0;
        
        // Statut stock
        if ($produit->quantite_stock == 0) {
            $dotClass  = 'out';
            $barClass  = 'bar-empty';
            $statusLabel = 'Rupture';
            $statusColor = 'color:#c62828;background:#ffebee;';
        } elseif ($produit->quantite_stock <= $produit->stock_alerte) {
            $dotClass  = 'low';
            $barClass  = 'bar-low';
            $statusLabel = 'Alerte';
            $statusColor = 'color:#e65100;background:#fff3e0;';
        } else {
            $dotClass  = 'ok';
            $barClass  = 'bar-good';
            $statusLabel = 'OK';
            $statusColor = 'color:#1b5e20;background:#e8f5e9;';
        }
    @endphp
    <tr>
        {{-- Nom --}}
        <td data-label="Produit">
            <div class="prod-name-cell">
                <div class="prod-dot {{ $dotClass }}"></div>
                <div>
                    <div class="prod-name">{{ $produit->nom }}</div>
                    <div class="prod-ref">{{ $produit->reference ?? '—' }}</div>
                </div>
            </div>
        </td>

        {{-- Stock avec barre --}}
        <td data-label="Stock">
            <div class="stock-bar-wrap">
                <span class="num-cell num-neutral">{{ number_format($produit->quantite_stock) }}</span>
                <div class="stock-bar">
                    <div class="stock-bar-fill {{ $barClass }}" style="width:{{ $stockPct }}%"></div>
                </div>
            </div>
        </td>

        {{-- Vendus --}}
        <td data-label="Vendus" class="num-cell">
            {{ $vendu > 0 ? number_format($vendu) : '—' }}
        </td>

        {{-- CA --}}
        <td data-label="CA" class="num-cell num-neutral">
            {{ $ca > 0 ? number_format($ca, 2, ',', ' ') : '—' }}
        </td>

        {{-- Marge --}}
        <td data-label="Marge" class="num-cell {{ $marge >= 0 ? 'num-positive' : 'num-negative' }}">
            {{ $marge != 0 ? number_format($marge, 2, ',', ' ') : '—' }}
        </td>

        {{-- Taux marge --}}
        <td data-label="Taux marge" class="num-cell {{ $tauxMarge >= 20 ? 'num-positive' : ($tauxMarge > 0 ? '' : '') }}">
            {{ $tauxMarge > 0 ? number_format($tauxMarge, 1) . ' %' : '—' }}
        </td>

        {{-- Statut --}}
        <td data-label="Statut">
            <span style="font-size:.72rem;font-weight:700;padding:3px 10px;border-radius:50px;{{ $statusColor }}">
                {{ $statusLabel }}
            </span>
        </td>
    </tr>
    @endforeach
    </tbody>

    {{-- TOTAUX --}}
    @php
        $totalStockCat = $produits->sum('quantite_stock');
        $totalVenduCat = $ventesStats->sum('total_vendu');
        $totalCACat    = $ventesStats->sum('ca_total');
        $totalMargeCat = $ventesStats->sum('marge_totale');
        $tauxMargeCat  = $totalCACat > 0 ? ($totalMargeCat / $totalCACat * 100) : 0;
    @endphp
    <tfoot>
        <tr style="background:#f7f3f0;border-top:2px solid var(--border);">
            <td style="font-size:.78rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:10px 16px;">
                Totaux ({{ $produits->count() }} produits)
            </td>
            <td class="num-cell" style="font-weight:700;">{{ number_format($totalStockCat) }}</td>
            <td class="num-cell" style="font-weight:700;">{{ $totalVenduCat > 0 ? number_format($totalVenduCat) : '—' }}</td>
            <td class="num-cell num-neutral" style="font-weight:700;">{{ $totalCACat > 0 ? number_format($totalCACat, 2, ',', ' ') : '—' }}</td>
            <td class="num-cell num-positive" style="font-weight:700;">{{ $totalMargeCat > 0 ? number_format($totalMargeCat, 2, ',', ' ') : '—' }}</td>
            <td class="num-cell" style="font-weight:700;">{{ $tauxMargeCat > 0 ? number_format($tauxMargeCat, 1) . ' %' : '—' }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
</div>
@endif