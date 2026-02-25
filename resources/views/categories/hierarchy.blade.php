{{-- resources/views/categories/hierarchy.blade.php --}}
<x-app-layout>

<link href="https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

<style>
:root {
    --ink: #0f0f0f;
    --surface: #fafaf8;
    --card-bg: #ffffff;
    --accent: #D32F2F;
    --accent2: #C2185B;
    --accent-soft: #fff0f0;
    --success: #1b5e20;
    --success-soft: #e8f5e9;
    --warning: #e65100;
    --warning-soft: #fff3e0;
    --info: #0d47a1;
    --info-soft: #e3f2fd;
    --muted: #6b7280;
    --border: #e8e4df;
    --shadow-card: 0 1px 3px rgba(0,0,0,.06), 0 4px 16px rgba(0,0,0,.04);
    --shadow-hover: 0 8px 32px rgba(0,0,0,.10);
    --radius: 16px;
    --radius-sm: 10px;
}
* { box-sizing: border-box; }
body { background: var(--surface); font-family: 'Sora', sans-serif; color: var(--ink); }

.page-header { padding: 40px 0 0; margin-bottom: 28px; }
.page-header h1 {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 800; letter-spacing: -1px; line-height: 1.1; margin: 0 0 6px;
}
.page-header h1 span {
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
}
.page-header p { color: var(--muted); margin: 0; font-size: .95rem; }

/* ── FILTER BAR ── */
.filter-bar {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: var(--radius); padding: 14px 20px; margin-bottom: 22px;
    display: flex; align-items: center; gap: 12px; flex-wrap: wrap;
    box-shadow: var(--shadow-card);
}
.filter-bar label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1px; color: var(--muted); white-space: nowrap;
}
.filter-group { display: flex; align-items: center; gap: 7px; }
.filter-select, .filter-input {
    border: 1px solid var(--border); border-radius: 9px;
    padding: 7px 13px; font-size: .84rem; font-family: 'Sora', sans-serif;
    color: var(--ink); background: #fafaf8; outline: none;
    transition: border-color .2s, box-shadow .2s; cursor: pointer;
}
.filter-select:focus, .filter-input:focus {
    border-color: var(--accent); box-shadow: 0 0 0 3px rgba(211,47,47,.1);
}
.filter-divider { width: 1px; height: 26px; background: var(--border); margin: 0 2px; }
.btn-filter {
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    color: white; border: none; border-radius: 9px;
    padding: 7px 18px; font-size: .84rem; font-weight: 700;
    font-family: 'Sora', sans-serif; cursor: pointer;
    transition: opacity .2s, transform .15s;
    display: flex; align-items: center; gap: 6px;
}
.btn-filter:hover { opacity: .9; transform: translateY(-1px); }
.btn-reset {
    background: none; border: 1px solid var(--border); border-radius: 9px;
    padding: 7px 15px; font-size: .82rem; font-weight: 600;
    color: var(--muted); cursor: pointer; font-family: 'Sora', sans-serif;
    transition: all .2s; text-decoration: none; display: inline-flex; align-items: center; gap: 4px;
}
.btn-reset:hover { border-color: var(--accent); color: var(--accent); background: var(--accent-soft); }
.period-badge {
    margin-left: auto; background: var(--accent-soft); color: var(--accent);
    font-size: .73rem; font-weight: 700; padding: 5px 13px; border-radius: 50px;
    font-family: 'JetBrains Mono', monospace; white-space: nowrap;
}

