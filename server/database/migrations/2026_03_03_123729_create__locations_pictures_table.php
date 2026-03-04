<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('locationsPictures', function (Blueprint $table) {
            $table->id(); // Auto-increment primary key
            $table->string('pictureName');
            $table->unsignedBigInteger('service_picture_id');
            $table->string('file_path'); // Optional: path to the picture
            $table->timestamps();

            // Make PictureName + ServicePictureId unique
            $table->unique(['pictureName', 'service_picture_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('locations_pictures');
    }
};