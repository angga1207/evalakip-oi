<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Searchable;
use App\Models\References\Role;
use App\Models\References\Instance;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Searchable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'image',
        'role_id',
        'instance_id',
        'jabatan',
        'no_hp',
        'password',
    ];

    protected $searchable = [
        'name',
        'username',
        'email',
        'jabatan',
        'no_hp',
        'Role.name',
        'Instance.name',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    function Role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    function Instance()
    {
        return $this->belongsTo(Instance::class, 'instance_id');
    }

    function getImageIfError()
    {
        $img = 'https://ui-avatars.com/api/?name=' . urlencode($this->name ?? 'User') .
            '&size=60';
        return asset($img);
    }

    function Instances()
    {
        return $this->belongsToMany(Instance::class, 'user_evaluator', 'user_id', 'instance_id');
    }


    public function canImpersonate()
    {
        return $this->role_id === 1;
    }

    public function canBeImpersonated()
    {
        return in_array($this->role_id, [2, 3, 4]);
    }
}
