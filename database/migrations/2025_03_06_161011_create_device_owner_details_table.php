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
        Schema::create('device_owner_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deviceId');
            $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            $table->string('billingName')->nullable();
            $table->string('addressCountry')->nullable();
            $table->string('addressZip')->nullable();
            $table->string('addressCity')->nullable();
            $table->string('addressStreet')->nullable();
            $table->string('vatNumber')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_owner_details');
    }
};
