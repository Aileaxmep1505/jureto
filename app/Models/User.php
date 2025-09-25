<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name','email','password','status','approved_at','avatar_path'
    ];

    protected $hidden = ['password','remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at'       => 'datetime',
        'password'          => 'hashed',
    ];

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar_path && Storage::disk('public')->exists($this->avatar_path)) {
            // Intento 1: URL normal del disco público
            $url = Storage::disk('public')->url($this->avatar_path);

            // Si tu server NO expone /storage (symlink roto), usamos la ruta /media/{path}
            // Detectamos rápido: si la URL generada contiene "/storage/" la dejamos;
            // si tu hosting no la sirve, igual daremos la alternativa estable abajo.
            $ts  = Storage::disk('public')->lastModified($this->avatar_path);
            $alt = route('media.show', ['path' => $this->avatar_path, 'v' => $ts]);

            // Te dejo por defecto la alternativa estable por hosting:
            return $alt; // <- usa la ruta /media/... que SIEMPRE funciona
            // Si prefieres el symlink, comenta la línea anterior y descomenta:
            // return $url . '?v=' . $ts;
        }

        $hash = md5(strtolower(trim($this->email ?? '')));
        return "https://www.gravatar.com/avatar/{$hash}?s=300&d=mp";
    }
}
