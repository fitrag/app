<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLE_ADMIN = 'admin';
    const ROLE_KREATOR = 'kreator';
    const ROLE_BIASA = 'biasa';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'is_active' => 'boolean',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isKreator()
    {
        return $this->role === self::ROLE_KREATOR;
    }

    public function isBiasa()
    {
        return $this->role === self::ROLE_BIASA;
    }

    public function canWriteNovel()
    {
        return $this->role === self::ROLE_ADMIN || $this->role === self::ROLE_KREATOR;
    }

    public function getStatusBadge()
    {
        if (!$this->is_active) {
            return '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Nonaktif</span>';
        }
        
        switch ($this->role) {
            case self::ROLE_ADMIN:
                return '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-xs">Admin</span>';
            case self::ROLE_KREATOR:
                return '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">Kreator</span>';
            default:
                return '<span class="px-2 py-1 bg-blue-100 text-blue-800 rounded-full text-xs">Pembaca</span>';
        }
    }
}