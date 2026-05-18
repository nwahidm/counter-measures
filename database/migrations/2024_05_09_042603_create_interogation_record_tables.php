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
        Schema::create('interogation_record', function (Blueprint $table) {
            $table->uuid('id_interogation_record')->primary();
            $table->unsignedInteger('id_satker')->nullable();
            $table->uuid('id_case')->nullable();

            $table->text('no_surat')->nullable();
            $table->date('tanggal_surat')->nullable();
            $table->text('perihal')->nullable();
            $table->text('nama_target')->nullable();
            $table->text('nip_nik_target')->nullable();
            $table->text('tipe_target')->nullable();
            $table->text('jenis_kelamin')->nullable();
            $table->text('agama')->nullable();
            $table->text('pekerjaan')->nullable();
            $table->text('pendidikan')->nullable();
            $table->string('foto')->nullable();
            $table->string('upload_berita_acara')->nullable();
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
        Schema::dropIfExists('interogation_record');
    }
};
