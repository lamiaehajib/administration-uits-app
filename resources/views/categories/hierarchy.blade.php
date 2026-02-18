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

/* ── PAGE HEADER ── */
.page-header {
    padding: 40px 0 0;
    margin-bottom: 36px;
}
.page-header h1 {
    font-size: clamp(1.8rem, 4vw, 2.6rem);
    font-weight: 800;
    letter-spacing: -1px;
    line-height: 1.1;
    margin: 0 0 6px;
}
.page-header h1 span {
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.page-header p { color: var(--muted); margin: 0; font-size: .95rem; }

/* ── STAT PILLS ── */
.stat-pills { display: flex; gap: 12px; flex-wrap: wrap; margin-bottom: 36px; }
.stat-pill {
    background: var(--card-bg);
    border: 1px solid var(--border);
    border-radius: 50px;
    padding: 8px 18px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: .85rem;
    font-weight: 600;
    box-shadow: var(--shadow-card);
}
.stat-pill .pill-dot {
    width: 8px; height: 8px; border-radius: 50%;
}
.pill-red .pill-dot { background: var(--accent); }
.pill-green .pill-dot { background: #4caf50; }
.pill-blue .pill-dot { background: #2196f3; }
.pill-orange .pill-dot { background: #ff9800; }

/* ── CATEGORY BLOCK ── */
.category-block {
    background: var(--card-bg);
    border-radius: var(--radius);
    border: 1px solid var(--border);
    box-shadow: var(--shadow-card);
    margin-bottom: 24px;
    overflow: hidden;
    transition: box-shadow .25s ease;
}
.category-block:hover { box-shadow: var(--shadow-hover); }

/* Parent header */
.cat-header {
    display: grid;
    grid-template-columns: 1fr auto;
    align-items: center;
    gap: 16px;
    padding: 22px 28px;
    cursor: pointer;
    border-bottom: 1px solid var(--border);
    background: linear-gradient(to right, #fff, var(--accent-soft));
    transition: background .2s;
}
.cat-header:hover { background: linear-gradient(to right, #fdf6f6, #fce8e8); }

.cat-header-left { display: flex; align-items: center; gap: 14px; }
.cat-icon {
    width: 44px; height: 44px;
    background: linear-gradient(135deg, var(--accent2), var(--accent));
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    color: white; font-size: 1.1rem;
    flex-shrink: 0;
}
.cat-name { font-size: 1.15rem; font-weight: 700; margin: 0; line-height: 1.2; }
.cat-meta { font-size: .78rem; color: var(--muted); margin-top: 2px; }

.cat-stats-row { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; }

/* Badge chips */
.chip {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 5px 12px;
    border-radius: 50px;
    font-size: .78rem;
    font-weight: 600;
    font-family: 'JetBrains Mono', monospace;
    white-space: nowrap;
}
.chip-stock  { background: var(--info-soft);    color: var(--info);    }
.chip-ventes { background: var(--success-soft);  color: var(--success); }
.chip-marge  { background: var(--accent-soft);   color: var(--accent);  }
.chip-rupture{ background: #fff3e0;              color: #bf360c;        }
.chip-alerte { background: #fff9c4;              color: #f57f17;        }

.toggle-btn {
    background: none; border: 1px solid var(--border); border-radius: 8px;
    width: 34px; height: 34px; display: flex; align-items: center; justify-content: center;
    cursor: pointer; color: var(--muted); transition: all .2s; flex-shrink: 0;
}
.toggle-btn:hover { background: var(--accent-soft); border-color: var(--accent); color: var(--accent); }
.toggle-btn .chevron { transition: transform .3s ease; }
.toggle-btn.open .chevron { transform: rotate(180deg); }

/* ── SUB-CAT SECTION ── */
.subcats-wrap { padding: 16px 28px 8px; }
.subcats-label {
    font-size: .72rem; font-weight: 700; text-transform: uppercase;
    letter-spacing: 1.5px; color: var(--muted); margin-bottom: 12px;
}

.subcat-row {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 10px 14px;
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: background .15s;
    border: 1px solid transparent;
}
.subcat-row:hover { background: var(--accent-soft); border-color: rgba(211,47,47,.12); }
.subcat-row.active { background: var(--accent-soft); border-color: rgba(211,47,47,.2); }

.subcat-icon {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, #fce4ec, #ffcdd2);
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    color: var(--accent); font-size: .85rem; flex-shrink: 0;
}
.subcat-name { font-weight: 600; font-size: .9rem; flex: 1; }
.subcat-chips { display: flex; gap: 6px; flex-wrap: wrap; }

/* ── PRODUCTS TABLE ── */
.products-panel {
    display: none;
    border-top: 1px solid var(--border);
    padding: 0;
}
.products-panel.visible { display: block; }

.products-panel-header {
    padding: 14px 28px 12px;
    background: #f7f7f5;
    display: flex; align-items: center; justify-content: space-between;
    border-bottom: 1px solid var(--border);
}
.products-panel-title { font-size: .8rem; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; color: var(--muted); }
.products-count-badge {
    background: var(--accent); color: white;
    font-size: .7rem; font-weight: 700;
    padding: 2px 10px; border-radius: 50px;
    font-family: 'JetBrains Mono', monospace;
}

.products-table { width: 100%; border-collapse: collapse; }
.products-table thead th {
    padding: 10px 16px;
    font-size: .72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: .8px;
    color: var(--muted); text-align: left;
    background: #f7f7f5;
    border-bottom: 1px solid var(--border);
}
.products-table tbody tr {
    border-bottom: 1px solid #f0ebe6;
    transition: background .15s;
}
.products-table tbody tr:last-child { border-bottom: none; }
.products-table tbody tr:hover { background: #fdfaf9; }
.products-table td {
    padding: 12px 16px;
    font-size: .875rem;
    vertical-align: middle;
}

.prod-name-cell { display: flex; align-items: center; gap: 10px; }
.prod-dot {
    width: 6px; height: 6px; border-radius: 50%;
    flex-shrink: 0;
}
.prod-dot.ok { background: #4caf50; }
.prod-dot.low { background: #ff9800; }
.prod-dot.out { background: #f44336; }

.prod-name { font-weight: 600; }
.prod-ref { font-size: .72rem; color: var(--muted); font-family: 'JetBrains Mono', monospace; }

.num-cell {
    font-family: 'JetBrains Mono', monospace;
    font-weight: 500;
    font-size: .85rem;
}
.num-positive { color: var(--success); }
.num-negative { color: var(--accent); }
.num-neutral  { color: var(--info); }

.stock-bar-wrap { display: flex; align-items: center; gap: 8px; min-width: 120px; }
.stock-bar {
    flex: 1; height: 5px;
    background: #f0ebe6;
    border-radius: 99px; overflow: hidden;
}
.stock-bar-fill {
    height: 100%; border-radius: 99px;
    transition: width .4s ease;
}
.bar-good  { background: #4caf50; }
.bar-low   { background: #ff9800; }
.bar-empty { background: #f44336; }

/* ── EMPTY STATE ── */
.empty-products {
    padding: 30px;
    text-align: center;
    color: var(--muted);
    font-size: .88rem;
}
.empty-products i { font-size: 2rem; margin-bottom: 8px; opacity: .3; display: block; }

/* ── PARENT PRODUCTS SECTION ── */
.parent-products-section { padding: 20px 28px; }

/* ── COLLAPSE ANIMATION ── */
.collapsible-content {
    max-height: 0;
    overflow: hidden;
    transition: max-height .4s cubic-bezier(0.4, 0, 0.2, 1);
}
.collapsible-content.expanded { max-height: 9999px; }

/* ── RESPONSIVE ── */
@media (max-width: 768px) {
    .cat-header { grid-template-columns: 1fr; gap: 12px; padding: 16px 18px; }
    .cat-stats-row { display: none; }
    .toggle-btn { position: absolute; top: 16px; right: 16px; }
    .cat-header { position: relative; }
    .products-table thead { display: none; }
    .products-table td { display: block; padding: 4px 16px; }
    .products-table td::before { content: attr(data-label)': '; font-weight: 700; font-size: .72rem; color: var(--muted); }
    .products-table tbody tr { display: block; padding: 10px 0; }
    .subcats-wrap { padding: 12px 18px 8px; }
    .parent-products-section { padding: 14px 18px; }
}
</style>

<div class="container-fluid px-4 px-md-5">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header">
        <h1><span>Catalogue</span> des Catégories</h1>
        <p>Vue hiérarchique · Stock en temps réel · Performance des ventes</p>
    </div>

    {{-- ── STAT PILLS ── --}}
    <div class="stat-pills">
        <div class="stat-pill pill-red">
            <div class="pill-dot"></div>
            {{ \App\Models\Category::whereNull('parent_id')->count() }} Catégories parentes
        </div>
        <div class="stat-pill pill-blue">
            <div class="pill-dot"></div>
            {{ \App\Models\Category::whereNotNull('parent_id')->count() }} Sous-catégories
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

    {{-- ── CATEGORIES LOOP ── --}}
    @php
        $categories = \App\Models\Category::with(['children.produits', 'produits'])
            ->whereNull('parent_id')
            ->orderBy('nom')
            ->get();
    @endphp

    @foreach($categories as $cat)
    @php
        // Tous les produits (parent + enfants)
        $allProds = $cat->allProduits();
        $totalStock = $allProds->sum('quantite_stock');
        $totalVendu = \App\Models\RecuItem::whereIn('produit_id', $allProds->pluck('id'))
            ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
            ->sum('quantite');
        $totalCA = \App\Models\RecuItem::whereIn('produit_id', $allProds->pluck('id'))
            ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
            ->sum('sous_total');
        $totalMarge = \App\Models\RecuItem::whereIn('produit_id', $allProds->pluck('id'))
            ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
            ->sum('marge_totale');
        $ruptures = $allProds->where('quantite_stock', 0)->count();
    @endphp

    <div class="category-block" id="cat-block-{{ $cat->id }}">

        {{-- ── PARENT HEADER ── --}}
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

            <div style="display:flex;align-items:center;gap:10px;flex-wrap:wrap;">
                <div class="cat-stats-row">
                    <span class="chip chip-stock">
                        <i class="fas fa-boxes"></i>
                        {{ number_format($totalStock) }} en stock
                    </span>
                    <span class="chip chip-ventes">
                        <i class="fas fa-arrow-up"></i>
                        {{ number_format($totalVendu) }} vendus
                    </span>
                    <span class="chip chip-marge">
                        <i class="fas fa-chart-line"></i>
                        {{ number_format($totalCA, 0, ',', ' ') }} MAD CA
                    </span>
                    @if($ruptures > 0)
                    <span class="chip chip-rupture">
                        <i class="fas fa-exclamation-triangle"></i>
                        {{ $ruptures }} rupture{{ $ruptures > 1 ? 's' : '' }}
                    </span>
                    @endif
                </div>
                <button class="toggle-btn" id="btn-{{ $cat->id }}">
                    <svg class="chevron" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </button>
            </div>
        </div>

        {{-- ── COLLAPSIBLE CONTENT ── --}}
        <div class="collapsible-content" id="content-{{ $cat->id }}">

            {{-- ── SOUS-CATÉGORIES ── --}}
            @if($cat->children->count() > 0)
            <div class="subcats-wrap">
                <div class="subcats-label"><i class="fas fa-sitemap me-1"></i> Sous-catégories</div>

                @foreach($cat->children as $sub)
                @php
                    $subStock   = $sub->produits->sum('quantite_stock');
                    $subVendu   = \App\Models\RecuItem::whereIn('produit_id', $sub->produits->pluck('id'))
                                    ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
                                    ->sum('quantite');
                    $subCA      = \App\Models\RecuItem::whereIn('produit_id', $sub->produits->pluck('id'))
                                    ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
                                    ->sum('sous_total');
                    $subMarge   = \App\Models\RecuItem::whereIn('produit_id', $sub->produits->pluck('id'))
                                    ->whereHas('recuUcg', fn($q) => $q->whereIn('statut', ['en_cours', 'livre']))
                                    ->sum('marge_totale');
                    $subRuptures = $sub->produits->where('quantite_stock', 0)->count();
                @endphp

                <div class="subcat-row" onclick="toggleSubcat({{ $sub->id }})" id="subcat-row-{{ $sub->id }}">
                    <div class="subcat-icon"><i class="fas fa-tag"></i></div>
                    <div class="subcat-name">{{ $sub->nom }}</div>
                    <div class="subcat-chips">
                        <span class="chip chip-stock">
                            <i class="fas fa-boxes"></i>{{ number_format($subStock) }} stock
                        </span>
                        <span class="chip chip-ventes">
                            <i class="fas fa-arrow-up"></i>{{ number_format($subVendu) }} vendus
                        </span>
                        <span class="chip chip-marge">
                            {{ number_format($subCA, 0, ',', ' ') }} MAD
                        </span>
                        @if($subRuptures > 0)
                        <span class="chip chip-rupture">{{ $subRuptures }} rupture{{ $subRuptures > 1 ? 's' : '' }}</span>
                        @endif
                    </div>
                    <svg style="color:var(--muted);flex-shrink:0;transition:transform .3s" id="subchev-{{ $sub->id }}" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 9l6 6 6-6"/></svg>
                </div>

                {{-- Products of this sub-category --}}
                <div id="subprod-{{ $sub->id }}" style="display:none; margin: 4px 0 12px 44px;">
                    @include('categories._products_table', [
                        'produits' => $sub->produits()->with('category')->orderBy('nom')->get(),
                        'label' => $sub->nom
                    ])
                </div>
                @endforeach
            </div>
            @endif

            {{-- ── PRODUITS DIRECTS DU PARENT ── --}}
            @if($cat->produits->count() > 0)
            <div class="parent-products-section">
                <div class="subcats-label" style="margin-bottom:12px;">
                    <i class="fas fa-box-open me-1"></i> Produits directs de « {{ $cat->nom }} »
                </div>
                @include('categories._products_table', [
                    'produits' => $cat->produits()->with('category')->orderBy('nom')->get(),
                    'label' => $cat->nom
                ])
            </div>
            @endif

        </div>{{-- end collapsible --}}

    </div>{{-- end category-block --}}
    @endforeach

</div>{{-- end container --}}

{{-- ── PARTIAL: _products_table ── --}}
{{-- Save as: resources/views/categories/_products_table.blade.php --}}

<script>
function toggleCat(id) {
    const content = document.getElementById('content-' + id);
    const btn     = document.getElementById('btn-' + id);
    content.classList.toggle('expanded');
    btn.classList.toggle('open');
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

// Auto-ouvre la première catégorie
document.addEventListener('DOMContentLoaded', () => {
    const firstContent = document.querySelector('.collapsible-content');
    const firstBtn     = document.querySelector('.toggle-btn');
    if (firstContent) firstContent.classList.add('expanded');
    if (firstBtn) firstBtn.classList.add('open');
});
</script>

</x-app-layout>