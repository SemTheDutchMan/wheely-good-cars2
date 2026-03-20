@section('body_class', 'dashboard-body')
<x-base-layout>
    <section class="live-dashboard">
        <div class="live-header">
            <div>
                <p class="section-kicker">Realtime aanbodmonitor</p>
                <h1>WheelyGoodCars live dashboard</h1>
            </div>
            <p class="muted-copy">Ververst elke 10 seconden en is geschikt voor fullscreen weergave.</p>
        </div>

        <div class="metric-grid">
            <article class="metric-card"><span>Totaal aangeboden</span><strong data-stat="offered">{{ $stats['offered'] }}</strong></article>
            <article class="metric-card"><span>Totaal verkocht</span><strong data-stat="sold">{{ $stats['sold'] }}</strong></article>
            <article class="metric-card"><span>Vandaag aangeboden</span><strong data-stat="today_offered">{{ $stats['today_offered'] }}</strong></article>
            <article class="metric-card"><span>Aanbieders</span><strong data-stat="sellers">{{ $stats['sellers'] }}</strong></article>
            <article class="metric-card"><span>Views vandaag</span><strong data-stat="today_views">{{ $stats['today_views'] }}</strong></article>
            <article class="metric-card"><span>Gem. auto's per aanbieder</span><strong data-stat="avg_per_seller">{{ $stats['avg_per_seller'] }}</strong></article>
        </div>

        <div class="dashboard-grid">
            <div class="chart-card">
                <h2>Voorraadverdeling</h2>
                <div class="progress-stack">
                    <div>
                        <div class="progress-label"><span>Budget</span><strong data-bucket="budget">{{ $stats['price_buckets']['budget'] }}</strong></div>
                        <div class="progress-shell"><div class="progress-bar progress-accent" data-bucket-bar="budget"></div></div>
                    </div>
                    <div>
                        <div class="progress-label"><span>Middenklasse</span><strong data-bucket="midrange">{{ $stats['price_buckets']['midrange'] }}</strong></div>
                        <div class="progress-shell"><div class="progress-bar progress-gold" data-bucket-bar="midrange"></div></div>
                    </div>
                    <div>
                        <div class="progress-label"><span>Premium</span><strong data-bucket="premium">{{ $stats['price_buckets']['premium'] }}</strong></div>
                        <div class="progress-shell"><div class="progress-bar progress-dark" data-bucket-bar="premium"></div></div>
                    </div>
                </div>
            </div>

            <div class="chart-card">
                <h2>Top 5 merken</h2>
                <div id="brand-bars" class="brand-bar-list">
                    @foreach ($stats['top_brands'] as $brand)
                        <div class="brand-bar-item" data-brand-item>
                            <span>{{ $brand->make }}</span>
                            <div class="brand-bar-track"><div class="brand-bar-fill" style="width: 0%"></div></div>
                            <strong>{{ $brand->total }}</strong>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script>
        (() => {
            const statsUrl = @json(route('admin.stats'));

            const render = (stats) => {
                Object.entries({
                    offered: stats.offered,
                    sold: stats.sold,
                    today_offered: stats.today_offered,
                    sellers: stats.sellers,
                    today_views: stats.today_views,
                    avg_per_seller: stats.avg_per_seller,
                }).forEach(([key, value]) => {
                    const node = document.querySelector(`[data-stat="${key}"]`);
                    if (node) {
                        node.textContent = value;
                    }
                });

                const totalBuckets = Object.values(stats.price_buckets).reduce((sum, value) => sum + value, 0) || 1;
                Object.entries(stats.price_buckets).forEach(([key, value]) => {
                    const label = document.querySelector(`[data-bucket="${key}"]`);
                    const bar = document.querySelector(`[data-bucket-bar="${key}"]`);
                    if (label) {
                        label.textContent = value;
                    }
                    if (bar) {
                        bar.style.width = `${Math.round((value / totalBuckets) * 100)}%`;
                    }
                });

                const brandContainer = document.getElementById('brand-bars');
                const topTotal = Math.max(...stats.top_brands.map((brand) => brand.total), 1);
                brandContainer.innerHTML = stats.top_brands.map((brand) => `
                    <div class="brand-bar-item">
                        <span>${brand.make}</span>
                        <div class="brand-bar-track"><div class="brand-bar-fill" style="width: ${Math.round((brand.total / topTotal) * 100)}%"></div></div>
                        <strong>${brand.total}</strong>
                    </div>
                `).join('');
            };

            render(@json($stats));

            setInterval(async () => {
                const response = await fetch(statsUrl, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    },
                });
                render(await response.json());
            }, 10000);
        })();
    </script>
</x-base-layout>
