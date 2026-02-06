<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Notifications\ResetPasswordNotification;
use PragmaRX\Google2FA\Google2FA;

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
        'email',
        'password',
        'is_admin',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'two_factor_confirmed_at',
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
            'last_login_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
            'two_factor_confirmed_at' => 'datetime',
            'two_factor_recovery_codes' => 'array',
        ];
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole(string $roleName): bool
    {
        return $this->roles()->where('name', $roleName)->exists();
    }

    /**
     * Check if user has any of the given roles
     */
    public function hasAnyRole(array $roleNames): bool
    {
        return $this->roles()->whereIn('name', $roleNames)->exists();
    }

    /**
     * Check if user has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        return $this->roles()->get()->some(function ($role) use ($permission) {
            return $role->hasPermission($permission);
        });
    }

    /**
     * Check if user is super admin
     */
    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super_admin');
    }

    /**
     * Assign role to user
     */
    public function assignRole(string $roleName): void
    {
        $role = Role::getByName($roleName);
        if ($role && !$this->hasRole($roleName)) {
            $this->roles()->attach($role->id);
        }
    }

    /**
     * Remove role from user
     */
    public function removeRole(string $roleName): void
    {
        $role = Role::getByName($roleName);
        if ($role) {
            $this->roles()->detach($role->id);
        }
    }

    /**
     * Check if user has 2FA enabled
     */
    public function hasTwoFactorEnabled(): bool
    {
        return !is_null($this->two_factor_secret) && !is_null($this->two_factor_confirmed_at);
    }

    /**
     * Enable 2FA and generate secret
     */
    public function enableTwoFactorAuth(): string
    {
        $google2fa = new Google2FA();
        $this->two_factor_secret = encrypt($google2fa->generateSecretKey());
        $this->two_factor_recovery_codes = encrypt(json_encode($this->generateRecoveryCodes()));
        $this->save();
        
        return $google2fa->getQRCodeUrl(
            config('app.name', 'Admin Panel'),
            $this->email,
            $this->getTwoFactorSecret()
        );
    }

    /**
     * Get decrypted two factor secret
     */
    public function getTwoFactorSecret(): string
    {
        return decrypt($this->two_factor_secret);
    }

    /**
     * Verify 2FA code
     */
    public function verifyTwoFactorCode(string $code): bool
    {
        if (!$this->hasTwoFactorEnabled()) {
            return false;
        }

        $google2fa = new Google2FA();
        return $google2fa->verifyKey($this->getTwoFactorSecret(), $code);
    }

    /**
     * Confirm 2FA setup
     */
    public function confirmTwoFactorAuth(string $code): bool
    {
        // During setup, two_factor_confirmed_at is null, so we need to verify directly
        if (!$this->two_factor_secret) {
            return false;
        }

        $google2fa = new Google2FA();
        $secret = $this->getTwoFactorSecret();
        
        // Verify the code
        if ($google2fa->verifyKey($secret, $code)) {
            $this->two_factor_confirmed_at = now();
            $this->save();
            return true;
        }
        
        return false;
    }

    /**
     * Disable 2FA
     */
    public function disableTwoFactorAuth(): void
    {
        $this->two_factor_secret = null;
        $this->two_factor_recovery_codes = null;
        $this->two_factor_confirmed_at = null;
        $this->save();
    }

    /**
     * Generate recovery codes
     */
    protected function generateRecoveryCodes(int $count = 8): array
    {
        $codes = [];
        for ($i = 0; $i < $count; $i++) {
            $codes[] = strtoupper(bin2hex(random_bytes(4)));
        }
        return $codes;
    }

    /**
     * Get recovery codes
     */
    public function getRecoveryCodes(): array
    {
        if (!$this->two_factor_recovery_codes) {
            return [];
        }
        return json_decode(decrypt($this->two_factor_recovery_codes), true);
    }

    /**
     * Use recovery code
     */
    public function useRecoveryCode(string $code): bool
    {
        $codes = $this->getRecoveryCodes();
        $key = array_search($code, $codes);
        
        if ($key !== false) {
            unset($codes[$key]);
            $this->two_factor_recovery_codes = encrypt(json_encode(array_values($codes)));
            $this->save();
            return true;
        }
        
        return false;
    }
}
