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
        if (!Schema::hasTable('infiltration_dinamika_target')) {
            Schema::create('infiltration_dinamika_target', function (Blueprint $table) {
                $table->uuid('id_infiltration_dinamika_target')->primary();
                $table->uuid('id_infiltation_operasi_rahasia')->nullable();
                $table->uuid('id_case')->nullable();
                $table->unsignedBigInteger('id_satker')->nullable();
    
                $table->text('dinamika_teramati')->nullable();
                $table->date('tanggal_dinamika_teramati')->nullable();
                $table->text('deskripsi_dinamika_teramati')->nullable();
                $table->string('dinamika_target_dokumen_upload')->nullable();
                $table->string('dinamika_target_video_upload')->nullable();
    
                $table->unsignedInteger('created_by')->nullable();
                $table->unsignedInteger('updated_by')->nullable();
                $table->timestamps();
                $table->softDeletes();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('infiltration_dinamika_target');
    }
};
