<x-base-layout>
    <div class="container page stack">

        <h1>Aanbod van {{ $cars->total() }} {{ Str::plural('auto', $cars->total()) }}</h1>


        <div style="display: flex; gap: 1rem; flex-wrap: wrap; align-items: flex-end; margin-bottom: 1.5rem;">
            <div style="max-width: 400px; flex: 1;">
                <input id="car-search" type="text" class="input w-full" placeholder="Zoek op merk of model..." oninput="filterCars()">
            </div>
            <div style="min-width: 220px;">
                <label for="tag-filter" class="muted" style="font-size: 0.92rem; margin-bottom: 0.25rem; display: block;">Filter op tags:</label>
                <select id="tag-filter" class="input w-full" multiple size="1" style="min-width: 180px; max-width: 260px;" onchange="filterCars()">
                    <option value="" selected>Alle tags</option>
                    @foreach($tags as $tag)
                        <option value="{{ strtolower($tag->name) }}" data-color="{{ $tag->color }}">{{ $tag->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @if ($cars->isEmpty())
            <div class="card muted" style="text-align: center;">
                Nog geen auto’s geplaatst.
            </div>
        @else
            @php
            
                $featuredIndices = collect(range(0, $cars->count() - 1))
                    ->shuffle()
                    ->take(max(1, (int) ceil($cars->count() / 4)))
                    ->flip();
            @endphp
            <div class="card-grid" id="car-grid">
                @foreach ($cars as $car)
                    <a
                        href="{{ route('car.show', $car) }}"
                        class="card {{ $featuredIndices->has($loop->index) ? 'card-featured' : '' }}"
                        data-tags="{{ $car->tags->pluck('name')->map(fn($n)=>strtolower($n))->implode(',') }}"
                        aria-label="Bekijk {{ $car->make }} {{ $car->model }}"
                        data-make="{{ strtolower($car->make) }}"
                        data-model="{{ strtolower($car->model) }}"
                    >
                       
                        <div class="card-media">
                            @if ($car->image)
                                <img
                                    src="{{ $car->image }}"
                                    alt="{{ $car->make }} {{ $car->model }}"
                                    class="object-cover w-full h-full"
                                    loading="lazy"
                                    onerror="this.onerror=null;this.parentNode.innerHTML=document.getElementById('car-image-placeholder').innerHTML;"
                                >
                            @else
                                <svg width="400" height="250" viewBox="0 0 400 250" class="w-full h-full">
                                    <defs>
                                        <linearGradient id="placeholder-bg" x1="0" y1="0" x2="0" y2="1">
                                            <stop offset="0%" stop-color="#f6f7f9" />
                                            <stop offset="100%" stop-color="#eceff3" />
                                        </linearGradient>
                                    </defs>
                                    <rect width="400" height="250" fill="url(#placeholder-bg)" rx="14" ry="14" />
                                    <rect x="24" y="24" width="352" height="202" rx="10" ry="10" fill="none" stroke="#d6dbe1" />
                                    <path d="M86 162 Q108 126 146 126 H254 Q292 126 314 162" fill="none" stroke="#b9c0c9" stroke-width="8" stroke-linecap="round" />
                                    <path d="M100 168 Q120 142 146 142 H254 Q280 142 300 168" fill="#d9dee5" />
                                    <circle cx="128" cy="176" r="12" fill="#9aa3ad" />
                                    <circle cx="272" cy="176" r="12" fill="#9aa3ad" />
                                    <rect x="172" y="118" width="56" height="16" rx="6" fill="#c6ccd4" />
                                    <text x="50%" y="196" text-anchor="middle" fill="#6b7280" font-family="Arial, sans-serif" font-size="15" letter-spacing="0.02em">
                                        Geen afbeelding beschikbaar
                                    </text>
                                </svg>
                            @endif
                        </div>
                       
                        <div class="card-body">
                            <div class="muted" style="font-size: 0.8rem;">
                                {{ $car->license_plate }}
                            </div>
                            <h2>{{ $car->make }} {{ $car->model }}</h2>
                            <div class="card-header">
                                <span class="price">€{{ number_format($car->price, 0, ',', '.') }}</span>
                                <span class="muted">{{ $car->production_year }}</span>
                            </div>
                            <div class="stack" style="gap: 0.5rem;">
                                <span class="pill">{{ number_format($car->mileage, 0, ',', '.') }} km</span>
                                @if ($car->sold_at)
                                    <span class="pill pill-success">Verkocht</span>
                                @else
                                    <span class="pill pill-warning">Te koop</span>
                                @endif
                            </div>
                            <div class="tag-row">
                                @if ($car->tags && $car->tags->isNotEmpty())
                                    @foreach ($car->tags as $tag)
                                        <span class="tag" style="background-color: {{ $tag->color }}; color: #fff;">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                @else
                                    <span class="muted" style="font-size: 0.8rem;">Geen tags</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-6">
                {{ $cars->links() }}
            </div>
        @endif

        <template id="car-image-placeholder">
            <svg width="400" height="250" viewBox="0 0 400 250" class="w-full h-full">
                <defs>
                    <linearGradient id="placeholder-bg" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#f6f7f9" />
                        <stop offset="100%" stop-color="#eceff3" />
                    </linearGradient>
                </defs>
                <rect width="400" height="250" fill="url(#placeholder-bg)" rx="14" ry="14" />
                <rect x="24" y="24" width="352" height="202" rx="10" ry="10" fill="none" stroke="#d6dbe1" />
                <path d="M86 162 Q108 126 146 126 H254 Q292 126 314 162" fill="none" stroke="#b9c0c9" stroke-width="8" stroke-linecap="round" />
                <path d="M100 168 Q120 142 146 142 H254 Q280 142 300 168" fill="#d9dee5" />
                <circle cx="128" cy="176" r="12" fill="#9aa3ad" />
                <circle cx="272" cy="176" r="12" fill="#9aa3ad" />
                <rect x="172" y="118" width="56" height="16" rx="6" fill="#c6ccd4" />
                <text x="50%" y="196" text-anchor="middle" fill="#6b7280" font-family="Arial, sans-serif" font-size="15" letter-spacing="0.02em">
                    Geen afbeelding beschikbaar
                </text>
            </svg>
        </template>
        <script>
        function filterCars() {
            const q = document.getElementById('car-search').value.trim().toLowerCase();
            const tagSelect = document.getElementById('tag-filter');
            let selectedTags = Array.from(tagSelect.selectedOptions)
                .map(opt => opt.value)
                .filter(v => v && v !== '');
            document.querySelectorAll('#car-grid > a.card').forEach(card => {
                const make = card.getAttribute('data-make') || '';
                const model = card.getAttribute('data-model') || '';
                const tags = (card.getAttribute('data-tags') || '').split(',');
                const matchesSearch = (make.includes(q) || model.includes(q));
                const matchesTags = !selectedTags.length || selectedTags.some(tag => tags.includes(tag));
                card.style.display = (matchesSearch && matchesTags) ? '' : 'none';
            });
        }
        </script>
    </div>
</x-base-layout>
