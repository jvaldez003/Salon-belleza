<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Administración de cuentas (solo administradores).
 * Protege contra quedarse sin administradores o bloquearse a uno mismo.
 */
class UsuariosController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withCount('citas');

        if ($request->filled('buscar')) {
            $buscar = $request->buscar;
            $query->where(fn ($q) => $q->where('name', 'like', "%{$buscar}%")
                ->orWhere('email', 'like', "%{$buscar}%")
                ->orWhere('telefono', 'like', "%{$buscar}%"));
        }

        if (array_key_exists($request->rol, User::ROLES)) {
            $query->where('role', $request->rol);
        }

        if ($request->filled('activo')) {
            $query->where('activo', $request->activo === '1');
        }

        $users   = $query->orderByRaw("FIELD(role, 'admin', 'editor', 'usuario')")->orderBy('name')->get();
        $totales = User::selectRaw('role, count(*) as total')->groupBy('role')->pluck('total', 'role');

        return view('usuario.index', compact('users', 'totales'));
    }

    public function create()
    {
        return view('usuario.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users',
            'telefono' => 'nullable|string|max:15',
            'password' => 'required|min:8|confirmed',
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
        ], $this->mensajes());

        $user = User::create([
            'name'              => $request->name,
            'email'             => $request->email,
            'telefono'          => $request->telefono,
            'password'          => Hash::make($request->password),
            'role'              => $request->role,
            'activo'            => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('usuario.show', $user->id)->with('success', "Creaste la cuenta de {$user->name}.");
    }

    public function show($id)
    {
        $user = User::withCount('citas')->findOrFail($id);
        $citas = $user->citas()->with('servicios')->orderByDesc('fecha')->orderByDesc('hora')->limit(8)->get();

        return view('usuario.show', compact('user', 'citas'));
    }

    public function edit($id)
    {
        return view('usuario.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'telefono' => 'nullable|string|max:15',
            'password' => 'nullable|min:8|confirmed',
            'role'     => ['required', Rule::in(array_keys(User::ROLES))],
        ], $this->mensajes());

        if ($user->isAdmin() && $request->role !== 'admin') {
            $this->exigirOtroAdmin($user, 'role', 'No puedes quitarle el rol de administrador: es el único que queda.');
            if ($user->is(auth()->user())) {
                throw ValidationException::withMessages(['role' => 'No puedes quitarte tu propio rol de administrador.']);
            }
        }

        $user->fill($request->only('name', 'email', 'telefono', 'role'));
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        $user->save();

        return redirect()->route('usuario.show', $user->id)->with('success', 'Guardaste los cambios.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->is(auth()->user())) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta desde aquí.');
        }
        if ($user->isAdmin() && User::where('role', 'admin')->count() <= 1) {
            return back()->with('error', 'No puedes eliminar al único administrador.');
        }

        $nombre = $user->name;
        $user->delete();

        return redirect()->route('usuario.index')->with('success', "Eliminaste la cuenta de {$nombre}. Sus citas se conservan sin cliente asignado.");
    }

    public function toggleActivo($id)
    {
        $user = User::findOrFail($id);

        if ($user->is(auth()->user())) {
            return back()->with('error', 'No puedes desactivar tu propia cuenta.');
        }
        if ($user->activo && $user->isAdmin() && User::where('role', 'admin')->where('activo', true)->count() <= 1) {
            return back()->with('error', 'No puedes desactivar al único administrador activo.');
        }

        $user->activo = ! $user->activo;
        $user->save();

        return back()->with('success', $user->activo
            ? "{$user->name} ya puede volver a entrar."
            : "{$user->name} ya no puede entrar. Sus datos y citas se conservan.");
    }

    private function exigirOtroAdmin(User $user, string $campo, string $mensaje): void
    {
        if (User::where('role', 'admin')->whereKeyNot($user->id)->doesntExist()) {
            throw ValidationException::withMessages([$campo => $mensaje]);
        }
    }

    private function mensajes(): array
    {
        return [
            'name.required'      => 'Escribe el nombre.',
            'email.required'     => 'Escribe el correo.',
            'email.email'        => 'Revisa el correo: debe tener la forma nombre@correo.com.',
            'email.unique'       => 'Ya hay una cuenta con este correo.',
            'password.required'  => 'Escribe una contraseña.',
            'password.min'       => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las dos contraseñas no coinciden.',
            'role.*'             => 'Elige qué puede hacer esta persona.',
        ];
    }
}
