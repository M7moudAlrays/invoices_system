<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles ;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles ;

    protected $fillable = 
    [
        'name',
        'email',
        'password',
        'roles_name',
        'Status'
    ];

    protected $hidden = 
    [
        'password',
        'remember_token',
    ];

    protected $casts = 
    [
        'email_verified_at' => 'datetime',
        'roles_name' => 'array',
    ];

}