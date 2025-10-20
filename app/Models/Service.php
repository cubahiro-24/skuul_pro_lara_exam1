<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'nom',
        'description',
        'icone',
    ];

    /**
     * Relation avec les types de services
     */
    public function typeServices(): HasMany
    {
        return $this->hasMany(TypeService::class);
    }
}
