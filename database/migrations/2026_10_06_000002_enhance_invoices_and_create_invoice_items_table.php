<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('client_name')->nullable()->after('user_id');
            $table->string('client_email')->nullable()->after('client_name');
            $table->string('client_phone', 50)->nullable()->after('client_email');
            $table->string('client_company')->nullable()->after('client_phone');
            $table->text('client_address')->nullable()->after('client_company');
            $table->string('discount_type', 20)->default('none')->after('subtotal');
            $table->decimal('discount_value', 12, 2)->default(0)->after('discount_type');
            $table->decimal('discount_amount', 12, 2)->default(0)->after('discount_value');
            $table->decimal('tax_rate', 5, 2)->default(0)->after('discount_amount');
            $table->text('notes')->nullable()->after('paid_at');
            $table->text('terms')->nullable()->after('notes');
            $table->foreignId('created_by')->nullable()->after('terms')->constrained('users')->nullOnDelete();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->string('item_type', 50)->default('custom_service'); // vps, hosting, domain, custom_service, setup_fee
            $table->string('description', 500);
            $table->decimal('quantity', 12, 2)->default(1);
            $table->decimal('unit_price', 12, 2)->default(0);
            $table->decimal('line_total', 12, 2)->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['invoice_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invoice_items');

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['created_by']);
            $table->dropColumn([
                'client_name',
                'client_email',
                'client_phone',
                'client_company',
                'client_address',
                'discount_type',
                'discount_value',
                'discount_amount',
                'tax_rate',
                'notes',
                'terms',
                'created_by',
            ]);
        });
    }
};
