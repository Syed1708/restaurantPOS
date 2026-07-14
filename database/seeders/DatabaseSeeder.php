<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use HasinHayder\Tyro\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Run Tyro's seeders silently
        Artisan::call('tyro:seed-all', ['--force' => true]);

        // 2. Create your custom Super Admin
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@burgerpalace.fr',
            'password' => Hash::make('adminpassword'), 
            'store_id' => null, 
        ]);

        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }

        // 3. Create the Bordeaux Store
        $store = Store::create([
            'name' => 'Burger Palace Bordeaux',
            'address' => '12 Rue Sainte-Catherine',
            'postal_code' => '33000',
            'city' => 'Bordeaux',
            'siret' => '12345678901234',
            'vat_number' => 'FR12345678901',
        ]);

        // 4. Create the Cashier
        $cashier = User::create([
            'name' => 'Cashier One',
            'email' => 'cashier@burgerpalace.fr',
            'password' => Hash::make('password123'),
            'store_id' => $store->id,
        ]);

        $cashierRole = Role::firstOrCreate(
            ['slug' => 'cashier'],
            ['name' => 'Cashier']
        );
        $cashier->assignRole($cashierRole);

        // ======================================================
        // 🍔 MULTIDIMENSIONAL DATASET (12 Categories, 91 Products)
        // ======================================================
        $menuData = [
            [
                'category' => 'Burgers Boeuf', // Hot food -> 10% TVA
                'products' => [
                    ['name' => 'Single Cheeseburger', 'price' => 7.50, 'vat_rate' => 10.00],
                    ['name' => 'Double Cheeseburger', 'price' => 9.50, 'vat_rate' => 10.00],
                    ['name' => 'Triple Cheeseburger', 'price' => 11.50, 'vat_rate' => 10.00],
                    ['name' => 'Classic Bacon Burger', 'price' => 8.90, 'vat_rate' => 10.00],
                    ['name' => 'Double Bacon Burger', 'price' => 11.90, 'vat_rate' => 10.00],
                    ['name' => 'BBQ Smokey Burger', 'price' => 9.20, 'vat_rate' => 10.00],
                    ['name' => 'Monster King Burger', 'price' => 13.50, 'vat_rate' => 10.00],
                    ['name' => 'Egg & Beef Burger', 'price' => 8.80, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Burgers Volaille & Poisson', // Hot food -> 10% TVA
                'products' => [
                    ['name' => 'Crispy Chicken Burger', 'price' => 8.50, 'vat_rate' => 10.00],
                    ['name' => 'Grilled Chicken Burger', 'price' => 8.90, 'vat_rate' => 10.00],
                    ['name' => 'Spicy Chicken Burger', 'price' => 9.00, 'vat_rate' => 10.00],
                    ['name' => 'Chicken Bacon Ranch', 'price' => 9.90, 'vat_rate' => 10.00],
                    ['name' => 'Classic Fish Filet', 'price' => 7.90, 'vat_rate' => 10.00],
                    ['name' => 'Double Fish Filet', 'price' => 9.90, 'vat_rate' => 10.00],
                    ['name' => 'Honey Mustard Chicken', 'price' => 8.90, 'vat_rate' => 10.00],
                    ['name' => 'Sweet Chili Chicken', 'price' => 8.80, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Burgers Veggie', // Hot food -> 10% TVA
                'products' => [
                    ['name' => 'Green Garden Burger', 'price' => 8.20, 'vat_rate' => 10.00],
                    ['name' => 'Spicy Avocado Veggie', 'price' => 9.50, 'vat_rate' => 10.00],
                    ['name' => 'Falafel Pita Burger', 'price' => 8.00, 'vat_rate' => 10.00],
                    ['name' => 'Portobello Mushroom', 'price' => 9.90, 'vat_rate' => 10.00],
                    ['name' => 'Halloumi Honey Burger', 'price' => 9.20, 'vat_rate' => 10.00],
                    ['name' => 'Beyond Meat Classic', 'price' => 10.50, 'vat_rate' => 10.00],
                    ['name' => 'Tofu Teriyaki Burger', 'price' => 8.50, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Frites & Accompagnements', // Hot food -> 10% TVA
                'products' => [
                    ['name' => 'Small French Fries', 'price' => 2.50, 'vat_rate' => 10.00],
                    ['name' => 'Medium French Fries', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Large French Fries', 'price' => 4.50, 'vat_rate' => 10.00],
                    ['name' => 'Small Sweet Potatoes', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Large Sweet Potatoes', 'price' => 5.50, 'vat_rate' => 10.00],
                    ['name' => 'Loaded Cheese Fries', 'price' => 6.90, 'vat_rate' => 10.00],
                    ['name' => 'Onion Rings x6', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Onion Rings x12', 'price' => 5.90, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Nuggets & Wings', // Hot food -> 10% TVA
                'products' => [
                    ['name' => 'Chicken Nuggets x4', 'price' => 2.90, 'vat_rate' => 10.00],
                    ['name' => 'Chicken Nuggets x6', 'price' => 3.90, 'vat_rate' => 10.00],
                    ['name' => 'Chicken Nuggets x9', 'price' => 5.50, 'vat_rate' => 10.00],
                    ['name' => 'Chicken Nuggets x20', 'price' => 10.90, 'vat_rate' => 10.00],
                    ['name' => 'Spicy Chicken Wings x5', 'price' => 4.90, 'vat_rate' => 10.00],
                    ['name' => 'Spicy Chicken Wings x10', 'price' => 8.90, 'vat_rate' => 10.00],
                    ['name' => 'Mozzarella Sticks x5', 'price' => 4.50, 'vat_rate' => 10.00],
                    ['name' => 'Jalapeno Poppers x5', 'price' => 4.90, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Salades Fraîches', // Food -> 10% TVA
                'products' => [
                    ['name' => 'Classic Caesar Salad', 'price' => 8.50, 'vat_rate' => 10.00],
                    ['name' => 'Crispy Caesar Salad', 'price' => 9.90, 'vat_rate' => 10.00],
                    ['name' => 'Greek Feta Salad', 'price' => 7.90, 'vat_rate' => 10.00],
                    ['name' => 'Quinoa Avocado Salad', 'price' => 8.90, 'vat_rate' => 10.00],
                    ['name' => 'Goat Cheese & Honey', 'price' => 8.80, 'vat_rate' => 10.00],
                    ['name' => 'Italian Caprese', 'price' => 8.20, 'vat_rate' => 10.00],
                    ['name' => 'Tuna Nicoise Salad', 'price' => 8.90, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Sauces', // Condiments -> 10% TVA
                'products' => [
                    ['name' => 'Ketchup Cup', 'price' => 0.30, 'vat_rate' => 10.00],
                    ['name' => 'Mayonnaise Cup', 'price' => 0.30, 'vat_rate' => 10.00],
                    ['name' => 'Smokey BBQ Dip', 'price' => 0.40, 'vat_rate' => 10.00],
                    ['name' => 'Spicy Samurai Dip', 'price' => 0.40, 'vat_rate' => 10.00],
                    ['name' => 'Honey Mustard Dip', 'price' => 0.40, 'vat_rate' => 10.00],
                    ['name' => 'Garlic Herb Mayo', 'price' => 0.40, 'vat_rate' => 10.00],
                    ['name' => 'Sweet Chili Dip', 'price' => 0.40, 'vat_rate' => 10.00],
                    ['name' => 'Warm Cheddar Sauce', 'price' => 1.00, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Desserts & Pâtisseries', // Takeaway Cold Sweets -> 5.5% TVA
                'products' => [
                    ['name' => 'Chocolate Chip Cookie', 'price' => 2.00, 'vat_rate' => 5.50],
                    ['name' => 'Double Chocolate Muffin', 'price' => 3.00, 'vat_rate' => 5.50],
                    ['name' => 'Blueberry White Muffin', 'price' => 3.00, 'vat_rate' => 5.50],
                    ['name' => 'Apple Turnover', 'price' => 2.50, 'vat_rate' => 5.50],
                    ['name' => 'Fudge Chocolate Brownie', 'price' => 3.20, 'vat_rate' => 5.50],
                    ['name' => 'Lemon Meringue Slice', 'price' => 3.50, 'vat_rate' => 5.50],
                    ['name' => 'Nutella Glazed Donut', 'price' => 2.20, 'vat_rate' => 5.50],
                    ['name' => 'New York Cheesecake', 'price' => 4.00, 'vat_rate' => 5.50],
                ]
            ],
            [
                'category' => 'Glaces & Shakes', // Prepared dairy cold desserts -> 10% TVA
                'products' => [
                    ['name' => 'Vanilla Soft Serve', 'price' => 2.50, 'vat_rate' => 10.00],
                    ['name' => 'Chocolate Soft Serve', 'price' => 2.50, 'vat_rate' => 10.00],
                    ['name' => 'Caramel Sundae Cup', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Strawberry Sundae Cup', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Classic Vanilla Shake', 'price' => 4.20, 'vat_rate' => 10.00],
                    ['name' => 'Classic Choco Shake', 'price' => 4.20, 'vat_rate' => 10.00],
                    ['name' => 'Classic Strawberry Shake', 'price' => 4.20, 'vat_rate' => 10.00],
                    ['name' => 'Oreo Cookie Shake', 'price' => 4.90, 'vat_rate' => 10.00],
                ]
            ],
            [
                'category' => 'Boissons Gazeuses', // Sugary Sodas -> 20% TVA
                'products' => [
                    ['name' => 'Coca-Cola Classic 33cl', 'price' => 2.50, 'vat_rate' => 20.00],
                    ['name' => 'Coca-Cola Zero 33cl', 'price' => 2.50, 'vat_rate' => 20.00],
                    ['name' => 'Fanta Orange 33cl', 'price' => 2.50, 'vat_rate' => 20.00],
                    ['name' => 'Sprite Lemon 33cl', 'price' => 2.50, 'vat_rate' => 20.00],
                    ['name' => 'Fuze Tea Peach 33cl', 'price' => 2.50, 'vat_rate' => 20.00],
                    ['name' => 'Orangina 33cl', 'price' => 2.60, 'vat_rate' => 20.00],
                    ['name' => 'Schweppes Tonic 33cl', 'price' => 2.60, 'vat_rate' => 20.00],
                    ['name' => 'Dr Pepper 33cl', 'price' => 2.70, 'vat_rate' => 20.00],
                ]
            ],
            [
                'category' => 'Eaux & Jus', // Water (5.5% TVA) vs Juices (20% TVA)
                'products' => [
                    ['name' => 'Evian Still Water 50cl', 'price' => 1.80, 'vat_rate' => 5.50],
                    ['name' => 'Badoit Sparkling 50cl', 'price' => 2.00, 'vat_rate' => 5.50],
                    ['name' => 'Tropicana Orange 25cl', 'price' => 2.80, 'vat_rate' => 20.00],
                    ['name' => 'Tropicana Apple 25cl', 'price' => 2.80, 'vat_rate' => 20.00],
                    ['name' => 'Fresh Lemonade 40cl', 'price' => 3.50, 'vat_rate' => 20.00],
                    ['name' => 'Tomato Juice 25cl', 'price' => 3.00, 'vat_rate' => 20.00],
                    ['name' => 'Coconut Water 33cl', 'price' => 3.20, 'vat_rate' => 5.50],
                ]
            ],
            [
                'category' => 'Café & Boissons Chaudes', // Prepared Hot drinks -> 10% TVA
                'products' => [
                    ['name' => 'Espresso Shot', 'price' => 1.50, 'vat_rate' => 10.00],
                    ['name' => 'Double Espresso', 'price' => 2.20, 'vat_rate' => 10.00],
                    ['name' => 'Americano Black Coffee', 'price' => 2.00, 'vat_rate' => 10.00],
                    ['name' => 'Classic Cafe Latte', 'price' => 3.00, 'vat_rate' => 10.00],
                    ['name' => 'Cappuccino Cup', 'price' => 3.20, 'vat_rate' => 10.00],
                    ['name' => 'Classic Hot Chocolate', 'price' => 3.50, 'vat_rate' => 10.00],
                    ['name' => 'Organic Green Tea', 'price' => 2.50, 'vat_rate' => 10.00],
                    ['name' => 'English Breakfast Tea', 'price' => 2.50, 'vat_rate' => 10.00],
                ]
            ]
        ];

        // 5. Populate categories and products using a fast SQL loop
        foreach ($menuData as $group) {
            $category = Category::create([
                'store_id' => $store->id,
                'name' => $group['category']
            ]);

            foreach ($group['products'] as $product) {
                Product::create([
                    'category_id' => $category->id,
                    'name' => $product['name'],
                    'price' => $product['price'],
                    'vat_rate' => $product['vat_rate'],
                    'is_active' => true,
                ]);
            }
        }

        // 6. Clean up Tyro default admin account
        User::where('email', 'admin@tyro.project')->delete();
    }
}
