<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cars')) {
            Schema::create('cars', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->string('license_plate', 16)->unique();
                $table->string('make');
                $table->string('model');
                $table->decimal('price', 10, 2);
                $table->unsignedInteger('mileage');
                $table->unsignedTinyInteger('seats')->nullable();
                $table->unsignedTinyInteger('doors')->nullable();
                $table->unsignedSmallInteger('production_year')->nullable();
                $table->unsignedInteger('weight')->nullable();
                $table->string('color')->nullable();
                $table->string('image')->nullable();
                $table->timestamp('sold_at')->nullable();
                $table->unsignedInteger('views')->default(0);
                $table->timestamps();
            });

            return;
        }

        Schema::table('cars', function (Blueprint $table) {
            if (! Schema::hasColumn('cars', 'image')) {
                $table->string('image')->nullable();
            }

            if (! Schema::hasColumn('cars', 'sold_at')) {
                $table->timestamp('sold_at')->nullable();
            }

            if (! Schema::hasColumn('cars', 'views')) {
                $table->unsignedInteger('views')->default(0);
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};
