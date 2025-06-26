<?php
namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Only create the required user accounts for production
        // No fake data for categories, products or movements
        $this->call([
            AdminUtilisateurSeeder::class, // Create all our users (super_admin, admin, user)
        ]);
    }
}
