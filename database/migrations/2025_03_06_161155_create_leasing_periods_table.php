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
        Schema::create('leasing_periods', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('deviceId');
            $table->foreign('deviceId')->references('id')->on('devices')->onDelete('cascade');
            $table->unsignedBigInteger('leasingConstructionId');
            $table->integer('leasingConstructionMaximumTraining')->nullable();
            $table->date('leasingConstructionMaximumDate')->nullable();
            $table->date('leasingActualPeriodStartDate')->nullable();
            $table->date('leasingNextCheck')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leasing_periods');
    }
};
