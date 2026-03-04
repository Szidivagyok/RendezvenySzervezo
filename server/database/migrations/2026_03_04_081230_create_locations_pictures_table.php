<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations_pictures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('locationId')->constrained('locations')->onDelete('cascade');
            $table->foreignId('pictureId')->constrained('pictures')->onDelete('cascade');
            $table->unique(['locationId', 'pictureId']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations_pictures');
    }
};
