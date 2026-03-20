<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tags')) {
            Schema::create('tags', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('color', 20)->default('#10243f');
                $table->timestamps();
            });

            return;
        }

        Schema::table('tags', function (Blueprint $table) {
            if (! Schema::hasColumn('tags', 'color')) {
                $table->string('color', 20)->default('#10243f');
            }
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};
