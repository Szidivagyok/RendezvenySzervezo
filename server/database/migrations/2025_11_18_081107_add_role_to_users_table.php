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
        Schema::table('users', function (Blueprint $table) {
            // role mező hozzáadása: integer, alapértelmezett értéke 3
            $table->integer('role')->default(3)->after('email');
            $table->string('idNumber', 10)->default(2)->after('role');
            $table->string('city', 50)->default(null)->after('idNumber');
            $table->string('street', 70)->default(null)->after('city');
            $table->string('houseNumber', 10)->default(null)->after('street');
            $table->string('postCode', 10)->default(null)->after('street');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // A role mező eltávolítása visszavonáskor
            $table->dropColumn('role');
        });
    }
};
