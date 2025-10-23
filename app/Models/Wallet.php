<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'solde',
        'is_active',
        'devise',
    ];

    protected $casts = [
        'solde' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected $appends = ['solde_formate'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function credit(float $montant, string $type = 'rechargement', array $metadata = []): Transaction
    {
        $soldeAvant = $this->solde;
        $this->solde += $montant;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'montant' => $montant,
            'solde_avant' => $soldeAvant,
            'solde_apres' => $this->solde,
            'reference' => $this->generateReference($type),
            'description' => $metadata['description'] ?? null,
            'statut' => 'reussi',
            'rendez_vous_id' => $metadata['rendez_vous_id'] ?? null,
            'methode_rechargement' => $metadata['methode_rechargement'] ?? null,
            'metadata' => !empty($metadata) ? json_encode($metadata) : null,
        ]);
    }

    public function debit(float $montant, string $type = 'paiement', array $metadata = []): Transaction
    {
        if ($this->solde < $montant) {
            throw new \Exception('Solde insuffisant');
        }

        $soldeAvant = $this->solde;
        $this->solde -= $montant;
        $this->save();

        return $this->transactions()->create([
            'type' => $type,
            'montant' => $montant,
            'solde_avant' => $soldeAvant,
            'solde_apres' => $this->solde,
            'reference' => $this->generateReference($type),
            'description' => $metadata['description'] ?? null,
            'statut' => 'reussi',
            'rendez_vous_id' => $metadata['rendez_vous_id'] ?? null,
            'metadata' => !empty($metadata) ? json_encode($metadata) : null,
        ]);
    }

    protected function generateReference(string $type): string
    {
        $prefix = match($type) {
            'rechargement' => 'RCH',
            'paiement' => 'PAY',
            'remboursement' => 'RMB',
            'retrait' => 'RET',
            default => 'TRX',
        };

        return $prefix . '-' . strtoupper(uniqid()) . '-' . time();
    }

    public function getSoldeFormateAttribute(): string
    {
        return number_format($this->solde, 0, ',', ' ') . ' FBU';
    }
}
