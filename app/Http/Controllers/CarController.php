<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCarRequest;
use App\Http\Requests\UpdateCarRequest;
use App\Models\Car;
use App\Models\CarView;
use App\Models\Tag;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class CarController extends Controller
{
    public function index(Request $request): View
    {
        $selectedTags = collect($request->input('tags', []))
            ->filter()
            ->map(fn ($tag) => (int) $tag)
            ->values()
            ->all();

        $cars = Car::query()
            ->with('tags')
            ->whereNull('sold_at')
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = trim((string) $request->string('search'));

                $query->where(function ($builder) use ($search) {
                    $builder->where('make', 'like', "%{$search}%")
                        ->orWhere('model', 'like', "%{$search}%");
                });
            })
            ->when($selectedTags !== [], function ($query) use ($selectedTags) {
                $query->whereHas('tags', function ($builder) use ($selectedTags) {
                    $builder->whereIn('tags.id', $selectedTags);
                });
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $tags = Tag::query()
            ->orderBy('name')
            ->get();

        return view('welcome', [
            'cars' => $cars,
            'tags' => $tags,
            'selectedTags' => $selectedTags,
            'search' => $request->string('search')->toString(),
        ]);
    }

    public function showMyCars(Request $request): View
    {
        $cars = Car::query()
            ->with('tags')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(15);

        return view('mycars', ['cars' => $cars]);
    }

    public function create(): View
    {
        return view('offercar');
    }

    public function createStep1(Request $request): RedirectResponse
    {
        $request->validate([
            'license_plate' => ['required', 'string', 'max:16'],
        ], [
            'license_plate.required' => 'Het kenteken is verplicht.',
        ]);

        $licensePlate = $this->normalizeLicensePlate($request->string('license_plate')->toString());

        if ($licensePlate === '') {
            return back()->withInput()->withErrors([
                'license_plate' => 'Voer een geldig kenteken in.',
            ]);
        }

        if (Car::where('license_plate', $licensePlate)->exists()) {
            return back()->withInput()->withErrors([
                'license_plate' => 'Deze auto is al aangeboden.',
            ]);
        }

        session([
            'car_api_data' => $this->fetchRdwData($licensePlate),
        ]);

        return redirect()->route('offercar.step2', ['license_plate' => $licensePlate]);
    }

    public function createStep2(string $license_plate): View
    {
        return view('offercar_step2', [
            'license_plate' => $license_plate,
            'car_api_data' => session('car_api_data', []),
        ]);
    }

    public function store(StoreCarRequest $request): RedirectResponse
    {
        $this->authorize('create', Car::class);

        $validated = $request->validated();
        $licensePlate = $this->normalizeLicensePlate($validated['license_plate']);

        if (Car::where('license_plate', $licensePlate)->exists()) {
            return back()->withInput()->withErrors([
                'license_plate' => 'Deze auto is al aangeboden.',
            ]);
        }

        $car = Car::create([
            'user_id' => $request->user()->id,
            'license_plate' => $licensePlate,
            'make' => $validated['make'],
            'model' => $validated['model'],
            'price' => $validated['price'],
            'mileage' => $validated['mileage'],
            'seats' => $validated['seats'] ?? null,
            'doors' => $validated['doors'] ?? null,
            'production_year' => $validated['year'] ?? null,
            'weight' => $validated['weight'] ?? null,
            'color' => $validated['color'] ?? null,
        ]);

        return redirect()
            ->route('offercar.step3', $car)
            ->with('success', 'Basisgegevens opgeslagen. Voeg nu optioneel tags toe.');
    }

    public function createStep3(Car $car): View
    {
        $this->authorize('view', $car);

        return view('offercar_step3', [
            'car' => $car->load('tags'),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function storeTags(Request $request): RedirectResponse
    {
        $car = Car::findOrFail((int) $request->input('car_id'));
        $this->authorize('update', $car);

        $validated = $request->validate([
            'car_id' => ['required', 'exists:cars,id'],
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $car->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('cars.mycars')->with('success', 'Aanbod opgeslagen.');
    }

    public function editTags(Car $car): View
    {
        $this->authorize('update', $car);

        return view('edit_tags', [
            'car' => $car->load('tags'),
            'tags' => Tag::query()->orderBy('name')->get(),
        ]);
    }

    public function updateTags(Request $request, Car $car): RedirectResponse
    {
        $this->authorize('update', $car);

        $validated = $request->validate([
            'tags' => ['nullable', 'array'],
            'tags.*' => ['exists:tags,id'],
        ]);

        $car->tags()->sync($validated['tags'] ?? []);

        return back()->with('success', 'Tags bijgewerkt.');
    }

    public function show(Car $car): View
    {
        $car->load(['tags', 'user']);
        $car->increment('views');
        $car->refresh();

        $today = Carbon::today()->toDateString();
        $currentViews = CarView::query()
            ->firstOrCreate(
                [
                    'car_id' => $car->id,
                    'view_date' => $today,
                ],
                ['views' => 0]
            );
        $currentViews->increment('views');

        $todayViews = CarView::query()
            ->where('car_id', $car->id)
            ->whereDate('view_date', $today)
            ->value('views') ?? 1;

        return view('show_car', [
            'car' => $car,
            'todayViews' => $todayViews,
        ]);
    }

    public function todayViews(Car $car): JsonResponse
    {
        $views = CarView::query()
            ->where('car_id', $car->id)
            ->whereDate('view_date', Carbon::today()->toDateString())
            ->value('views') ?? 0;

        return response()->json(['views_today' => $views]);
    }

    public function update(UpdateCarRequest $request, Car $car): JsonResponse
    {
        $this->authorize('update', $car);

        $validated = $request->validated();
        $sold = (bool) $validated['sold'];

        $car->update([
            'price' => $validated['price'],
            'sold_at' => $sold ? ($car->sold_at ?? now()) : null,
        ]);

        return response()->json([
            'message' => 'Aanbod bijgewerkt.',
            'sold' => $car->sold_at !== null,
            'sold_label' => $car->sold_at ? 'Verkocht' : 'Te koop',
            'price' => number_format((float) $car->price, 0, ',', '.'),
        ]);
    }

    public function destroy(Car $car): RedirectResponse
    {
        $this->authorize('delete', $car);
        $car->delete();

        return redirect()->route('cars.mycars')->with('success', 'Auto succesvol verwijderd.');
    }

    private function normalizeLicensePlate(string $value): string
    {
        $normalized = strtoupper(preg_replace('/[^A-Z0-9]/i', '', $value) ?? '');

        return strlen($normalized) >= 6 ? $normalized : '';
    }

    private function fetchRdwData(string $licensePlate): array
    {
        try {
            $response = Http::timeout(6)
                ->acceptJson()
                ->get('https://opendata.rdw.nl/resource/m9d7-ebf2.json', [
                    'kenteken' => $licensePlate,
                    '$limit' => 1,
                ]);

            if (! $response->successful()) {
                return [];
            }

            $data = $response->json();

            if (! is_array($data) || $data === []) {
                return [];
            }

            $row = $data[0];

            return [
                'make' => $row['merk'] ?? '',
                'model' => $row['handelsbenaming'] ?? '',
                'mileage' => null,
                'year' => isset($row['datum_eerste_toelating']) ? (int) Str::of($row['datum_eerste_toelating'])->substr(0, 4) : null,
                'weight' => isset($row['massa_ledig_voertuig']) ? (int) $row['massa_ledig_voertuig'] : null,
                'color' => $row['eerste_kleur'] ?? '',
                'seats' => isset($row['aantal_zitplaatsen']) ? (int) $row['aantal_zitplaatsen'] : null,
                'doors' => isset($row['aantal_deuren']) ? (int) $row['aantal_deuren'] : null,
                'fuel' => $row['brandstof_omschrijving'] ?? null,
                'body' => $row['inrichting'] ?? null,
            ];
        } catch (\Throwable) {
            return [];
        }
    }
}
