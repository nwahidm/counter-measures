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
        Schema::create('clients', function (Blueprint $table) {
            $table->string('client_id', 100)->primary();
            $table->string('client_username', 150);
            $table->string('client_password', 250);
            $table->string('client_key', 250);
            $table->string('client_name', 250);
            $table->jsonb('whitelist_ip')->nullable();
            $table->boolean('is_active')->default(true);
            $table->datetime('expired_at')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->index('client_username', 'is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};
