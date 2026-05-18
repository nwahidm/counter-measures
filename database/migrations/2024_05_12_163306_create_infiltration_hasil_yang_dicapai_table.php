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
        if (!Schema::hasTable('infiltration_hasil_yang_dicapai')) {
            Schema::create('infiltration_hasil_yang_dicapai', function (Blueprint $table) {
                $table->uuid('id_infiltration_hasil_yang_dicapai')->primary();
                $table->uuid('id_infiltration_dinamika_target')->nullable();
                $table->uuid('id_infiltation_operasi_rahasia')->nullable();
                $table->uuid('id_case')->nullable();
                $table->unsignedBigInteger('id_satker')->nullable();
    
                $table->text('hasil_yang_dicapai')->nullable();
                $table->string('upload_hasil_yang_dicapai')->nullable();
    
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
        Schema::dropIfExists('infiltration_hasil_yang_dicapai');
    }
};
