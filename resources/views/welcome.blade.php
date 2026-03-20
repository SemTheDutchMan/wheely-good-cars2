<x-base-layout>
    <section class="hero-panel">
        <div>
            <p class="section-kicker">Vind jouw volgende auto</p>
            <h1>Speels aanbod, slimme filters en directe details.</h1>
            <p class="hero-copy">Blader door het actuele aanbod van WheelyGoodCars. Verkochte auto's verdwijnen automatisch uit het openbare overzicht.</p>
        </div>
    </section>

    <section class="filter-panel">
        <form id="inventory-filter-form" action="{{ route('home') }}" method="GET" class="filter-grid">
            <label class="field">
                <span>Zoek op merk of model</span>
                <input type="search" name="search" value="{{ $search }}" placeholder="Bijv. Volvo of Golf">
            </label>

            <label class="field">
                <span>Filter op tags</span>
                <select name="tags[]" multiple size="4">
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTags, true))>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </label>

            <div class="filter-actions">
                <button type="submit" class="button">Filter aanbod</button>
                <a href="{{ route('home') }}" class="button button-secondary">Reset</a>
            </div>
        </form>
    </section>

    @include('partials.public-inventory')

    <script>
        (() => {
            const form = document.getElementById('inventory-filter-form');
            if (!form) {
                return;
            }

            let timeoutId;

            const refreshResults = async (url) => {
                const response = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                    },
                });

                const html = await response.text();
                const doc = new DOMParser().parseFromString(html, 'text/html');
                const nextResults = doc.querySelector('#inventory-results');
                const currentResults = document.querySelector('#inventory-results');

                if (nextResults && currentResults) {
                    currentResults.replaceWith(nextResults);
                    bindPagination();
                }
            };

            const serializeForm = () => {
                const params = new URLSearchParams(new FormData(form));
                return `${form.action}?${params.toString()}`;
            };

            const queueRefresh = () => {
                clearTimeout(timeoutId);
                timeoutId = setTimeout(() => {
                    const url = serializeForm();
                    window.history.replaceState({}, '', url);
                    refreshResults(url);
                }, 200);
            };

            form.querySelectorAll('input, select').forEach((element) => {
                element.addEventListener('input', queueRefresh);
                element.addEventListener('change', queueRefresh);
            });

            form.addEventListener('submit', (event) => {
                event.preventDefault();
                const url = serializeForm();
                window.history.replaceState({}, '', url);
                refreshResults(url);
            });

            const bindPagination = () => {
                document.querySelectorAll('.pagination a').forEach((link) => {
                    link.addEventListener('click', (event) => {
                        event.preventDefault();
                        const url = link.getAttribute('href');

                        if (!url) {
                            return;
                        }

                        window.history.replaceState({}, '', url);
                        refreshResults(url);
                    });
                });
            };

            bindPagination();
        })();
    </script>
</x-base-layout>
