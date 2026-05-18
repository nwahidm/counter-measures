<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('users');
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->boolean('is_active')->default(true);
            $table->string('profile', 250)->nullable();
            $table->unsignedInteger('id_satker')->nullable();
            $table->string('kode_satker')->nullable();
            $table->smallInteger('tipe_satker')->nullable();
            $table->string('theme_apps', 25)->default('light');
            $table->string('messenger_color', 50)->nullable();
            $table->string('nip', 250)->nullable();
            $table->string('nik', 250)->nullable();
            $table->jsonb('detail_pegawai')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->string('updated_by_name')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')
                ->onUpdate('cascade')->onDelete('cascade');

            $table->index(['name', 'username', 'email', 'is_active', 'id_satker', 'kode_satker']);
        });
        
        Schema::dropIfExists('uspassword_reset_tokensers');
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::dropIfExists('sessions');
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
}
