<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'prenom',
        'email',
        'password',
        'telephone',
        'adresse',
        'role_id',
        'statut',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relation avec le rôle
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Rendez-vous en tant que patient
     */
    public function rendezVousPatient(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'utilisateur_id');
    }

    /**
     * Rendez-vous en tant que médecin
     */
    public function rendezVousMedecin(): HasMany
    {
        return $this->hasMany(RendezVous::class, 'medecin_id');
    }

    /**
     * Portefeuille virtuel de l'utilisateur
     */
    public function wallet(): HasOne
    {
        return $this->hasOne(Wallet::class);
    }

    /**
     * Obtenir ou créer le wallet de l'utilisateur
     * Seuls les patients peuvent avoir un wallet
     */
    public function getOrCreateWallet(): ?Wallet
    {
        // Vérifier si l'utilisateur est un patient
        if (!$this->hasRole('Patient')) {
            return null;
        }

        return $this->wallet ?? $this->wallet()->create([
            'solde' => 0,
            'is_active' => true,
            'devise' => 'FBU',
        ]);
    }

    /**
     * Logs d'audit
     */
    public function auditLogs(): HasMany
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Vérifier si l'utilisateur a un rôle spécifique
     */
    public function hasRole(string $roleName): bool
    {
        return $this->role?->nom === $roleName;
    }

    /**
     * Vérifier si l'utilisateur est actif
     */
    public function isActive(): bool
    {
        return $this->statut === 'actif';
    }
}
