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
        Schema::create('lawyer', function (Blueprint $table) {
            $table->id();
            $table->string('fullname');
            $table->string('address');
            $table->string('roll_signed_date');
            $table->string('roll_number');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lawyer');
    }
};
