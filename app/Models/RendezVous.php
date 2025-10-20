<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RendezVous extends Model
{
    use HasFactory;

    protected $table = 'rendez_vous';

    protected $fillable = [
        'utilisateur_id',
        'medecin_id',
        'type_service_id',
        'date_rdv',
        'heure_rdv',
        'statut',
        'notes',
    ];

    protected $casts = [
        'date_rdv' => 'date',
        'heure_rdv' => 'datetime:H:i',
    ];

    /**
     * Relation avec l'utilisateur (patient)
     */
    public function utilisateur(): BelongsTo
    {
        return $this->belongsTo(User::class, 'utilisateur_id');
    }

    /**
     * Relation avec le médecin
     */
    public function medecin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'medecin_id');
    }

    /**
     * Relation avec le type de service
     */
    public function typeService(): BelongsTo
    {
        return $this->belongsTo(TypeService::class);
    }

    /**
     * Relation avec les paiements
     */
    public function paiements(): HasMany
    {
        return $this->hasMany(Paiement::class);
    }
}
