@php
    $featuredIndices = collect(range(0, max($cars->count() - 1, 0)))
        ->shuffle()
        ->take(max(1, (int) ceil(max($cars->count(), 1) / 4)))
        ->flip();
@endphp

<div id="inventory-results">
    <div class="section-head">
        <div>
            <p class="section-kicker">Openbare voorraad</p>
            <h1>{{ $cars->total() }} auto's beschikbaar</h1>
        </div>
        <p class="muted-copy">Zoeken en filteren werkt zonder page reload.</p>
    </div>

    @if ($cars->isEmpty())
        <div class="empty-state">Geen auto's gevonden voor deze filters.</div>
    @else
        <div class="inventory-grid">
            @foreach ($cars as $car)
                @include('partials.car-card', ['car' => $car, 'featured' => $featuredIndices->has($loop->index)])
            @endforeach
        </div>

        <div class="pagination-wrap">
            {{ $cars->links() }}
        </div>
    @endif
</div>
