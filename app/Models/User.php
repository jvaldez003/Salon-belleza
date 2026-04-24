<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
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
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Campos que se pueden asignar masivamente.
     * Añadimos 'role' para controlar los permisos de acceso.
     */
    protected $fillable = [
        'name',     // Nombre completo del usuario
        'email',    // Correo único de acceso
        'password', // Contraseña encriptada
        'role',     // Nivel de acceso: admin, editor, usuario
    ];

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
        'password' => 'hashed', // Asegura que la contraseña se maneje siempre como hash
    ];
}
