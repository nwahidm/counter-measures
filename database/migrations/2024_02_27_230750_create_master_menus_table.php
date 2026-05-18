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
        Schema::create('master_menu', function (Blueprint $table) {
            $table->id();
            $table->string('group', 250)->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('name', 150);
            $table->string('description', 250)->nullable();
            $table->string('route_name', 250)->nullable();
            $table->string('route_url', 250)->nullable();
            $table->jsonb('asset')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->index(['group', 'parent_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_menu');
    }
};
