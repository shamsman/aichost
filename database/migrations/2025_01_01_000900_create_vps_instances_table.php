<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vps_instances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->string('gcp_project')->nullable();
            $table->string('gcp_zone', 64)->nullable();
            $table->string('instance_name')->nullable()->unique();
            $table->string('machine_type', 64)->nullable();
            $table->unsignedInteger('disk_gb')->default(0);
            $table->string('external_ip', 45)->nullable();
            $table->string('internal_ip', 45)->nullable();
            $table->string('status', 32)->default('pending');
            $table->boolean('cwp_installed')->default(false);
            $table->string('cwp_url')->nullable();
            $table->json('specs')->nullable();
            $table->timestamp('provisioned_at')->nullable();
            $table->json('meta')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('machine_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vps_instances');
    }
};
