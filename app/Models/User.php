<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'nomor_induk', 'photo_profile'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, HasRoles, Notifiable;

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

    public function mahasiswaProfile(): HasOne
    {
        return $this->hasOne(MahasiswaProfile::class);
    }

    public function mahasiswaProfilesDibimbing(): HasMany
    {
        return $this->hasMany(MahasiswaProfile::class, 'dosen_pembimbing_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(Milestone::class, 'mahasiswa_id');
    }

    public function progressSkripsi(): HasMany
    {
        return $this->hasMany(ProgressSkripsi::class, 'mahasiswa_id');
    }

    public function isMahasiswa(): bool
    {
        return $this->hasRole('mahasiswa');
    }

    public function isDosen(): bool
    {
        return $this->hasRole('dosen');
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function supervises(MahasiswaProfile $profile): bool
    {
        if ($this->isAdmin() && ! $this->isDosen()) {
            return true;
        }

        return $this->isDosen()
            && (int) $profile->dosen_pembimbing_id === (int) $this->id;
    }
}
