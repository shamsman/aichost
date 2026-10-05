<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminInvoiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_non_admin_cannot_access_issue_invoices_module(): void
    {
        $user = User::factory()->create(['role' => 'client']);

        $response = $this->actingAs($user)->get(route('admin.invoices.index'));
        $response->assertStatus(403);
    }

    public function test_admin_can_view_invoices_index_and_create_page(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->get(route('admin.invoices.index'));
        $response->assertStatus(200);
        $response->assertSee('Issue & Manage Invoices', false);

        $response = $this->actingAs($admin)->get(route('admin.invoices.create'));
        $response->assertStatus(200);
        $response->assertSee('Issue New Invoice', false);
    }

    public function test_admin_can_issue_invoice_with_line_items_and_discounts(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $client = User::factory()->create([
            'name'  => 'Acme Client',
            'email' => 'client@acme.test',
        ]);

        $postData = [
            'user_id'        => $client->id,
            'client_name'    => 'Acme Client',
            'client_email'   => 'client@acme.test',
            'invoice_number' => 'INV-TEST-0001',
            'currency'       => 'USD',
            'status'         => 'unpaid',
            'discount_type'  => 'fixed',
            'discount_value' => 10.00,
            'tax_rate'       => 5.00,
            'items'          => [
                [
                    'item_type'   => 'vps',
                    'description' => 'Google Cloud VPS E2-Standard-4',
                    'quantity'    => 1,
                    'unit_price'  => 100.00,
                ],
                [
                    'item_type'   => 'hosting',
                    'description' => 'CWP Web Hosting Addon',
                    'quantity'    => 2,
                    'unit_price'  => 25.00,
                ],
            ],
        ];

        $response = $this->actingAs($admin)->post(route('admin.invoices.store'), $postData);
        $response->assertRedirect(route('admin.invoices.index'));

        // Subtotal = 100 + 50 = 150
        // Discount = 10 -> Subtotal after discount = 140
        // Tax = 5% of 140 = 7
        // Total = 147
        $this->assertDatabaseHas('invoices', [
            'invoice_number'  => 'INV-TEST-0001',
            'subtotal'        => 150.00,
            'discount_amount' => 10.00,
            'tax_amount'      => 7.00,
            'total'           => 147.00,
            'status'          => 'unpaid',
        ]);

        $this->assertDatabaseCount('invoice_items', 2);
    }

    public function test_admin_can_mark_invoice_as_paid(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $invoice = Invoice::factory()->create([
            'status' => 'unpaid',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.invoices.mark-paid', $invoice->id));
        $response->assertStatus(302);

        $this->assertEquals('paid', $invoice->fresh()->status);
        $this->assertNotNull($invoice->fresh()->paid_at);
    }
}
