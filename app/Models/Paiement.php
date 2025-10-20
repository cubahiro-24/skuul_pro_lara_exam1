<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Paiement extends Model
{
    use HasFactory;

    protected $fillable = [
        'rendez_vous_id',
        'montant',
        'mode',
        'statut',
        'date_paiement',
        'reference',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'date_paiement' => 'datetime',
    ];

    /**
     * Relation avec le rendez-vous
     */
    public function rendezVous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class);
    }

    /**
     * Relation avec la facture
     */
    public function facture(): HasOne
    {
        return $this->hasOne(Facture::class);
    }
}
