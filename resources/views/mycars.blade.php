<x-base-layout>
    <section class="my-offers-page">
        <h1>Mijn aanbod</h1>

        <div class="offers-table">
            @forelse ($cars as $car)
                <article class="offer-row" data-car-row>
                    <div class="offer-thumb">
                        @if ($car->image)
                            <img src="{{ $car->image }}" alt="{{ $car->make }} {{ $car->model }}">
                        @else
                            <span>100 x 100</span>
                        @endif
                    </div>

                    <div class="offer-plate-block">
                        <strong>{{ $car->license_plate }}</strong>
                        <button
                            type="button"
                            class="status-pill"
                            data-toggle-status
                        >
                            <span data-status-label>{{ $car->sold_at ? 'verkocht' : 'te koop' }}</span>
                        </button>
                    </div>

                    <div class="offer-price-block">
                        <form action="{{ route('cars.update', $car) }}" method="POST" class="inline-update-form" data-update-form>
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="sold" value="{{ $car->sold_at ? 1 : 0 }}" data-sold-input>
                            <span class="price-value">€</span>
                            <input type="number" name="price" value="{{ (int) $car->price }}" min="0">
                            <button type="submit" class="status-pill">opslaan</button>
                        </form>
                    </div>

                    <div class="offer-title-block">
                        <strong>{{ strtoupper($car->make) }} {{ strtoupper($car->model) }} {{ $car->production_year }}</strong>
                    </div>

                    <div class="offer-tags-block">
                        @forelse($car->tags as $tag)
                            <span class="status-pill">{{ strtolower($tag->name) }}</span>
                        @empty
                            <span class="muted-copy">geen tags</span>
                        @endforelse
                    </div>

                    <div class="offer-actions-block">
                        <a href="{{ route('cars.tags.edit', $car) }}" class="status-pill">wijzigen</a>
                        <a href="{{ route('cars.pdf', $car) }}" class="status-pill">pdf</a>
                        <form action="{{ route('cars.destroy', $car) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="status-pill">verwijder</button>
                        </form>
                    </div>
                </article>
            @empty
                <div class="empty-state">Je hebt nog geen auto's aangeboden.</div>
            @endforelse
        </div>
    </section>

    <div class="pagination-wrap">
        {{ $cars->links() }}
    </div>

    <script>
        (() => {
            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            document.querySelectorAll('[data-car-row]').forEach((row) => {
                const form = row.querySelector('[data-update-form]');
                const statusButton = row.querySelector('[data-toggle-status]');
                const soldInput = row.querySelector('[data-sold-input]');
                const statusLabel = row.querySelector('[data-status-label]');

                const submitUpdate = async () => {
                    const formData = new FormData(form);
                    const response = await fetch(form.action, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': token,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        },
                        body: formData,
                    });

                    if (!response.ok) {
                        return;
                    }

                    const data = await response.json();
                    statusLabel.textContent = data.sold_label;
                    soldInput.value = data.sold ? '1' : '0';
                };

                form.addEventListener('submit', (event) => {
                    event.preventDefault();
                    submitUpdate();
                });

                statusButton.addEventListener('click', () => {
                    soldInput.value = soldInput.value === '1' ? '0' : '1';
                    submitUpdate();
                });
            });
        })();
    </script>
</x-base-layout>
