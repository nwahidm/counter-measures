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
        Schema::create('interogation_target_identification', function (Blueprint $table) {
            $table->uuid('id_interogation_target_identification')->primary();
            $table->unsignedInteger('id_satker')->nullable();
            $table->uuid('id_interogation_record')->nullable();
            $table->uuid('id_case')->nullable();
            $table->text('hasil_target_identification')->nullable();
            $table->string('upload_dokumen_hasil_target_identification')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interogation_target_identification');
    }
};
