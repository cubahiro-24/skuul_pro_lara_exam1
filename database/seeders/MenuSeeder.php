<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Role;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = Role::all()->pluck('id', 'nom');

        $menus = [
            // Menu Admin
            [
                'titre' => 'Dashboard',
                'lien' => '/dashboard',
                'icone' => 'layout-dashboard',
                'ordre' => 1,
                'visible_pour' => [$roles['Admin']],
            ],
            [
                'titre' => 'Utilisateurs',
                'lien' => '/admin/utilisateurs',
                'icone' => 'users',
                'ordre' => 2,
                'visible_pour' => [$roles['Admin']],
            ],
            [
                'titre' => 'Services',
                'lien' => '/admin/services',
                'icone' => 'briefcase-medical',
                'ordre' => 3,
                'visible_pour' => [$roles['Admin']],
            ],
            [
                'titre' => 'Rendez-vous',
                'lien' => '/admin/rendez-vous',
                'icone' => 'calendar',
                'ordre' => 4,
                'visible_pour' => [$roles['Admin'], $roles['Secretaire']],
            ],
            [
                'titre' => 'Paiements',
                'lien' => '/admin/paiements',
                'icone' => 'credit-card',
                'ordre' => 5,
                'visible_pour' => [$roles['Admin'], $roles['Caissier']],
            ],
            [
                'titre' => 'Rapports',
                'lien' => '/admin/rapports',
                'icone' => 'chart-line',
                'ordre' => 6,
                'visible_pour' => [$roles['Admin']],
            ],
            
            // Menu Médecin
            [
                'titre' => 'Mes Rendez-vous',
                'lien' => '/medecin/rendez-vous',
                'icone' => 'calendar-check',
                'ordre' => 1,
                'visible_pour' => [$roles['Medecin']],
            ],
            [
                'titre' => 'Mes Patients',
                'lien' => '/medecin/patients',
                'icone' => 'user-group',
                'ordre' => 2,
                'visible_pour' => [$roles['Medecin']],
            ],
            
            // Menu Patient
            [
                'titre' => 'Prendre RDV',
                'lien' => '/patient/rendez-vous/create',
                'icone' => 'calendar-plus',
                'ordre' => 1,
                'visible_pour' => [$roles['Patient']],
            ],
            [
                'titre' => 'Mes Rendez-vous',
                'lien' => '/patient/rendez-vous',
                'icone' => 'calendar',
                'ordre' => 2,
                'visible_pour' => [$roles['Patient']],
            ],
            [
                'titre' => 'Mon Portefeuille',
                'lien' => '/patient/wallet',
                'icone' => 'wallet',
                'ordre' => 3,
                'visible_pour' => [$roles['Patient']],
            ],
            [
                'titre' => 'Mes Paiements',
                'lien' => '/patient/paiements',
                'icone' => 'credit-card',
                'ordre' => 4,
                'visible_pour' => [$roles['Patient']],
            ],
            [
                'titre' => 'Mes Factures',
                'lien' => '/patient/factures',
                'icone' => 'file-invoice',
                'ordre' => 5,
                'visible_pour' => [$roles['Patient']],
            ],
        ];

        foreach ($menus as $menu) {
            Menu::firstOrCreate(
                ['lien' => $menu['lien']],
                $menu
            );
        }
    }
}
