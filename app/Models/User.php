<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', 
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

    // --- HELPER METHOD UNTUK ROLE (Sangat berguna untuk keamanan) ---
    public function isAdmin(): bool {
        return $this->role === 'admin';
    }

    public function isStaff(): bool {
        return $this->role === 'staff';
    }

    public function isViewer(): bool {
        return $this->role === 'viewer';
    }

    // Relasi: Satu user bisa mengupload banyak dokumen
    public function documents()
    {
        return $this->hasMany(Document::class, 'uploaded_by');
    }
}