<x-base-layout>
    <section class="offer-layout">
        <div class="offer-sidebar"></div>

        <form action="{{ route('cars.store') }}" method="POST" class="offer-form">
            @csrf
            <input type="hidden" name="license_plate" value="{{ $license_plate }}">

            <div class="offer-header">
                <h1>Nieuw aanbod</h1>
                <div class="inline-plate">
                    <span class="plate-country">NL</span>
                    <span class="plate-value">{{ $license_plate }}</span>
                </div>
            </div>

            <label class="field field-full">
                <span>Merk</span>
                <input type="text" name="make" value="{{ old('make', $car_api_data['make'] ?? '') }}">
            </label>
            <label class="field field-full">
                <span>Model</span>
                <input type="text" name="model" value="{{ old('model', $car_api_data['model'] ?? '') }}">
            </label>
            <label class="field">
                <span>Zitplaatsen</span>
                <input type="number" name="seats" value="{{ old('seats', $car_api_data['seats'] ?? '') }}" min="1">
            </label>
            <label class="field">
                <span>Aantal deuren</span>
                <input type="number" name="doors" value="{{ old('doors', $car_api_data['doors'] ?? '') }}" min="1">
            </label>
            <label class="field">
                <span>Massa rijklaar</span>
                <input type="number" name="weight" value="{{ old('weight', $car_api_data['weight'] ?? '') }}" min="0">
            </label>
            <label class="field">
                <span>Jaar van productie</span>
                <input type="number" name="year" value="{{ old('year', $car_api_data['year'] ?? '') }}" min="1900" max="{{ now()->year + 1 }}">
            </label>
            <label class="field">
                <span>Kleur</span>
                <input type="text" name="color" value="{{ old('color', $car_api_data['color'] ?? '') }}">
            </label>
            <label class="field field-full field-with-suffix">
                <span>Kilometerstand</span>
                <div class="input-with-suffix">
                    <input type="number" name="mileage" value="{{ old('mileage', $car_api_data['mileage'] ?? '') }}" min="0">
                    <span class="input-suffix">km</span>
                </div>
            </label>
            <label class="field field-full field-with-prefix">
                <span>Vraagprijs</span>
                <div class="input-with-prefix">
                    <span class="input-prefix">€</span>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" step="1">
                </div>
            </label>

            <button type="submit" class="wide-submit">Aanbod afronden</button>
        </form>
    </section>
</x-base-layout>
