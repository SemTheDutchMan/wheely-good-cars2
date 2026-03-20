<x-base-layout>
    <section class="section-head">
        <div>
            <p class="section-kicker">Beheer</p>
            <h1>Tag-analyses en opvallende aanbieders</h1>
        </div>
        <a href="{{ route('admin.live-dashboard') }}" class="button">Open live dashboard</a>
    </section>

    <div class="admin-grid">
        <div class="table-card">
            <h2>Taggebruik</h2>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Tag</th>
                        <th>Totaal</th>
                        <th>Niet verkocht</th>
                        <th>Verkocht</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tags as $tag)
                        <tr>
                            <td><span class="tag-badge" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span></td>
                            <td>{{ $tag->cars_count }}</td>
                            <td>{{ $tag->unsold_cars_count }}</td>
                            <td>{{ $tag->sold_cars_count }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="stack-panel">
            <h2>Opvallende aanbieders</h2>
            @forelse ($suspiciousDealers as $entry)
                <article class="alert-card">
                    <div class="alert-head">
                        <strong>{{ $entry['user']->name }}</strong>
                        <span>{{ $entry['cars_count'] }} auto's</span>
                    </div>
                    <p class="muted-copy">{{ $entry['user']->email }}</p>
                    <ul class="reason-list">
                        @foreach ($entry['reasons'] as $reason)
                            <li>{{ $reason }}</li>
                        @endforeach
                    </ul>
                </article>
            @empty
                <div class="empty-state">Er zijn momenteel geen aanbieders gemarkeerd.</div>
            @endforelse
        </div>
    </div>
</x-base-layout>
