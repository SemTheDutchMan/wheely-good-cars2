<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Car extends Model
{
    /** @use HasFactory<\Database\Factories\CarFactory> */
    use HasFactory;

    protected static ?array $catalogImageLookup = null;

    protected $fillable = [
        'user_id',
        'license_plate',
        'make',
        'model',
        'price',
        'mileage',
        'seats',
        'doors',
        'production_year',
        'weight',
        'color',
        'image',
        'sold_at',
        'views',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'car_tags');
    }

    public function dailyViews(): HasMany
    {
        return $this->hasMany(CarView::class);
    }

    public function getDisplayImageUrlAttribute(): string
    {
        if (filled($this->image)) {
            return Str::startsWith($this->image, ['http://', 'https://', '/'])
                ? $this->image
                : asset(ltrim($this->image, '/'));
        }

        $catalogImage = $this->resolveCatalogImagePath();

        if ($catalogImage !== null) {
            return asset($catalogImage);
        }

        return asset('img/car-models/404.png');
    }

    public static function flushCatalogImageCache(): void
    {
        static::$catalogImageLookup = null;
    }

    protected function catalogImageKey(): string
    {
        return Str::lower(preg_replace('/[^a-z0-9]+/i', '', $this->make . $this->model) ?? '');
    }

    protected function catalogBrandKey(): string
    {
        return Str::lower(preg_replace('/[^a-z0-9]+/i', '', $this->make) ?? '');
    }

    protected static function catalogImageLookup(): array
    {
        if (static::$catalogImageLookup !== null) {
            return static::$catalogImageLookup;
        }

        $directory = public_path('img/car-models');

        if (! is_dir($directory)) {
            return static::$catalogImageLookup = [];
        }

        $allowedExtensions = ['png', 'jpg', 'jpeg', 'webp', 'gif'];
        $lookup = [];

        foreach (scandir($directory) ?: [] as $file) {
            $path = $directory . DIRECTORY_SEPARATOR . $file;

            if (! is_file($path)) {
                continue;
            }

            $extension = Str::lower(pathinfo($file, PATHINFO_EXTENSION));

            if (! in_array($extension, $allowedExtensions, true)) {
                continue;
            }

            $lookup[Str::lower(pathinfo($file, PATHINFO_FILENAME))] = 'img/car-models/' . $file;
        }

        return static::$catalogImageLookup = $lookup;
    }

    protected function resolveCatalogImagePath(): ?string
    {
        $lookup = static::catalogImageLookup();

        foreach ([$this->catalogImageKey(), $this->catalogBrandKey()] as $key) {
            if ($key !== '' && isset($lookup[$key])) {
                return $lookup[$key];
            }
        }

        $fallbacks = collect($lookup)
            ->filter(fn (string $path, string $key) => Str::startsWith($key, '404'))
            ->sort()
            ->values();

        if ($fallbacks->isEmpty()) {
            return null;
        }

        return $fallbacks[$this->fallbackImageIndex($fallbacks->count())];
    }

    protected function fallbackImageIndex(int $count): int
    {
        if ($count <= 1) {
            return 0;
        }

        $signature = implode('|', [
            (string) $this->id,
            (string) $this->license_plate,
            (string) $this->make,
            (string) $this->model,
        ]);

        $hash = hash('sha256', $signature, true);
        $value = unpack('N', substr($hash, 0, 4))[1];

        return $value % $count;
    }
}
