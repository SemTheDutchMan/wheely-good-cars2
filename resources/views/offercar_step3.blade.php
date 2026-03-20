<x-base-layout>
    <section class="section-head">
        <div>
            <p class="section-kicker">Stap 3 van 3</p>
            <h1>Kies tags voor {{ $car->make }} {{ $car->model }}</h1>
        </div>
    </section>

    <div class="progress-shell">
        <div class="progress-bar" style="width: 100%"></div>
    </div>

    <div class="form-card">
        <form action="{{ route('offercar.tags.store') }}" method="POST" class="stack-form">
            @csrf
            <input type="hidden" name="car_id" value="{{ $car->id }}">

            <div class="tag-picker">
                @foreach ($tags as $tag)
                    <label class="tag-choice">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($car->tags->contains($tag->id))>
                        <span class="tag-badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Opslaan en afronden</button>
                <button type="submit" class="button button-secondary">Opslaan zonder extra tags</button>
            </div>
        </form>
    </div>
</x-base-layout>
