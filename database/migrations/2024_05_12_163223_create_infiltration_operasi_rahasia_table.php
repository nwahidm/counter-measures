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
        if (!Schema::hasTable('infiltration_operasi_rahasia')) {
            Schema::create('infiltration_operasi_rahasia', function (Blueprint $table) {
                $table->uuid('id_infiltration_operasi_rahasia')->primary();
                $table->uuid('id_case')->nullable();
                $table->unsignedBigInteger('id_satker')->nullable();
    
                $table->text('nama_operasi_rahasia')->nullable();
                $table->date('tanggal_operasi_rahasia')->nullable();
                $table->text('metode_eksekusi')->nullable();
                $table->string('operasi_rahasia_dokumen_upload')->nullable();
                $table->string('operasi_rahasia_video_upload')->nullable();
    
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
        Schema::dropIfExists('infiltration_operasi_rahasia');
    }
};
