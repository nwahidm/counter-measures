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
        Schema::create('log_workflow', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('jenis')->comment('AGHT;ETC');
            $table->string('ref_id')->comment('Id related model');
            $table->datetime('action_at')->nullable();
            $table->string('actor_user_id', 100)->nullable();
            $table->string('actor_role', 100)->nullable();
            $table->string('actor_name', 255)->nullable();
            $table->string('actor_id_satker', 25)->nullable();
            $table->string('actor_kode_satker', 50)->nullable();
            $table->string('actor_nama_satker', 255)->nullable();
            $table->string('status', 255)->comment('WAITING_APPROVE, APPROVED, REJECT');
            $table->text('description')->nullable();
            $table->jsonb('old_value')->nullable();
            $table->jsonb('new_value')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->index(['jenis', 'ref_id', 'action_at', 'actor_user_id', 'actor_role', 'status', 'actor_id_satker']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_workflow');
    }
};
