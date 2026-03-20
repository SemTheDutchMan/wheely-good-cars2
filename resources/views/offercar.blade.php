<x-base-layout>
    <section class="license-entry-page">
        <form action="{{ route('offercar.step1') }}" method="POST" class="license-hero-form">
            @csrf
            <div class="license-plate-input">
                <span class="plate-country">NL</span>
                <input type="text" name="license_plate" value="{{ old('license_plate') }}" placeholder="AA-BB-12">
                <button type="submit" class="plate-submit">Go!</button>
            </div>
        </form>
    </section>
</x-base-layout>
