<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_views', function (Blueprint $table) {
            $table->id();
            $table->foreignId('car_id')->constrained()->cascadeOnDelete();
            $table->date('view_date');
            $table->unsignedInteger('views')->default(0);
            $table->unique(['car_id', 'view_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_views');
    }
};
