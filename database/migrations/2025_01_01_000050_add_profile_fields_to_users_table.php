<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone', 30)->nullable()->after('password');
            $table->string('company')->nullable()->after('phone');
            $table->string('address')->nullable()->after('company');
            $table->string('country', 10)->default('US')->after('address');
            $table->string('role', 30)->default('customer')->after('country');
            $table->string('status', 30)->default('active')->after('role');
            $table->decimal('balance', 12, 2)->default(0.00)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'company', 'address', 'country', 'role', 'status', 'balance']);
        });
    }
};
