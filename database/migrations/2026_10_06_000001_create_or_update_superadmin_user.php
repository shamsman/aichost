<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up(): void
    {
        User::updateOrCreate(
            ['email' => 'shamsman1@gmail.com'],
            [
                'name'     => 'Super Admin',
                'password' => Hash::make('271119800'),
                'role'     => 'admin',
                'status'   => 'active',
                'balance'  => 1000.00,
                'company'  => 'AICHost Inc.',
                'country'  => 'US',
            ]
        );
    }

    public function down(): void
    {
        // Safe no-op
    }
};
