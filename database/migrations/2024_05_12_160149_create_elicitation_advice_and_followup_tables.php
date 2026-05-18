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
        Schema::create('elicitation_advice_and_followup', function (Blueprint $table) {
            $table->uuid('id_elicitation_advice_and_followup')->primary();
            $table->uuid('id_elicitation_interview_result')->nullable();
            $table->uuid('id_case')->nullable();
            $table->unsignedBigInteger('id_satker')->nullable();
            $table->string('kode_satker')->nullable();

            $table->date('tanggal_tinjut')->nullable();
            $table->text('saran_tinjut')->nullable();

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
        Schema::dropIfExists('elicitation_advice_and_followup');
    }
};
