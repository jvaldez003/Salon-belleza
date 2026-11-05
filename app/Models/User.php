<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Modelo User
 * 
 * Gestiona la identidad de los usuarios, sus credenciales y sus niveles de permiso.
 * Implementa la lógica de autenticación base de Laravel Breeze.
 */
class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos que se pueden asignar masivamente.
     * Añadimos 'role' para controlar los permisos de acceso.
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'telefono',
        'activo',
    ];

    /** Roles con el nombre y la explicación que ve el administrador. */
    public const ROLES = [
        'usuario' => ['nombre' => 'Cliente',       'detalle' => 'Reserva citas y ve las suyas.'],
        'editor'  => ['nombre' => 'Recepción',     'detalle' => 'Ve la agenda y gestiona las citas de todos los clientes.'],
        'admin'   => ['nombre' => 'Administrador', 'detalle' => 'Todo lo anterior, más servicios, equipo, usuarios, reportes y configuración del sitio.'],
    ];

    public function getRolNombreAttribute(): string
    {
        return self::ROLES[$this->role]['nombre'] ?? ucfirst((string) $this->role);
    }

    /**
     * Helper: Verifica si el usuario es Administrador.
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Helper: Verifica si el usuario es Editor.
     */
    public function isEditor()
    {
        return $this->role === 'editor';
    }

    /**
     * Helper: Verifica si el usuario es un Usuario estándar (Solo lectura).
     */
    public function isUsuario()
    {
        return $this->role === 'usuario';
    }

    /**
     * Campos que se ocultan al convertir el modelo a JSON o Array (Seguridad).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Formateo automático de tipos de datos al recuperar de la base de datos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'activo' => 'boolean',
    ];

    public function citas()
    {
        return $this->hasMany(Cita::class);
    }

    public function resenas()
    {
        return $this->hasMany(Resena::class);
    }

    public function isActivo(): bool
    {
        return (bool) $this->activo;
    }

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new \App\Notifications\VerifyEmailNotification);
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }
}
