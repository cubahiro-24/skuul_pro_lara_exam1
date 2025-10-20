<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TypeService extends Model
{
    use HasFactory;

    protected $fillable = [
        'service_id',
        'nom',
        'description',
        'prix',
        'duree_minutes',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    /**
     * Relation avec le service parent
     */
    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Relation avec les rendez-vous
     */
    public function rendezVous(): HasMany
    {
        return $this->hasMany(RendezVous::class);
    }
}