/* ── STAT PILLS ── */
.stat-pills { display: flex; gap: 10px; flex-wrap: wrap; margin-bottom: 26px; }
.stat-pill {
    background: var(--card-bg); border: 1px solid var(--border);
    border-radius: 50px; padding: 7px 16px;
    display: flex; align-items: center; gap: 8px;
    font-size: .83rem; font-weight: 600; box-shadow: var(--shadow-card);
}
.stat-pill .pill-dot { width: 7px; height: 7px; border-radius: 50%; }
.pill-red .pill-dot    { background: var(--accent); }
.pill-green .pill-dot  { background: #4caf50; }
.pill-blue .pill-dot   { background: #2196f3; }
.pill-orange .pill-dot { background: #ff9800; }

/* ── CATEGORY BLOCK ── */
.category-block {
    background: var(--card-bg); border-radius: var(--radius);
    border: 1px solid var(--border); box-shadow: var(--shadow-card);
    margin-bottom: 18px; overflow: hidden; transition: box-shadow .25s ease;
}
.category-block:hover { box-shadow: var(--shadow-hover); }

.cat-header {
    display: grid; grid-template-columns: 1fr auto;
    align-items: center; gap: 16px; padding: 18px 22px;
    cursor: pointer; border-bottom: 1px solid var(--border);
    background: linear-gradient(to right, #fff, var(--accent-soft));
    transition: background .2s;
}
.cat-header:hover { background: linear-gradient(to right, #fdf6f6, #fce8e8); }
.cat-header-left { display: flex; align-items: center; gap: 13px; }
.cat-icon {
    width: 42px; height: 42px;
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    border-radius: 11px; display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1rem; flex-shrink: 0;
}
.cat-name { font-size: 1.08rem; font-weight: 700; margin: 0; }
.cat-meta { font-size: .73rem; color: var(--muted); margin-top: 2px; }
.cat-stats-row { display: flex; gap: 7px; flex-wrap: wrap; align-items: center; }

.chip {
    display: inline-flex; align-items: center; gap: 4px;
    padding: 4px 10px; border-radius: 50px;
    font-size: .73rem; font-weight: 600;
    font-family: 'JetBrains Mono', monospace; white-space: nowrap;
}
.chip-stock   { background: var(--info-soft);   color: var(--info);    }
.chip-ventes  { background: var(--success-soft); color: var(--success); }
.chip-marge   { background: var(--accent-soft);  color: var(--accent);  }
.chip-rupture { background: #fff3e0;             color: #bf360c;        }

.toggle-btn {
    background: none; border: 1px solid var(--border); border-radius: 7px;
    width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted); transition: all .2s; flex-shrink: 0;
}
.toggle-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }
.toggle-btn svg { transition: transform .3s ease; }
.toggle-btn.open svg { transform: rotate(180deg); }

.subcats-wrap { padding: 13px 22px 6px; }
.subcats-label {
    font-size: .68rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.5px; color: var(--muted); margin-bottom: 9px;
}
.subcat-row {
    display: flex; align-items: center; gap: 10px;
    padding: 8px 11px; border-radius: var(--radius-sm);
    cursor: pointer; transition: background .15s; border: 1px solid transparent;
}
.subcat-row:hover { background: var(--accent-soft); border-color: rgba(211,47,47,.12); }
.subcat-row.active { background: var(--accent-soft); border-color: rgba(211,47,47,.2); }
.subcat-icon {
    width: 28px; height: 28px;
    background: linear-gradient(135deg, #fce4ec, #ffcdd2);
    border-radius: 7px; display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: .78rem; flex-shrink: 0;
}
.subcat-name { font-weight: 600; font-size: .86rem; flex: 1; }
.subcat-chips { display: flex; gap: 5px; flex-wrap: wrap; }

.products-table { width: 100%; border-collapse: collapse; }
.products-table thead th {
    padding: 8px 13px; font-size: .68rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .8px;
    color: var(--muted); text-align: left;
    background: #f7f7f5; border-bottom: 1px solid var(--border);
}
.products-table tbody tr { border-bottom: 1px solid #f0ebe6; transition: background .15s; }
.products-table tbody tr:last-child { border-bottom: none; }
.products-table tbody tr:hover { background: #fdfaf9; }
.products-table td { padding: 10px 13px; font-size: .855rem; vertical-align: middle; }
.prod-name-cell { display: flex; align-items: center; gap: 8px; }
.prod-dot { width: 6px; height: 6px; border-radius: 50%; flex-shrink: 0; }
.prod-dot.ok  { background: #4caf50; }
.prod-dot.low { background: #ff9800; }
.prod-dot.out { background: #f44336; }
.prod-name { font-weight: 600; }
.prod-ref { font-size: .68rem; color: var(--muted); font-family: 'JetBrains Mono', monospace; }
.num-cell { font-family: 'JetBrains Mono', monospace; font-weight: 500; font-size: .82rem; }
.num-positive { color: var(--success); }
.num-negative { color: var(--accent); }
.num-neutral  { color: var(--info); }
.stock-bar-wrap { display: flex; align-items: center; gap: 7px; min-width: 100px; }
.stock-bar { flex: 1; height: 4px; background: #f0ebe6; border-radius: 99px; overflow: hidden; }
.stock-bar-fill { height: 100%; border-radius: 99px; transition: width .4s ease; }
.bar-good  { background: #4caf50; }
.bar-low   { background: #ff9800; }
.bar-empty { background: #f44336; }
.empty-products { padding: 26px; text-align: center; color: var(--muted); font-size: .86rem; }
.empty-products i { font-size: 1.7rem; margin-bottom: 7px; opacity: .3; display: block; }
.parent-products-section { padding: 16px 22px; }

.collapsible-content { max-height: 0; overflow: hidden; transition: max-height .4s cubic-bezier(0.4,0,0.2,1); }
.collapsible-content.expanded { max-height: 99999px; }

@media (max-width: 768px) {
    .cat-header { grid-template-columns: 1fr; }
    .cat-stats-row { display: none; }
    .filter-bar { gap: 8px; }
    .period-badge { margin-left: 0; }
    .products-table thead { display: none; }
    .products-table td { display: block; padding: 3px 13px; }
    .products-table td::before { content: attr(data-label)': '; font-weight: 700; font-size: .68rem; color: var(--muted); }
    .products-table tbody tr { display: block; padding: 7px 0; }
}
</style>

@php
    $filterMois  = request('mois');
    $filterAnnee = request('annee');

    if ($filterMois) {
        $dateDebut   = \Carbon\Carbon::parse($filterMois . '-01')->startOfMonth();
        $dateFin     = \Carbon\Carbon::parse($filterMois . '-01')->endOfMonth();
        $periodLabel = $dateDebut->locale('fr')->isoFormat('MMMM YYYY');
    } elseif ($filterAnnee) {
        $dateDebut   = \Carbon\Carbon::create($filterAnnee)->startOfYear();
        $dateFin     = \Carbon\Carbon::create($filterAnnee)->endOfYear();
        $periodLabel = 'Année ' . $filterAnnee;
    } else {
        $dateDebut   = null;
        $dateFin     = null;
        $periodLabel = 'Toutes périodes';
    }

    // Closure filtre date réutilisable partout
    $applyDateFilter = function($q) use ($dateDebut, $dateFin) {
        $q->whereIn('statut', ['en_cours', 'livre']);
        if ($dateDebut && $dateFin) {
            $q->whereBetween('created_at', [$dateDebut, $dateFin]);
        }
    };

    $anneesDisponibles = \App\Models\RecuUcg::selectRaw('YEAR(created_at) as annee')
        ->distinct()->orderByDesc('annee')->pluck('annee');
@endphp

<div class="container-fluid px-4 px-md-5">

    <div class="page-header">
        <h1><span>Catalogue</span> des Catégories</h1>
        <p>Vue hiérarchique · Stock en temps réel · Performance des ventes</p>
    </div>

    {{-- ── FILTER BAR ── --}}
    <form method="GET" action="{{ route('categories.hierarchy') }}" class="filter-bar">

        <div class="filter-group">
            <label for="f-mois"><i class="fas fa-calendar-day me-1"></i>Mois</label>
            <input type="month" id="f-mois" name="mois" class="filter-input"
                   value="{{ $filterMois }}"
                   onchange="document.getElementById('f-annee').value=''; this.form.submit();">
        </div>

        <div class="filter-divider"></div>

        <div class="filter-group">
            <label for="f-annee"><i class="fas fa-calendar me-1"></i>Année</label>
            <select id="f-annee" name="annee" class="filter-select"
                    onchange="document.getElementById('f-mois').value=''; this.form.submit();">
                <option value="">— Toutes —</option>
                @foreach($anneesDisponibles as $annee)
                    <option value="{{ $annee }}" {{ $filterAnnee == $annee ? 'selected' : '' }}>
                        {{ $annee }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-divider"></div>

        <button type="submit" class="btn-filter">
            <i class="fas fa-filter"></i> Filtrer
        </button>

        @if($filterMois || $filterAnnee)
        <a href="{{ route('categories.hierarchy') }}" class="btn-reset">
            <i class="fas fa-times"></i> Réinitialiser
        </a>
        @endif

        <span class="period-badge">
            <i class="fas fa-clock me-1"></i>{{ $periodLabel }}
        </span>
    </form>

    {{-- ── STAT PILLS ── --}}
    <div class="stat-pills">
        <div class="stat-pill pill-red">
            <div class="pill-dot"></div>
            {{ \App\Models\Category::whereNull('parent_id')->count() }} catégories parentes
        </div>
        <div class="stat-pill pill-blue">
            <div class="pill-dot"></div>
            {{ \App\Models\Category::whereNotNull('parent_id')->count() }} sous-catégories
        </div>
        <div class="stat-pill pill-green">
            <div class="pill-dot"></div>
            {{ \App\Models\Produit::where('actif', true)->sum('quantite_stock') }} unités en stock
        </div>
        <div class="stat-pill pill-orange">
            <div class="pill-dot"></div>
            {{ \App\Models\Produit::whereColumn('quantite_stock', '<=', 'stock_alerte')->count() }} alertes stock
        </div>
    </div>

    {{-- ── CATÉGORIES ── --}}
    @php
        $categories = \App\Models\Category::with(['children.produits', 'produits'])
            ->whereNull('parent_id')->orderBy('nom')->get();
    @endphp

    @foreach($categories as $cat)
    @php
        $allProds   = $cat->allProduits();
        $prodIds    = $allProds->pluck('id');
        $totalStock = $allProds->sum('quantite_stock');
        $totalVendu = App\Models\RecuItem::whereIn('produit_id', $prodIds)->whereHas('recuUcg', $applyDateFilter)->sum('quantite');
        $totalCA    = App\Models\RecuItem::whereIn('produit_id', $prodIds)->whereHas('recuUcg', $applyDateFilter)->sum('total_apres_remise');
        $totalMarge = App\Models\RecuItem::whereIn('produit_id', $prodIds)->whereHas('recuUcg', $applyDateFilter)->sum('marge_totale');
        $ruptures   = $allProds->where('quantite_stock', 0)->count();
    @endphp

    <div class="category-block">

        <div class="cat-header" onclick="toggleCat({{ $cat->id }})">
            <div class="cat-header-left">
                <div class="cat-icon"><i class="fas fa-layer-group"></i></div>
                <div>
                    <div class="cat-name">{{ $cat->nom }}</div>
                    <div class="cat-meta">
                        {{ $allProds->count() }} produit{{ $allProds->count() != 1 ? 's' : '' }}
                        · {{ $cat->children->count() }} sous-cat{{ $cat->children->count() != 1 ? 's' : '' }}
                    </div>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:7px;flex-wrap:wrap;">
                <div class="cat-stats-row">
                    <span class="chip chip-stock"><i class="fas fa-boxes"></i>{{ number_format($totalStock) }} stock</span>
                    <span class="chip chip-ventes"><i class="fas fa-arrow-up"></i>{{ number_format($totalVendu) }} vendus</span>
                    <span class="chip chip-marge"><i class="fas fa-chart-line"></i>{{ number_format($totalCA, 0, ',', ' ') }} MAD</span>
                    @if($ruptures > 0)
                    <span class="chip chip-rupture"><i class="fas fa-exclamation-triangle"></i>{{ $ruptures }} rupture{{ $ruptures > 1 ? 's' : '' }}</span>
                    @endif
                </div>
                <button type="button" class="toggle-btn" id="btn-{{ $cat->id }}"
                        onclick="event.stopPropagation(); toggleCat({{ $cat->id }})">
                    <svg width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </button>
            </div>
        </div>

        <div class="collapsible-content" id="content-{{ $cat->id }}">

            @if($cat->children->count() > 0)
            <div class="subcats-wrap">
                <div class="subcats-label"><i class="fas fa-sitemap me-1"></i>Sous-catégories</div>

                @foreach($cat->children as $sub)
                @php
                    $subIds   = $sub->produits->pluck('id');
                    $subStock = $sub->produits->sum('quantite_stock');
                    $subVendu = App\Models\RecuItem::whereIn('produit_id', $subIds)->whereHas('recuUcg', $applyDateFilter)->sum('quantite');
                    $subCA    = App\Models\RecuItem::whereIn('produit_id', $subIds)->whereHas('recuUcg', $applyDateFilter)->sum('total_apres_remise');
                    $subMarge = App\Models\RecuItem::whereIn('produit_id', $subIds)->whereHas('recuUcg', $applyDateFilter)->sum('marge_totale');
                    $subRupt  = $sub->produits->where('quantite_stock', 0)->count();
                @endphp

                <div class="subcat-row" onclick="toggleSubcat({{ $sub->id }})" id="subcat-row-{{ $sub->id }}">
                    <div class="subcat-icon"><i class="fas fa-tag"></i></div>
                    <div class="subcat-name">{{ $sub->nom }}</div>
                    <div class="subcat-chips">
                        <span class="chip chip-stock"><i class="fas fa-boxes"></i>{{ number_format($subStock) }}</span>
                        <span class="chip chip-ventes"><i class="fas fa-arrow-up"></i>{{ number_format($subVendu) }}</span>
                        <span class="chip chip-marge">{{ number_format($subCA, 0, ',', ' ') }} MAD</span>
                        @if($subRupt > 0)<span class="chip chip-rupture">{{ $subRupt }} rupture{{ $subRupt > 1 ? 's' : '' }}</span>@endif
                    </div>
                    <svg style="color:var(--muted);flex-shrink:0;transition:transform .3s" id="subchev-{{ $sub->id }}"
                         width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path d="M6 9l6 6 6-6"/>
                    </svg>
                </div>

                <div id="subprod-{{ $sub->id }}" style="display:none;margin:3px 0 10px 40px;">
                    @include('categories._products_table', [
                        'produits'        => $sub->produits()->orderBy('nom')->get(),
                        'applyDateFilter' => $applyDateFilter,
                        'label'           => $sub->nom
                    ])
                </div>
                @endforeach
            </div>
            @endif

            @if($cat->produits->count() > 0)
            <div class="parent-products-section">
                <div class="subcats-label" style="margin-bottom:9px;">
                    <i class="fas fa-box-open me-1"></i>Produits directs de « {{ $cat->nom }} »
                </div>
                @include('categories._products_table', [
                    'produits'        => $cat->produits()->orderBy('nom')->get(),
                    'applyDateFilter' => $applyDateFilter,
                    'label'           => $cat->nom
                ])
            </div>
            @endif

        </div>
    </div>
    @endforeach

</div>

<script>
function toggleCat(id) {
    document.getElementById('content-' + id).classList.toggle('expanded');
    document.getElementById('btn-' + id).classList.toggle('open');
}
function toggleSubcat(id) {
    const panel = document.getElementById('subprod-' + id);
    const chev  = document.getElementById('subchev-' + id);
    const row   = document.getElementById('subcat-row-' + id);
    const isOpen = panel.style.display === 'block';
    panel.style.display = isOpen ? 'none' : 'block';
    chev.style.transform = isOpen ? '' : 'rotate(180deg)';
    row.classList.toggle('active', !isOpen);
}
document.addEventListener('DOMContentLoaded', () => {
    const c = document.querySelector('.collapsible-content');
    const b = document.querySelector('.toggle-btn');
    if (c) c.classList.add('expanded');
    if (b) b.classList.add('open');
});
</script>

</x-app-layout>