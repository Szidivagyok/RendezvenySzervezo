<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('role')->default(2)->after('email');
            $table->string('idNumber', 10)->nullable()->after('role'); // nullable
            $table->string('city', 50)->nullable()->after('idNumber'); // nullable
            $table->string('street', 70)->nullable()->after('city');   // nullable
            $table->string('houseNumber', 10)->nullable()->after('street'); // nullable
            $table->string('postCode', 10)->nullable()->after('houseNumber'); // nullable
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'idNumber', 'city', 'street', 'houseNumber', 'postCode']);
        });
    }
};