<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Invoice>
 */
class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'client_name' => fake()->name(),
            'client_email' => fake()->safeEmail(),
            'invoice_number' => 'INV-'.date('Ymd').'-'.fake()->unique()->numerify('####'),
            'subtotal' => 50.00,
            'discount_type' => 'none',
            'discount_value' => 0,
            'discount_amount' => 0,
            'tax_rate' => 0,
            'tax_amount' => 0,
            'total' => 50.00,
            'currency' => 'USD',
            'status' => 'unpaid',
            'due_at' => now()->addDays(14),
        ];
    }
}
