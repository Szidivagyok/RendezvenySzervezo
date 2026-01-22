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
            $table->string('cityName',80);
            $table->string('zipCode', 5);
            $table->string('street', 125);
            $table->string('houseNumber', 10);
            $table->string('locationName', 80);
            $table->unique(['zipCode', 'street', 'houseNumber', 'locationName']);
            $table->integer('maxCapacity');
            $table->integer('minCapacity');
            $table->decimal('priceSlashPerson', 10,2);
            $table->decimal('roomPriceSlashDay', 10,2);
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
