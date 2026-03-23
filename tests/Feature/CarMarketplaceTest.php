<?php

namespace Tests\Feature;

use App\Models\Car;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CarMarketplaceTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_inventory_only_shows_unsold_cars(): void
    {
        $seller = User::factory()->create();

        Car::factory()->create([
            'user_id' => $seller->id,
            'make' => 'Volvo',
            'model' => 'XC60',
            'sold_at' => null,
        ]);

        Car::factory()->create([
            'user_id' => $seller->id,
            'make' => 'BMW',
            'model' => 'X5',
            'sold_at' => now(),
        ]);

        $response = $this->get(route('home'));

        $response->assertOk();
        $response->assertSee('Volvo XC60');
        $response->assertDontSee('BMW X5');
    }

    public function test_authenticated_seller_can_complete_multistep_offer_flow(): void
    {
        Http::fake([
            '*' => Http::response([
                [
                    'merk' => 'Audi',
                    'handelsbenaming' => 'A4',
                    'datum_eerste_toelating' => '20190501',
                    'massa_ledig_voertuig' => '1450',
                    'eerste_kleur' => 'Zwart',
                    'aantal_zitplaatsen' => '5',
                    'aantal_deuren' => '4',
                ],
            ], 200),
        ]);

        $seller = User::factory()->create();
        $tag = Tag::create(['name' => 'SUV', 'color' => '#123456']);

        $this->actingAs($seller)
            ->post(route('offercar.step1'), ['license_plate' => '12-ab-34'])
            ->assertRedirect(route('offercar.step2', ['license_plate' => '12AB34']));

        $storeResponse = $this->actingAs($seller)->post(route('cars.store'), [
            'license_plate' => '12AB34',
            'make' => 'Audi',
            'model' => 'A4',
            'price' => 19950,
            'mileage' => 125000,
            'year' => 2019,
            'weight' => 1450,
            'color' => 'Zwart',
            'seats' => 5,
            'doors' => 4,
        ]);

        $car = Car::first();

        $storeResponse->assertRedirect(route('offercar.step3', $car));

        $this->actingAs($seller)
            ->post(route('offercar.tags.store'), [
                'car_id' => $car->id,
                'tags' => [$tag->id],
            ])
            ->assertRedirect(route('cars.mycars'));

        $this->assertDatabaseHas('cars', [
            'license_plate' => '12AB34',
            'user_id' => $seller->id,
        ]);
        $this->assertDatabaseHas('car_tags', [
            'car_id' => $car->id,
            'tag_id' => $tag->id,
        ]);
    }

    public function test_owner_can_update_price_and_mark_car_as_sold_without_full_page_reload(): void
    {
        $seller = User::factory()->create();
        $car = Car::factory()->create([
            'user_id' => $seller->id,
            'price' => 10000,
            'sold_at' => null,
        ]);

        $response = $this->actingAs($seller)->patchJson(route('cars.update', $car), [
            'price' => 12500,
            'sold' => true,
        ]);

        $response->assertOk()
            ->assertJson([
                'message' => 'Aanbod bijgewerkt.',
                'sold' => true,
                'sold_label' => 'Verkocht',
            ]);

        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'price' => 12500,
        ]);
        $this->assertNotNull($car->fresh()->sold_at);
    }

    public function test_car_prefers_exact_model_image_over_brand_fallback(): void
    {
        $exactImage = $this->putCatalogImage('orionultra7.png');
        $brandImage = $this->putCatalogImage('orion.png');
        Car::flushCatalogImageCache();

        try {
            $car = Car::factory()->make([
                'make' => 'Orion',
                'model' => 'Ultra 7',
                'image' => null,
            ]);

            $this->assertSame(asset('img/car-models/orionultra7.png'), $car->display_image_url);
        } finally {
            $this->removeCatalogImages([$exactImage, $brandImage]);
        }
    }

    public function test_car_uses_brand_image_when_exact_model_image_is_missing(): void
    {
        $brandImage = $this->putCatalogImage('nebula.png');
        Car::flushCatalogImageCache();

        try {
            $car = Car::factory()->make([
                'make' => 'Nebula',
                'model' => 'Roadster',
                'image' => null,
            ]);

            $this->assertSame(asset('img/car-models/nebula.png'), $car->display_image_url);
        } finally {
            $this->removeCatalogImages([$brandImage]);
        }
    }

    public function test_car_uses_one_of_the_available_404_images_when_brand_is_missing(): void
    {
        $fallbackA = $this->putCatalogImage('404-city.png');
        $fallbackB = $this->putCatalogImage('404-suv.png');
        Car::flushCatalogImageCache();

        try {
            $car = Car::factory()->make([
                'make' => 'Quantum',
                'model' => 'Sprint',
                'image' => null,
            ]);

            $this->assertContains($car->display_image_url, [
                asset('img/car-models/404-city.png'),
                asset('img/car-models/404-suv.png'),
                asset('img/car-models/404.png'),
            ]);
        } finally {
            $this->removeCatalogImages([$fallbackA, $fallbackB]);
        }
    }

    private function putCatalogImage(string $filename): string
    {
        $directory = public_path('img/car-models');

        if (! is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $path = $directory . DIRECTORY_SEPARATOR . $filename;
        file_put_contents($path, 'fake image');

        return $path;
    }

    private function removeCatalogImages(array $paths): void
    {
        foreach ($paths as $path) {
            if (is_file($path)) {
                @unlink($path);
            }
        }

        Car::flushCatalogImageCache();
    }
}
