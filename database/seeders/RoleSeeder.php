<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'nom' => 'Admin',
                'description' => 'Administrateur système avec tous les privilèges',
            ],
            [
                'nom' => 'Medecin',
                'description' => 'Médecin pouvant gérer les rendez-vous et consultations',
            ],
            [
                'nom' => 'Patient',
                'description' => 'Patient pouvant prendre et consulter ses rendez-vous',
            ],
            [
                'nom' => 'Secretaire',
                'description' => 'Secrétaire gérant les rendez-vous et l\'accueil',
            ],
            [
                'nom' => 'Caissier',
                'description' => 'Caissier gérant les paiements et factures',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['nom' => $role['nom']], $role);
        }
    }
}
