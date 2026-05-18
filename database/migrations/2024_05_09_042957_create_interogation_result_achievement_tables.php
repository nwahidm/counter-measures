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
        Schema::create('interogation_result_achievement', function (Blueprint $table) {
            $table->uuid('id_interogation_result_achivement')->primary();
            $table->unsignedInteger('id_satker')->nullable();
            $table->uuid('id_interogation_target_identification')->nullable();
            $table->uuid('id_interogation_record')->nullable();
            $table->uuid('id_case')->nullable();
            $table->text('hasil_yang_dicapai')->nullable();
            $table->string('upload_hasil_yang_dicapai')->nullable();
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
        Schema::dropIfExists('interogation_result_achievement');
    }
};
