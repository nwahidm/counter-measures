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
        Schema::create('research_anggota_sprint', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('id_sprint');

            $table->unsignedBigInteger('id_anggota');

            $table->bigInteger('created_by');
            $table->bigInteger('updated_by');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('id_sprint')->references('id')->on('research_sprint');
            $table->foreign('id_anggota')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('research_anggota_sprint');
    }
};
