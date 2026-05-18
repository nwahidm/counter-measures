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
        if (!Schema::hasTable('research_saran_tl')) {
            Schema::create('research_saran_tl', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('kode_satker', 250);
                $table->uuid('id_case');
                $table->uuid('id_sprint');
                $table->uuid('id_lapinsus');
                $table->date('tanggal_tl')->nullable();
                $table->text('saran_tl')->nullable();
    
                $table->bigInteger('created_by');
                $table->bigInteger('updated_by');
                $table->timestamps();
                $table->softDeletes();
    
                $table->foreign('kode_satker')->references('kode_satker')->on('master_satker');
                $table->foreign('id_case')->references('id')->on('open_case');
                $table->foreign('id_sprint')->references('id')->on('research_sprint');
                $table->foreign('id_lapinsus')->references('id')->on('research_lapinsus');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_saran_tl');
    }
};
