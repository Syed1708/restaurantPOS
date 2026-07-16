<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use HasinHayder\Tyro\Concerns\HasTyroRoles;
use HasinHayder\TyroLogin\Traits\HasTwoFactorAuth;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use HasApiTokens, HasTyroRoles, HasTwoFactorAuth;


    /** @use HasFactory<UserFactory> */
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
        'store_id', // Add this to fillable
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


        // Get the store this user belongs to
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }




       /**
     * The "booted" method of the User model.
     */
    protected static function booted(): void
    {
        // 🚀 THE LIFECYCLE INTERCEPTOR:
        // Whenever a user is being saved (either created or updated),
        // automatically grab 'store_id' from the HTTP request and set it on the user object!
        // Because store_id is a custom column we added, Tyro's internal packaged UserController does not expect it or validate it inside its hardcoded save methods, so it got stripped out during the save request.
       static::saving(function ($user) {
            // 1. Gather submitted role IDs
            $requestedRoleIds = (array) request()->input('roles', request()->input('role_id', []));
            
            // 2. 🚀 Gather the submitted store ID (Defaults to 'NOT_PRESENT' if the input was not sent)
            $submittedStoreId = request()->input('store_id', 'NOT_PRESENT_IN_REQUEST');

            // Write both to your storage/logs/laravel.log
            logger("Requested Role IDs: " . implode(', ', $requestedRoleIds) . " | Submitted Store ID: " . $submittedStoreId);

            // 3. Query the Roles table to see if any of those IDs belong to 'superadmin'
            $assigningSuperAdmin = \HasinHayder\Tyro\Models\Role::whereIn('id', $requestedRoleIds)
                ->where('slug', 'superadmin')
                ->exists();

            if ($assigningSuperAdmin) {
                $user->store_id = null; // Forces NULL for superadmins
            } elseif (request()->has('store_id')) {
                $user->store_id = request()->input('store_id') ?: null;
            }
        });
    }

}
