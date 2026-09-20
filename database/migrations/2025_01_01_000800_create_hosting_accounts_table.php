<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hosting_accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('server_id')->nullable()->constrained()->nullOnDelete();
            $table->string('cwp_username')->nullable();
            $table->string('cwp_account_id')->nullable();
            $table->string('primary_domain')->nullable();
            $table->string('package_name')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->string('login_url')->nullable();
            $table->string('status', 32)->default('pending');
            $table->timestamp('provisioned_at')->nullable();
            $table->timestamp('suspended_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->unique(['server_id', 'cwp_username']);
            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hosting_accounts');
    }
};
