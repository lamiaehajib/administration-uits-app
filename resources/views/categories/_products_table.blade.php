{{-- resources/views/categories/_products_table.blade.php --}}
{{-- Variables attendues: $produits (Collection), $applyDateFilter (Closure), $label (string) --}}
@php
    $produitIds  = $produits->pluck('id');
    $maxStock    = $produits->max('quantite_stock') ?: 1;

    // ✅ LOGIQUE IDENTIQUE à ProduitController::show()
    // Pour chaque reçu: proportion = item.sous_total / SUM(TOUS les items du reçu)
    // remise_produit = proportion × remise_reçu
    // CA_produit = sous_total - remise_produit
    //
    // Exemple: produit A = 200 DH, reçu avec remise 100 DH sur produit A seulement
    //   → reçu 1: A=200, remise=0   → CA = 200
    //   → reçu 2: A=200, remise=100 → CA = 100  (remise totale du reçu)
    //   → Total CA = 300 DH ✅

    // 1️⃣ Récupérer tous les reçus qui contiennent nos produits (avec TOUS leurs items)
    $recusAvecTousItems = \App\Models\RecuUcg::with(['items'])
        ->whereHas('items', function($q) use ($produitIds) {
            $q->whereIn('produit_id', $produitIds);
        })
        ->where(function($q) use ($applyDateFilter) {
            $applyDateFilter($q);
        })
        ->get();

    // 2️⃣ Calculer CA et marge par produit (loop PHP = identique à show())
    $ventesStatsRaw = [];

    foreach ($recusAvecTousItems as $recu) {
        // Total brut de TOUS les items du reçu (pour calculer la proportion)
        $totalBrutRecu = $recu->items->sum('sous_total');

        foreach ($recu->items as $item) {
            if (!in_array($item->produit_id, $produitIds->toArray())) continue;

            $pid = $item->produit_id;

            if (!isset($ventesStatsRaw[$pid])) {
                $ventesStatsRaw[$pid] = [
                    'total_vendu'   => 0,
                    'ca_total'      => 0,
                    'marge_totale'  => 0,
                ];
            }

            // Remise proportionnelle sur cet item
            $proportion    = $totalBrutRecu > 0 ? ($item->sous_total / $totalBrutRecu) : 0;
            $remiseProduit = $proportion * ($recu->remise ?? 0);

            $ventesStatsRaw[$pid]['total_vendu']  += $item->quantite;
            $ventesStatsRaw[$pid]['ca_total']     += $item->sous_total - $remiseProduit;
            $ventesStatsRaw[$pid]['marge_totale'] += $item->marge_totale;
        }
    }

    // 3️⃣ Convertir en collection keyBy produit_id
    $ventesStats = collect($ventesStatsRaw)->map(function($data, $pid) {
        return (object) array_merge($data, ['produit_id' => $pid]);
    });
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