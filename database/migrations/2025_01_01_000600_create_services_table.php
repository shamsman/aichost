<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('product_plan_id')->nullable()->constrained()->nullOnDelete();
            $table->nullableMorphs('serviceable');
            $table->string('label');
            $table->string('status', 32)->default('pending');
            $table->string('billing_cycle', 32)->default('monthly');
            $table->decimal('amount', 12, 2)->default(0);
            $table->char('currency', 3)->default('USD');
            $table->timestamp('next_due_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'status']);
            $table->index('next_due_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
