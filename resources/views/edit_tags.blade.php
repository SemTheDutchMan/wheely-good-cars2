<x-base-layout>
    <section class="section-head">
        <div>
            <p class="section-kicker">Tags aanpassen</p>
            <h1>{{ $car->make }} {{ $car->model }}</h1>
        </div>
    </section>

    <div class="form-card">
        <form action="{{ route('cars.tags.update', $car) }}" method="POST" class="stack-form">
            @csrf
            @method('PATCH')

            <div class="tag-picker">
                @foreach ($tags as $tag)
                    <label class="tag-choice">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}" @checked($car->tags->contains($tag->id))>
                        <span class="tag-badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>

            <div class="form-actions">
                <button type="submit" class="button">Tags opslaan</button>
            </div>
        </form>
    </div>
</x-base-layout>
