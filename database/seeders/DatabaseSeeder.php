<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\{
    User, Vendor, Category, Product,
    Cart, CartItem, Order, OrderItem
};

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1️⃣ Users
        $users = User::factory(5)->create();

        // 2️⃣ Vendors (linked to users)
        $vendors = Vendor::factory(2)->make()->each(function ($vendor) use ($users) {
            $vendor->user_id = $users->random()->id;
            $vendor->save();
        });

        /*
        |--------------------------------------------------------------------------
        | 1️⃣ Parent Categories
        |--------------------------------------------------------------------------
        */
        $grocery = Category::create([
            'name' => 'Grocery',
            'slug' => 'grocery',
        ]);

        $electronics = Category::create([
            'name' => 'Electronics',
            'slug' => 'electronics',
        ]);

        /*
        |--------------------------------------------------------------------------
        | 2️⃣ Subcategories (with parent_id)
        |--------------------------------------------------------------------------
        */
        $subCategories = [
            // Grocery
            [
                'parent' => $grocery,
                'name' => 'Fruits',
                'products' => ['Apple', 'Banana', 'Orange', 'Mango']
            ],
            [
                'parent' => $grocery,
                'name' => 'Vegetables',
                'products' => ['Potato', 'Tomato', 'Onion', 'Carrot']
            ],
            [
                'parent' => $grocery,
                'name' => 'Dairy',
                'products' => ['Milk', 'Butter', 'Cheese', 'Curd']
            ],

            // Electronics
            [
                'parent' => $electronics,
                'name' => 'Mobiles',
                'products' => ['iPhone', 'Samsung Galaxy', 'OnePlus', 'Pixel']
            ],
            [
                'parent' => $electronics,
                'name' => 'Laptops',
                'products' => ['MacBook', 'Dell XPS', 'HP Pavilion', 'Lenovo ThinkPad']
            ],
            [
                'parent' => $electronics,
                'name' => 'Accessories',
                'products' => ['Headphones', 'Charger', 'Power Bank', 'Bluetooth Speaker']
            ],
        ];

        $categorySuffixes = [
            'Grocery' => ['Fresh', 'Organic', 'Premium'],
            'Electronics' => ['Pro', '2026 Edition', 'Deluxe'],
        ];

        /*
        |--------------------------------------------------------------------------
        | 3️⃣ Create Subcategories + 4️⃣ Products
        |--------------------------------------------------------------------------
        */
        $products = collect();

        foreach ($subCategories as $item) {

            $suffixes = $categorySuffixes[$item['parent']->name] ?? [];
            $suffix = $suffixes ? fake()->randomElement($suffixes) : '';

            $category = Category::create([
                'name' => $item['name'],
                'slug' => Str::slug($item['name']),
                'parent_id' => $item['parent']->id,
            ]);

            foreach ($item['products'] as $productName) {

                $product = Product::factory()->create([
                    'name' => $productName . ($suffix ? " $suffix" : ''), // append suffix if exists
                    'slug' => Str::slug($productName . '-' . rand(100, 999)),
                    'category_id' => $category->id,
                    'vendor_id' => $vendors->random()->id,
                    'price' => rand(50, 5000),
                    'stock' => rand(1, 100),
                ]);

                $products->push($product);
            }
        }

        // 5️⃣ Carts
        $carts = Cart::factory(5)->make()->each(function ($cart) use ($users) {
            $cart->user_id = $users->random()->id;
            $cart->save();
        });

        // 6️⃣ Cart Items
        CartItem::factory(15)->make()->each(function ($item) use ($carts, $products) {
            $item->cart_id = $carts->random()->id;
            $item->product_id = $products->random()->id;
            $item->save();
        });

        // 7️⃣ Orders
        $orders = Order::factory(10)->make()->each(function ($order) use ($users) {
            $order->user_id = $users->random()->id;
            $order->save();
        });

        // 8️⃣ Order Items + total calculation
        foreach ($orders as $order) {
            $items = OrderItem::factory(rand(1, 3))->make();

            $total = 0;

            foreach ($items as $item) {
                $product = $products->random();

                $item->order_id = $order->id;
                $item->product_id = $product->id;
                $item->price = $product->price;
                $item->save();

                $total += $item->price * $item->quantity;
            }

            $order->update(['total' => $total]);
        }
    }
}
