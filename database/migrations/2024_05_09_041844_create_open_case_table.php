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
        Schema::create('open_case', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->unsignedBigInteger('id_satker');

            $table->string('nama_kasus', 400)->nullable();
            $table->date('tanggal_kasus')->nullable();
            $table->text('deskripsi_kasus')->nullable();

            $table->string('nama_target', 400)->nullable();
            $table->string('tipe_identitas', 255)->nullable();
            $table->string('no_identitas', 255)->nullable();
            $table->string('agama', 255)->nullable();
            $table->string('pendidikan', 255)->nullable();
            $table->string('pekerjaan', 255)->nullable();
            $table->text('alamat')->nullable();
            $table->text('foto')->nullable();

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('open_case');
    }
};
