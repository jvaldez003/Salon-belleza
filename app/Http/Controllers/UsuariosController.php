<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller;

/**
 * Controlador UsuariosController
 * 
 * Centraliza la administración de los usuarios del sistema.
 * Implementa el Control de Acceso Basado en Roles (RBAC) definido en el manual.
 */
class UsuariosController extends Controller
{
    /**
     * Muestra la tabla principal de usuarios.
     * Solo accesible por administradores.
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(function ($q) use ($buscar) {
                $q->where('name', 'like', "%{$buscar}%")
                    ->orWhere('email', 'like', "%{$buscar}%")
                    ->orWhere('role', 'like', "%{$buscar}%");
            });
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $users = $query->orderBy('name')->get();

        return view('usuario.index', compact('users'));
    }

    /**
     * Muestra el formulario para crear un nuevo usuario.
     * El acceso a este método está restringido solo a Administradores en web.php.
     */
    public function create()
    {
        return view('usuario.create');
    }

    /**
     * Procesa el registro de un nuevo usuario en la base de datos.
     * Encripta la contraseña usando Bcrypt para seguridad.
     */
    public function store(Request $request)
    {
        // Validación estricta de datos de usuario
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
            'role' => 'required|in:admin,editor,usuario', // Solo acepta estos 3 roles
        ]);

        User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'role' => $request['role'],
            'email_verified_at' => now(),
        ]);

        return redirect('/usuario')->with('success', 'Usuario creado exitosamente');
    }

    /**
     * Muestra el detalle de un usuario específico.
     * En la versión actual, esta información se muestra mediante un Slide-over en el index,
     * pero esta ruta se mantiene para compatibilidad y acceso directo.
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('usuario.show', ['user' => $user]);
    }

    /**
     * Muestra el formulario para editar datos de un usuario.
     * Accesible solo por administradores.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('usuario.edit', ['user' => $user]);
    }

    /**
     * Procesa los cambios en el perfil de un usuario.
     * Solo actualiza la contraseña si se proporciona un nuevo valor.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|min:8',
            'role' => 'required|in:admin,editor,usuario',
            'telefono' => 'nullable|string|max:15',
            'activo' => 'nullable|boolean',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->telefono = $request->telefono;

        if ($request->has('activo')) {
            $user->activo = $request->boolean('activo');
        }

        // Si el campo de contraseña no está vacío, la actualizamos
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect('/usuario')->with('success', 'Usuario actualizado exitosamente');
    }

    /**
     * Elimina un usuario del sistema permanentemente.
     * Método restringido exclusivamente al Administrador.
     */
    public function destroy($id)
    {
        User::destroy($id);
        return redirect('/usuario')->with('success', 'Usuario eliminado exitosamente');
    }

    public function toggleActivo($id)
    {
        $user = User::findOrFail($id);
        $user->activo = ! $user->activo;
        $user->save();

        $estado = $user->activo ? 'activado' : 'desactivado';

        return redirect()->route('usuario.index')->with('success', "Usuario {$estado} correctamente.");
    }
}
