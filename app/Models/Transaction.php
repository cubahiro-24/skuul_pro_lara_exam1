<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'wallet_id',
        'type',
        'montant',
        'solde_avant',
        'solde_apres',
        'reference',
        'description',
        'statut',
        'rendez_vous_id',
        'methode_rechargement',
        'metadata',
    ];

    protected $casts = [
        'montant' => 'decimal:2',
        'solde_avant' => 'decimal:2',
        'solde_apres' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(Wallet::class);
    }

    public function rendezVous(): BelongsTo
    {
        return $this->belongsTo(RendezVous::class);
    }

    public function getMontantFormateAttribute(): string
    {
        return number_format($this->montant, 0, ',', ' ') . ' FBU';
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'rechargement' => 'green',
            'paiement' => 'red',
            'remboursement' => 'blue',
            'retrait' => 'orange',
            default => 'gray',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'rechargement' => 'Rechargement',
            'paiement' => 'Paiement',
            'remboursement' => 'Remboursement',
            'retrait' => 'Retrait',
            default => 'Transaction',
        };
    }
}
