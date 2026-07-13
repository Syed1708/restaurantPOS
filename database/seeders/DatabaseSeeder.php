<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Store;
use App\Models\User;
use App\Models\Category;
use App\Models\Product;
use HasinHayder\Tyro\Models\Role; // <-- Import the Tyro Role model here!
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        
        // 1. Run Tyro's complete seeder silently using the --force flag
        Artisan::call('tyro:seed-all', ['--force' => true]);

        // 2. Create the Super Admin (Has access to the Tyro Dashboard)
        $admin = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@burgerpalace.fr',
            'password' => Hash::make('adminpassword'), 
            'store_id' => null, 
        ]);

        // Retrieve Tyro's seeded 'super-admin' role object and assign it safely
        $superAdminRole = Role::where('slug', 'super-admin')->first();
        if ($superAdminRole) {
            $admin->assignRole($superAdminRole);
        }

        // 3. Create a Test Store
        $store = Store::create([
            'name' => 'Burger Palace Bordeaux',
            'address' => '12 Rue Sainte-Catherine',
            'postal_code' => '33000',
            'city' => 'Bordeaux',
            'siret' => '12345678901234',
            'vat_number' => 'FR12345678901',
        ]);

        // 4. Create a Cashier User linked to this store
        $cashier = User::create([
            'name' => 'Cashier One',
            'email' => 'cashier@burgerpalace.fr',
            'password' => Hash::make('password123'),
            'store_id' => $store->id,
        ]);

        // Safely fetch or create the 'cashier' role object and assign it
        $cashierRole = Role::firstOrCreate(
            ['slug' => 'cashier'],
            ['name' => 'Cashier']
        );
        $cashier->assignRole($cashierRole);

        // 5. Create Categories
        $burgersCategory = Category::create(['store_id' => $store->id, 'name' => 'Burgers']);
        $drinksCategory = Category::create(['store_id' => $store->id, 'name' => 'Drinks']);

        // 6. Create Products
        Product::create([
            'category_id' => $burgersCategory->id,
            'name' => 'Classic Cheeseburger',
            'price' => 8.50,
            'vat_rate' => 10.00,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $burgersCategory->id,
            'name' => 'Double Bacon Burger',
            'price' => 11.50,
            'vat_rate' => 10.00,
            'is_active' => true,
        ]);

        Product::create([
            'category_id' => $drinksCategory->id,
            'name' => 'Coca-Cola 33cl',
            'price' => 2.50,
            'vat_rate' => 20.00,
            'is_active' => true,
        ]);


        // 7. Remove Tyro's default bootstrap user to keep the database clean
        User::where('email', 'admin@tyro.project')->delete();
    }
}
