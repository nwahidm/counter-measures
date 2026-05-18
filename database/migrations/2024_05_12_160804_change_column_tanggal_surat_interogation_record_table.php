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
        Schema::table('interogation_record', function (Blueprint $table) {
            //
            //$table->date('tanggal_surat')->change();
            DB::statement('ALTER TABLE interogation_record ALTER COLUMN tanggal_surat TYPE date USING tanggal_surat::date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('interogation_record', function (Blueprint $table) {
            //
            // $table->text('tanggal_surat')->nullable()->change();
            DB::statement('ALTER TABLE interogation_record ALTER COLUMN tanggal_surat TYPE text');
        });
    }
};
