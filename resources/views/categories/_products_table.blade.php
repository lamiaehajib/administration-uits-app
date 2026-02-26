{{-- resources/views/categories/_products_table.blade.php --}}
{{-- Variables attendues: $produits (Collection), $applyDateFilter (Closure), $label (string) --}}
@php
    $produitIds  = $produits->pluck('id');
    $maxStock    = $produits->max('quantite_stock') ?: 1;

    // ✅ Même logique exacte que ProduitController::show()
    // proportion = item.sous_total / SUM(tous les sous_totals du même reçu)
    // Cela garantit que la remise est répartie proportionnellement sur tous les produits du reçu
    $ventesStats = \App\Models\RecuItem::whereIn('recu_items.produit_id', $produitIds)
        ->whereHas('recuUcg', $applyDateFilter)
        ->join('recus_ucgs', 'recu_items.recu_ucg_id', '=', 'recus_ucgs.id')
        ->join(
            \Illuminate\Support\Facades\DB::raw('(
                SELECT recu_ucg_id, SUM(sous_total) as total_brut_recu
                FROM recu_items
                GROUP BY recu_ucg_id
            ) as totaux_par_recu'),
            'totaux_par_recu.recu_ucg_id', '=', 'recu_items.recu_ucg_id'
        )
        ->whereIn('recus_ucgs.statut', ['en_cours', 'livre'])
        ->whereNull('recus_ucgs.deleted_at')
        ->select(
            'recu_items.produit_id',
            \Illuminate\Support\Facades\DB::raw('SUM(recu_items.quantite) as total_vendu'),
            // ✅ CA = sous_total - remise proportionnelle
            // proportion basée sur total réel de TOUS les items du reçu
            \Illuminate\Support\Facades\DB::raw('
                SUM(
                    recu_items.sous_total -
                    CASE
                        WHEN totaux_par_recu.total_brut_recu > 0
                        THEN (recu_items.sous_total / totaux_par_recu.total_brut_recu) * COALESCE(recus_ucgs.remise, 0)
                        ELSE 0
                    END
                ) as ca_total
            '),
            \Illuminate\Support\Facades\DB::raw('SUM(recu_items.marge_totale) as marge_totale')
        )
        ->groupBy('recu_items.produit_id')
        ->get()
        ->keyBy('produit_id');
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
            <th style="width:34%">Produit</th>
            <th>Stock actuel</th>
            <th>Qté vendue</th>
            <th>CA (MAD)</th>
            <th>Marge (MAD)</th>
            <th>Taux marge</th>
            <th>Statut</th>
        </tr>
    </thead>
    <tbody>
    @foreach($produits as $produit)
    @php
        $s         = $ventesStats[$produit->id] ?? null;
        $vendu     = $s ? (int)$s->total_vendu   : 0;
        $ca        = $s ? (float)$s->ca_total     : 0;
        $marge     = $s ? (float)$s->marge_totale : 0;
        $tauxMarge = $ca > 0 ? ($marge / $ca * 100) : 0;
        $stockPct  = min(100, ($produit->quantite_stock / $maxStock) * 100);

        if ($produit->quantite_stock == 0) {
            $dotClass    = 'out';
            $barClass    = 'bar-empty';
            $statusLabel = 'Rupture';
            $statusStyle = 'color:#c62828;background:#ffebee;';
        } elseif ($produit->stock_alerte && $produit->quantite_stock <= $produit->stock_alerte) {
            $dotClass    = 'low';
            $barClass    = 'bar-low';
            $statusLabel = 'Alerte';
            $statusStyle = 'color:#e65100;background:#fff3e0;';
        } else {
            $dotClass    = 'ok';
            $barClass    = 'bar-good';
            $statusLabel = 'OK';
            $statusStyle = 'color:#1b5e20;background:#e8f5e9;';
        }
    @endphp
    <tr>
        {{-- Produit --}}
        <td data-label="Produit">
            <div class="prod-name-cell">
                <div class="prod-dot {{ $dotClass }}"></div>
                <div>
                    <div class="prod-name">{{ $produit->nom }}</div>
                    @if($produit->reference)
                    <div class="prod-ref">{{ $produit->reference }}</div>
                    @endif
                </div>
            </div>
        </td>

        {{-- Stock avec mini barre --}}
        <td data-label="Stock actuel">
            <div class="stock-bar-wrap">
                <span class="num-cell num-neutral">{{ number_format($produit->quantite_stock) }}</span>
                <div class="stock-bar">
                    <div class="stock-bar-fill {{ $barClass }}" style="width:{{ $stockPct }}%"></div>
                </div>
            </div>
        </td>

        {{-- Qté vendue --}}
        <td data-label="Qté vendue" class="num-cell">
            {{ $vendu > 0 ? number_format($vendu) : '—' }}
        </td>

        {{-- CA --}}
        <td data-label="CA" class="num-cell num-neutral">
            {{ $ca > 0 ? number_format($ca, 2, ',', ' ') : '—' }}
        </td>

        {{-- Marge --}}
        <td data-label="Marge" class="num-cell {{ $marge > 0 ? 'num-positive' : ($marge < 0 ? 'num-negative' : '') }}">
            {{ $marge != 0 ? number_format($marge, 2, ',', ' ') : '—' }}
        </td>

        {{-- Taux marge --}}
        <td data-label="Taux marge" class="num-cell {{ $tauxMarge >= 20 ? 'num-positive' : '' }}">
            {{ $tauxMarge > 0 ? number_format($tauxMarge, 1) . ' %' : '—' }}
        </td>

        {{-- Statut stock --}}
        <td data-label="Statut">
            <span style="font-size:.68rem;font-weight:700;padding:3px 9px;border-radius:50px;{{ $statusStyle }}">
                {{ $statusLabel }}
            </span>
        </td>
    </tr>
    @endforeach
    </tbody>

    {{-- Ligne totaux --}}
    @php
        $totStock = $produits->sum('quantite_stock');
        $totVendu = $ventesStats->sum('total_vendu');
        $totCA    = $ventesStats->sum('ca_total');
        $totMarge = $ventesStats->sum('marge_totale');
        $totTaux  = $totCA > 0 ? ($totMarge / $totCA * 100) : 0;
    @endphp
    <tfoot>
        <tr style="background:#f5f3f0;border-top:2px solid var(--border);">
            <td style="font-size:.7rem;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);padding:9px 13px;">
                Totaux — {{ $produits->count() }} produit{{ $produits->count() != 1 ? 's' : '' }}
            </td>
            <td class="num-cell" style="font-weight:700;">{{ number_format($totStock) }}</td>
            <td class="num-cell" style="font-weight:700;">{{ $totVendu > 0 ? number_format($totVendu) : '—' }}</td>
            <td class="num-cell num-neutral" style="font-weight:700;">{{ $totCA > 0 ? number_format($totCA, 2, ',', ' ') : '—' }}</td>
            <td class="num-cell num-positive" style="font-weight:700;">{{ $totMarge > 0 ? number_format($totMarge, 2, ',', ' ') : '—' }}</td>
            <td class="num-cell" style="font-weight:700;">{{ $totTaux > 0 ? number_format($totTaux, 1) . ' %' : '—' }}</td>
            <td></td>
        </tr>
    </tfoot>
</table>
</div>
@endif