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
        Schema::create('wilayah_satker', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_satker');
            $table->unsignedInteger('id_wilayah');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->index(['id_satker', 'id_wilayah']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wilayah_satker');
    }
};
