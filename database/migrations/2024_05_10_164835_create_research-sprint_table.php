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
        Schema::create('research_sprint', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('kode_satker', 250);
            $table->uuid('id_case');

            $table->string('nomor_sprint')->nullable();
            $table->string('perihal_sprint')->nullable();
            $table->date('tanggal_sprint')->nullable();
            $table->date('tanggal_mulai_sprint')->nullable();
            $table->date('tanggal_akhir_sprint')->nullable();
            $table->text('upload_sprint')->nullable();

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('kode_satker')->references('kode_satker')->on('master_satker');
            $table->foreign('id_case')->references('id')->on('open_case');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_sprint');
    }
};
