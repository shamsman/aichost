<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('provider', 50)->default('cwp');
            $table->string('hostname');
            $table->string('ip_address', 45)->nullable();
            $table->string('api_url');
            $table->text('api_key_encrypted')->nullable();
            $table->text('api_secret_encrypted')->nullable();
            $table->string('region', 64)->nullable();
            $table->unsignedInteger('capacity')->default(0);
            $table->unsignedInteger('used_slots')->default(0);
            $table->string('status', 32)->default('active');
            $table->timestamps();

            $table->unique('hostname');
            $table->index(['provider', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('servers');
    }
};
