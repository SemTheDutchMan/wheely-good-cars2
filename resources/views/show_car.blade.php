<x-base-layout>
    <section class="detail-layout">
        <div class="detail-hero">
            <div class="detail-copy">
                <p class="section-kicker">{{ $car->license_plate }}</p>
                <h1>{{ $car->make }} {{ $car->model }}</h1>
                <p class="hero-copy">Vraagprijs: EUR {{ number_format($car->price, 0, ',', '.') }}</p>
            </div>
            <div class="detail-panel">
                <div class="detail-stat">
                    <span>Views totaal</span>
                    <strong>{{ $car->views }}</strong>
                </div>
                <div class="detail-stat">
                    <span>Views vandaag</span>
                    <strong id="views-today">{{ $todayViews }}</strong>
                </div>
            </div>
        </div>

        <div class="detail-grid">
            <div class="info-card detail-visual">
                <img src="{{ $car->display_image_url }}" alt="{{ $car->make }} {{ $car->model }}" loading="lazy">
            </div>

            <div class="info-card">
                <h2>Specificaties</h2>
                <dl class="spec-grid">
                    <div><dt>Merk</dt><dd>{{ $car->make }}</dd></div>
                    <div><dt>Model</dt><dd>{{ $car->model }}</dd></div>
                    <div><dt>Bouwjaar</dt><dd>{{ $car->production_year ?: '-' }}</dd></div>
                    <div><dt>Kilometerstand</dt><dd>{{ number_format($car->mileage, 0, ',', '.') }} km</dd></div>
                    <div><dt>Kleur</dt><dd>{{ $car->color ?: '-' }}</dd></div>
                    <div><dt>Gewicht</dt><dd>{{ $car->weight ? number_format($car->weight, 0, ',', '.') . ' kg' : '-' }}</dd></div>
                    <div><dt>Deuren</dt><dd>{{ $car->doors ?: '-' }}</dd></div>
                    <div><dt>Zitplaatsen</dt><dd>{{ $car->seats ?: '-' }}</dd></div>
                </dl>
            </div>

            <div class="info-card">
                <h2>Tags</h2>
                <div class="tag-list">
                    @forelse ($car->tags as $tag)
                        <span class="tag-badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                    @empty
                        <span class="muted-copy">Geen tags gekoppeld.</span>
                    @endforelse
                </div>

                <h2 class="mt-space">Aanbieder</h2>
                <dl class="spec-grid">
                    <div><dt>Naam</dt><dd>{{ $car->user->name }}</dd></div>
                    <div><dt>E-mail</dt><dd>{{ $car->user->email }}</dd></div>
                    <div><dt>Telefoon</dt><dd>{{ $car->user->phone_number ?: 'Niet ingevuld' }}</dd></div>
                </dl>
            </div>
        </div>
    </section>

    <div id="view-toast" class="toast-card" hidden>
        <strong id="toast-title">{{ $todayViews }} klanten bekeken deze auto vandaag</strong>
        <p>Deze auto krijgt vandaag opvallend veel aandacht.</p>
    </div>

    <script>
        setTimeout(async () => {
            const response = await fetch(@json(route('car.views.today', $car)));
            const data = await response.json();
            const toast = document.getElementById('view-toast');
            const count = data.views_today ?? {{ $todayViews }};

            document.getElementById('views-today').textContent = count;
            document.getElementById('toast-title').textContent = `${count} klanten bekeken deze auto vandaag`;
            toast.hidden = false;
        }, 10000);
    </script>
</x-base-layout>

