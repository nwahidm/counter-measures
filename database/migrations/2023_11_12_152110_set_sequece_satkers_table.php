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
        DB::statement('SELECT setval(\'master_satker_id_satker_seq\', 536)');
        DB::statement('SELECT setval(\'master_wilayah_id_wilayah_seq\', 91589)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
