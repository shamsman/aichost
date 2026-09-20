<?php

namespace Tests\Feature;

use App\Models\ProductPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AICHostFlowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;
    public function test_home_page_renders_successfully(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('AI Cloud Host');
        $response->assertSee('srv.shamsman.com:2083');
    }

    public function test_domain_check_api_works(): void
    {
        $response = $this->getJson('/api/v1/domains/check?domain=neuralinnovate.ai');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'domain',
            'available',
            'price',
            'currency',
        ]);
        $this->assertTrue($response->json('available'));
    }

    public function test_shared_hosting_page_renders(): void
    {
        $response = $this->get('/shared-hosting');
        $response->assertStatus(200);
        $response->assertSee('CWP Shared Hosting');
        $response->assertSee('srv.shamsman.com:2083');
    }

    public function test_vps_hosting_page_renders(): void
    {
        $response = $this->get('/vps-hosting');
        $response->assertStatus(200);
        $response->assertSee('Google VM VPS');
    }

    public function test_cart_and_checkout_and_provisioning_flow(): void
    {
        $plan = ProductPlan::where('slug', 'cwp-starter')->first();
        $this->assertNotNull($plan);

        // Add to cart
        $addResponse = $this->post('/cart/add', [
            'type'          => 'hosting',
            'plan_id'       => $plan->id,
            'billing_cycle' => 'monthly',
            'domain_name'   => 'testclient.ai',
        ]);
        $addResponse->assertRedirect(route('cart.index'));

        // View Cart
        $cartResponse = $this->get('/cart');
        $cartResponse->assertStatus(200);
        $cartResponse->assertSee('testclient.ai');

        // Process Checkout with guest registration
        $checkoutResponse = $this->post('/checkout/process', [
            'name'           => 'Jane Developer',
            'email'          => 'jane.developer@aichost.test',
            'password'       => 'password123',
            'payment_method' => 'credit_card',
        ]);

        $checkoutResponse->assertRedirect(route('dashboard.index'));

        // Verify database records
        $this->assertDatabaseHas('users', ['email' => 'jane.developer@aichost.test']);
        $this->assertDatabaseHas('orders', ['payment_status' => 'paid']);
        $this->assertDatabaseHas('invoices', ['status' => 'paid']);
        $this->assertDatabaseHas('services', ['status' => 'active']);
        $this->assertDatabaseHas('hosting_accounts', ['status' => 'active', 'primary_domain' => 'testclient.ai']);
    }
}
