<?php

namespace Database\Seeders;

use App\Models\Utilisateur;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUtilisateurSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create an admin user for easy login
        Utilisateur::create([
            'utilisateur' => 'admin',
            'email' => 'admin@gestion-stock.com',
            'motdepasse' => Hash::make('admin123'),
            'role' => 'admin'
        ]);
    }
}
