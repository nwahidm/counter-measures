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
        Schema::create('research_potensi_aght_lampiran', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_potensi_aght');

            $table->text('url_lampiran')->nullable();

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_potensi_aght')->references('id')->on('research_potensi_aght');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_potensi_aght_lampiran');
    }
};
