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
        Schema::create('elicitation_interview_result', function (Blueprint $table) {
            $table->uuid('id_elicitation_interview_result')->primary();
            $table->uuid('id_case')->nullable();
            $table->unsignedBigInteger('id_satker')->nullable();
            $table->string('kode_satker')->nullable();

            $table->text('interviewer_name')->nullable();
            $table->text('interviewer_schedule')->nullable();
            $table->text('source_person_name')->nullable();
            $table->text('target_identity_number')->nullable();
            $table->text('target_identity_number_type')->nullable();
            $table->text('target_gender')->nullable();
            $table->text('target_religion')->nullable();
            $table->text('target_occupation')->nullable();
            $table->text('target_education')->nullable();
            $table->string('target_photo')->nullable();
            $table->text('interview_result')->nullable();
            $table->text('interview_result_path')->nullable();

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
        Schema::dropIfExists('elicitation_interview_result');
    }
};
