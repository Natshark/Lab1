<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\DTO\RoleCollectionDTO;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'username',
        'email',
        'password',
        'birthday',
        'deleted_at',
        'deleted_by',
        'tfa_code',
        'tfa_code_valid_until',
        'tfa_code_count',
        'delay_until',
    ];

    protected $dates = [
        'created_at',
        'deleted_at',
        'tfa_code_valid_until',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function roles()
    {
        return RoleCollectionDTO::fromCollectionToDTO($this->belongsToMany(Role::class, 'users_and_roles',
            'user_id', 'role_id')->get());
    }
}
