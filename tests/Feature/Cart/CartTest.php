<?php

namespace Tests\Feature\Cart;

use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CartTest extends TestCase
{
    use RefreshDatabase; // resets DB after each test

    public function test_user_can_add_to_cart()
    {
        // 1️⃣ Create a user
        $user = User::factory()->create();

        // 2️⃣ Create a product
        $product = Product::factory()->create();

        // 3️⃣ Make API request as authenticated user
        $response = $this->actingAs($user, 'sanctum')
            ->postJson('/api/cart/add', [
                'product_id' => $product->id,
                'quantity' => 2,
                'price' => $product->price
            ]);

        // 4️⃣ Assert response status
        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Item added to cart',
                 ]);

        // 5️⃣ Assert database has the cart item
        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 2,
            'price' => $product->price
        ]);
    }

    public function test_guest_can_add_to_cart()
    {
        $product = Product::factory()->create();
        $sessionId = 'guest-session-123';

        $response = $this->postJson('/api/cart/add', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ], [
            'X-Session-Id' => $sessionId
        ]);

        $response->assertStatus(200)
                 ->assertJson([
                     'message' => 'Item added to cart',
                 ]);

        $this->assertDatabaseHas('cart_items', [
            'product_id' => $product->id,
            'quantity' => 1,
            'price' => $product->price
        ]);
    }
}