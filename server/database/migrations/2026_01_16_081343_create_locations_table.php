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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('cityName');
            $table->decimal('zipCode');
            $table->string('street');
            $table->string('houseNumber');
            $table->string('locationName');
            $table->unique('street');
            $table->unique('houseNumber');
            $table->unique('locationName');
            $table->integer('maxCapacity');
            $table->integer('minCapacity');
            $table->integer('priceSlashPerson');
            $table->integer('roomPriceSlashDay');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
