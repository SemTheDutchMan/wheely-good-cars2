<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarView;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AdminController extends Controller
{
    public function index(Request $request): View
    {
        $this->ensureAdmin($request);

        $tags = Tag::query()
            ->withCount([
                'cars',
                'cars as sold_cars_count' => fn ($query) => $query->whereNotNull('sold_at'),
                'cars as unsold_cars_count' => fn ($query) => $query->whereNull('sold_at'),
            ])
            ->orderByDesc('cars_count')
            ->get();

        return view('admin', [
            'tags' => $tags,
            'suspiciousDealers' => $this->suspiciousDealers(),
        ]);
    }

    public function dashboard(Request $request): View
    {
        if ($this->isAdmin($request->user())) {
            return $this->liveDashboard($request);
        }

        return view('dashboard');
    }

    public function liveDashboard(Request $request): View
    {
        $this->ensureAdmin($request);

        return view('admin-live-dashboard', [
            'stats' => $this->buildStats(),
        ]);
    }

    public function stats(Request $request): JsonResponse
    {
        $this->ensureAdmin($request);

        return response()->json($this->buildStats());
    }

    private function buildStats(): array
    {
        $today = Carbon::today();
        $offeredCount = Car::count();
        $sellerCount = User::where('is_admin', false)->count();
        $todayViews = CarView::query()
            ->whereDate('view_date', $today->toDateString())
            ->sum('views');

        $priceBuckets = [
            'budget' => Car::where('price', '<', 10000)->count(),
            'midrange' => Car::whereBetween('price', [10000, 30000])->count(),
            'premium' => Car::where('price', '>', 30000)->count(),
        ];

        $brandCounts = Car::query()
            ->selectRaw('make, COUNT(*) as total')
            ->groupBy('make')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return [
            'offered' => $offeredCount,
            'sold' => Car::whereNotNull('sold_at')->count(),
            'today_offered' => Car::whereDate('created_at', $today->toDateString())->count(),
            'sellers' => $sellerCount,
            'today_views' => $todayViews,
            'avg_per_seller' => $sellerCount > 0 ? round($offeredCount / $sellerCount, 1) : 0,
            'price_buckets' => $priceBuckets,
            'top_brands' => $brandCounts,
        ];
    }

    private function suspiciousDealers()
    {
        $oneYearAgo = Carbon::now()->subYear();

        return User::query()
            ->where('is_admin', false)
            ->with([
                'cars' => fn ($query) => $query->with('tags')->latest(),
            ])
            ->get()
            ->map(function (User $user) use ($oneYearAgo) {
                $cars = $user->cars;
                $reasons = [];

                if (blank($user->phone_number)) {
                    $reasons[] = 'Geen telefoonnummer ingevuld.';
                }

                if ($cars->contains(fn (Car $car) => $car->production_year !== null
                    && $car->production_year <= now()->year - 12
                    && $car->mileage <= 90000)) {
                    $reasons[] = 'Oude auto met opvallend lage kilometerstand.';
                }

                if ($cars->filter(fn (Car $car) => $car->sold_at !== null
                    && $car->created_at->isSameDay($car->sold_at)
                    && $car->price > 10000)->count() > 3) {
                    $reasons[] = 'Meer dan 3 dure auto\'s direct als verkocht gemarkeerd.';
                }

                if ($cars->isNotEmpty() && $cars->every(fn (Car $car) => $car->price < 1000)) {
                    $reasons[] = 'Enkel auto\'s onder 1000 euro.';
                }

                if ($cars->isNotEmpty() && $cars->every(fn (Car $car) => $car->tags->isEmpty())) {
                    $reasons[] = 'Geen tags in gebruik.';
                }

                $lastCarDate = $cars->max('created_at');
                if ($lastCarDate && Carbon::parse($lastCarDate)->lt($oneYearAgo)) {
                    $reasons[] = 'Al meer dan een jaar geen nieuw aanbod.';
                }

                return [
                    'user' => $user,
                    'cars_count' => $cars->count(),
                    'reasons' => $reasons,
                ];
            })
            ->filter(fn (array $entry) => $entry['reasons'] !== [])
            ->sortByDesc(fn (array $entry) => count($entry['reasons']))
            ->values();
    }

    private function ensureAdmin(Request $request): void
    {
        abort_unless($this->isAdmin($request->user()), 403);
    }

    private function isAdmin(?User $user): bool
    {
        return (bool) ($user?->is_admin ?? false);
    }
}
