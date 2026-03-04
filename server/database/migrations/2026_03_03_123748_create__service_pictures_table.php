<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servicsPictures', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('serviceName')->unique(); // Unique service name
            $table->string('description')->nullable(); // Optional description
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servicePictures');
    }
};