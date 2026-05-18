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
        if (!Schema::hasTable('research_lapinsus')) {
            Schema::create('research_lapinsus', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->string('kode_satker', 250);
                $table->uuid('id_case');
    
                $table->uuid('id_sprint');
                $table->string('nomor_surat')->nullable();
                $table->date('tanggal_surat')->nullable();
                $table->string('perihal_surat')->nullable();
                $table->text('pendahuluan')->nullable();
                $table->string('data_fakta')->nullable();
                $table->string('telaahan')->nullable();
                $table->string('kesimpulan')->nullable();
                $table->string('pendapat')->nullable();
                $table->text('upload_lapinsus')->nullable();
    
                $table->bigInteger('created_by');
                $table->bigInteger('updated_by');
                $table->timestamps();
                $table->softDeletes();
    
                $table->foreign('kode_satker')->references('kode_satker')->on('master_satker');
                $table->foreign('id_case')->references('id')->on('open_case');
                $table->foreign('id_sprint')->references('id')->on('research_sprint');
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_lapinsus');
    }
};
