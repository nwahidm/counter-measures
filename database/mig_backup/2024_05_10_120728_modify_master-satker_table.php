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
        Schema::table('master_satker', function (Blueprint $table) {
            //
            $table->dropIndex('master_satker_kode_satker_parent_id_index');
        });

        Schema::table('master_satker', function (Blueprint $table) {
            //
            $table->unique('kode_satker', 'master_satker_kode_satker_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('master_satker', function (Blueprint $table) {
            //
            $table->dropUnique('master_satker_kode_satker_unique');
        });

        Schema::table('master_satker', function (Blueprint $table) {
            //
            $table->index('kode_satker', 'master_satker_kode_satker_parent_id_index');
        });
    }
};
