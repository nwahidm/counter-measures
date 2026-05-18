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
        if (!Schema::hasTable('research_lapinsus')) {
            Schema::table('research_lapinsus', function (Blueprint $table) {
                //
                $table->text('data_fakta')->change();
                $table->text('telaahan')->change();
                $table->text('kesimpulan')->change();
                $table->text('pendapat')->change();
            });
        }
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('research_lapinsus', function (Blueprint $table) {
            //
            $table->string('data_fakta')->change();
            $table->string('telaahan')->change();
            $table->string('kesimpulan')->change();
            $table->string('pendapat')->change();
        });
    }
};
