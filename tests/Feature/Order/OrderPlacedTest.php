<?php
namespace Tests\Feature\Order;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OrderPlacedTest extends TestCase
{
    use RefreshDatabase; // resets DB after each test
    
    public function test_user_can_place_order()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user, 'sanctum')->postJson('/api/orders', [
            'items' => [
                [
                    'product_id' => $product->id,
                    'quantity' => 2
                ]
            ]
        ]);

        //dd($response->json());

        $response->assertJsonStructure([
            'success',
            'message',
            'data' => ['id', 'total']
        ]);
    }
}