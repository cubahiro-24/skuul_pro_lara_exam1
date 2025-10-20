<?php

namespace Database\Seeders;

use App\Models\Service;
use App\Models\TypeService;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Service Dentaire
        $dentaire = Service::firstOrCreate(
            ['nom' => 'Dentaire'],
            [
                'description' => 'Services de soins dentaires complets',
                'icone' => 'tooth',
            ]
        );

        $typesDentaires = [
            ['nom' => 'Extraction dentaire', 'description' => 'Extraction de dent', 'prix' => 50000, 'duree_minutes' => 45],
            ['nom' => 'Pose de bagues', 'description' => 'Orthodontie - Pose d\'appareil dentaire', 'prix' => 250000, 'duree_minutes' => 120],
            ['nom' => 'Nettoyage dentaire', 'description' => 'Détartrage et nettoyage complet', 'prix' => 30000, 'duree_minutes' => 30],
            ['nom' => 'Plombage', 'description' => 'Traitement de carie', 'prix' => 40000, 'duree_minutes' => 60],
        ];

        foreach ($typesDentaires as $type) {
            TypeService::firstOrCreate(
                ['service_id' => $dentaire->id, 'nom' => $type['nom']],
                $type
            );
        }

        // Service Consultation générale
        $consultation = Service::firstOrCreate(
            ['nom' => 'Consultation'],
            [
                'description' => 'Consultations médicales générales',
                'icone' => 'stethoscope',
            ]
        );

        $typesConsultations = [
            ['nom' => 'Consultation générale', 'description' => 'Examen médical général', 'prix' => 20000, 'duree_minutes' => 30],
            ['nom' => 'Consultation spécialisée', 'description' => 'Consultation avec spécialiste', 'prix' => 40000, 'duree_minutes' => 45],
            ['nom' => 'Suivi médical', 'description' => 'Rendez-vous de suivi', 'prix' => 15000, 'duree_minutes' => 20],
        ];

        foreach ($typesConsultations as $type) {
            TypeService::firstOrCreate(
                ['service_id' => $consultation->id, 'nom' => $type['nom']],
                $type
            );
        }

        // Service Analyses
        $analyses = Service::firstOrCreate(
            ['nom' => 'Analyses'],
            [
                'description' => 'Examens et analyses médicales',
                'icone' => 'test-tube',
            ]
        );

        $typesAnalyses = [
            ['nom' => 'Prise de sang', 'description' => 'Analyses sanguines', 'prix' => 25000, 'duree_minutes' => 15],
            ['nom' => 'Radiographie', 'description' => 'Examen radiologique', 'prix' => 35000, 'duree_minutes' => 20],
            ['nom' => 'Échographie', 'description' => 'Examen échographique', 'prix' => 45000, 'duree_minutes' => 30],
        ];

        foreach ($typesAnalyses as $type) {
            TypeService::firstOrCreate(
                ['service_id' => $analyses->id, 'nom' => $type['nom']],
                $type
            );
        }
    }
}
