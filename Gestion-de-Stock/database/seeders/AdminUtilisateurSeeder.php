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
        // Create a super_admin user
        Utilisateur::create([
            'utilisateur' => 'Super Administrateur',
            'email' => 'superadmin@gmail.com',
            'motdepasse' => Hash::make('11111111'),
            'role' => 'super_admin'
        ]);
        
        // Create an admin user
        Utilisateur::create([
            'utilisateur' => 'Administrateur',
            'email' => 'admin@gmail.com',
            'motdepasse' => Hash::make('11111111'),
            'role' => 'admin'
        ]);
        
        // Create a regular user
        Utilisateur::create([
            'utilisateur' => 'Utilisateur',
            'email' => 'user@gmail.com',
            'motdepasse' => Hash::make('11111111'),
            'role' => 'user'
        ]);
    }
}
