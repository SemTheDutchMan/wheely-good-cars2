<?php

use Livewire\Component;
use App\Models\Car;

new class extends Component
{
    public Car $car;
    public bool $sold;
    public int $price;

    public function mount(Car $car)
    {
        $this->car = $car;
        $this->sold = (bool) $car->sold_at;
        $this->price = $car->price;
    }

    public function toggleSold()
    {
        if (auth()->id() !== $this->car->user_id) {
            abort(403);
        }

        $this->sold = ! $this->sold;

        $this->car->update([
            'sold_at' => $this->sold ? now() : null,
        ]);
    }

    public function updatePrice()
    {
        if (auth()->id() !== $this->car->user_id) {
            abort(403);
        }

        $this->validate([
            'price' => 'required|numeric|min:0',
        ]);

        $this->car->update([
            'price' => $this->price,
        ]);
    }
};
?>

<div class="flex items-center gap-2">
    
    <input
        type="number"
        wire:model.lazy="price"
        wire:change="updatePrice"
        class="input input-small"
        style="max-width: 90px;"
    >

   
    <button
        wire:click="toggleSold"
        class="pill {{ $sold ? 'pill-warning' : 'pill-success' }}"
    >
        {{ $sold ? 'Verkocht' : 'Te koop' }}
    </button>
</div>
