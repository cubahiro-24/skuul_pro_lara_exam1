<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Appeler les seeders dans l'ordre
        $this->call([
            RoleSeeder::class,
            ServiceSeeder::class,
            MenuSeeder::class,
        ]);

        // Créer des utilisateurs de test pour chaque rôle
        $roles = Role::all();

        $testUsers = [
            'Admin' => ['email' => 'admin@hospital.com', 'password' => 'admin123'],
            'Medecin' => ['email' => 'medecin@hospital.com', 'password' => 'medecin123'],
            'Patient' => ['email' => 'patient@hospital.com', 'password' => 'patient123'],
            'Secretaire' => ['email' => 'secretaire@hospital.com', 'password' => 'secretaire123'],
            'Caissier' => ['email' => 'caissier@hospital.com', 'password' => 'caissier123'],
        ];

        foreach ($roles as $role) {
            $userData = $testUsers[$role->nom] ?? [
                'email' => strtolower($role->nom) . '@hospital.com',
                'password' => 'password123'
            ];

            User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $role->nom,
                    'prenom' => 'Test',
                    'password' => Hash::make($userData['password']),
                    'telephone' => '0123456789',
                    'adresse' => '123 Rue de l\'Hôpital',
                    'role_id' => $role->id,
                    'statut' => 'actif',
                ]
            );
        }
    }
}
