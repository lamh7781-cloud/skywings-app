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
        Schema::table('flights', function (Blueprint $table) {
            $table->dropUnique(['flight_number']);
            $table->string('cabin_class', 32)->default('economy')->after('price');
            $table->unsignedSmallInteger('seats_available')->default(100)->after('cabin_class');
            $table->unique(['flight_number', 'cabin_class']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('flights', function (Blueprint $table) {
            $table->dropUnique(['flight_number', 'cabin_class']);
            $table->dropColumn(['cabin_class', 'seats_available']);
            $table->unique('flight_number');
        });
    }
};
