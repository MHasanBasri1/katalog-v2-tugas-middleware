<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Notifications\UserResetPasswordNotification;
use App\Notifications\UserVerifyEmailNotification;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'google_avatar',
        'avatar',
        'email_verified_at',
        'is_admin',
        'is_frozen',
        'frozen_at',
        'freeze_reason',
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
            'is_admin' => 'boolean',
            'is_frozen' => 'boolean',
            'frozen_at' => 'datetime',
        ];
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new UserVerifyEmailNotification());
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public static function normalizeEmail(?string $email): string
    {
        return Str::lower(trim((string) $email));
    }

    public function setEmailAttribute(?string $value): void
    {
        $this->attributes['email'] = static::normalizeEmail($value);
    }

    public function scopeWhereEmailInsensitive(Builder $query, string $email): Builder
    {
        return $query->whereRaw('LOWER(email) = ?', [static::normalizeEmail($email)]);
    }

    public function hasUploadedAvatar(): bool
    {
        $avatar = (string) ($this->avatar ?? '');
        if ($avatar === '') {
            return false;
        }

        return ! Str::startsWith($avatar, ['http://', 'https://']);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        $avatar = (string) ($this->avatar ?? '');
        if ($avatar === '') {
            return null;
        }

        if (Str::startsWith($avatar, ['http://', 'https://'])) {
            return $avatar;
        }

        return asset('storage/' . $avatar);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
}
