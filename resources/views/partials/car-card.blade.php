<a href="{{ route('car.show', $car) }}" class="car-card {{ $featured ? 'car-card-featured' : '' }}">
    <div class="car-card-media">
        @if ($car->image)
            <img src="{{ $car->image }}" alt="{{ $car->make }} {{ $car->model }}" loading="lazy">
        @else
            <div class="car-card-placeholder">
                <span>{{ $car->make }}</span>
                <strong>{{ $car->model }}</strong>
            </div>
        @endif
    </div>

    <div class="car-card-body">
        <div class="eyebrow">{{ $car->license_plate }}</div>
        <div class="car-card-row">
            <h2>{{ $car->make }} {{ $car->model }}</h2>
            <span class="price-chip">EUR {{ number_format($car->price, 0, ',', '.') }}</span>
        </div>
        <div class="spec-line">
            <span>{{ number_format($car->mileage, 0, ',', '.') }} km</span>
            <span>{{ $car->production_year ?: 'Onbekend bouwjaar' }}</span>
            <span>{{ $car->color ?: 'Kleur onbekend' }}</span>
        </div>
        <div class="tag-list">
            @forelse($car->tags as $tag)
                <span class="tag-badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
            @empty
                <span class="muted-copy">Geen tags</span>
            @endforelse
        </div>
    </div>
</a>
