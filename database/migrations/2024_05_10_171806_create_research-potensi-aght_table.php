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
        if (!Schema::hasTable('research_potensi_aght')) {
            Schema::create('research_potensi_aght', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('kode_satker', 250);
                $table->uuid('id_case');
    
                $table->uuid('id_sprint');
                $table->uuid('id_lapinsus');
                $table->uuid('id_saran_tl');
                $table->string('jenis_aght')->nullable();
                $table->dateTime('waktu')->nullable();
                $table->string('tempat')->nullable();
                $table->string('perihal')->nullable();
                $table->text('keterangan')->nullable();
    
                $table->bigInteger('created_by');
                $table->bigInteger('updated_by');
                $table->timestamps();
                $table->softDeletes();
    
                $table->foreign('kode_satker')->references('kode_satker')->on('master_satker');
                $table->foreign('id_case')->references('id')->on('open_case');
                $table->foreign('id_sprint')->references('id')->on('research_sprint');
                $table->foreign('id_lapinsus')->references('id')->on('research_lapinsus');
                $table->foreign('id_saran_tl')->references('id')->on('research_saran_tl');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_potensi_aght');
    }
};
