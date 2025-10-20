<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Facture extends Model
{
    use HasFactory;

    protected $fillable = [
        'paiement_id',
        'numero_facture',
        'montant_total',
        'date_facture',
        'pdf_url',
    ];

    protected $casts = [
        'montant_total' => 'decimal:2',
        'date_facture' => 'datetime',
    ];

    /**
     * Relation avec le paiement
     */
    public function paiement(): BelongsTo
    {
        return $this->belongsTo(Paiement::class);
    }
}
