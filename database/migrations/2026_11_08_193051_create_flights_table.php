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
        Schema::create('flights', function (Blueprint $table) {
            $table->id();
            $table->string('flight_number')->unique(); // Đổi thành mã chuyến bay, không cho trùng lặp
            $table->string('origin');                  // Đổi departure thành origin (Điểm đi) cho chuẩn thuật ngữ
            $table->string('destination');             // Điểm đến
            $table->dateTime('departure_time');        // Lưu cả ngày và giờ cất cánh
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
