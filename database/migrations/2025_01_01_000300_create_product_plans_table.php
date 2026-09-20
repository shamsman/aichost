<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_plans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('slug')->unique();
            $table->string('name');
            $table->json('specs')->nullable();
            $table->decimal('price_monthly', 12, 2)->default(0);
            $table->decimal('price_annually', 12, 2)->nullable();
            $table->decimal('setup_fee', 12, 2)->default(0);
            $table->char('currency', 3)->default('USD');
            $table->string('provider_type', 50)->default('cwp');
            $table->json('provider_config')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['product_id', 'is_active']);
            $table->index('provider_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_plans');
    }
};
