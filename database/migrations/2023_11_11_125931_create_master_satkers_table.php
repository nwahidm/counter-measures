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
        Schema::create('master_satker', function (Blueprint $table) {
            $table->id('id_satker');
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('nama_satker', 250);
            $table->smallInteger('tipe_satker');
            $table->string('alamat_satker', 250)->nullable();
            $table->string('telp_satker', 250)->nullable();
            $table->string('kode_satker', 250)->nullable();
            $table->string('provinsi', 250)->nullable();
            $table->string('city', 250)->nullable();
            $table->string('foto_sakter', 250)->nullable();
            $table->string('lat', 250)->nullable();
            $table->string('long', 250)->nullable();
            $table->string('website_satker', 250)->nullable();
            $table->double('rating')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->index(['kode_satker', 'parent_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_satker');
    }
};
