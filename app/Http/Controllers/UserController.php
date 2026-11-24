<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    // Lista de usuarios activos (los eliminados con Soft Delete no aparecen)
    public function index()
    {
        $usuarios = User::orderBy('name')->paginate(10);

        return view('users.index', compact('usuarios'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8'],
            'role' => ['nullable', Rule::in(['admin', 'empleado'])],
        ]);

        // Un empleado solo puede registrar usuarios con rol empleado
        if (! auth()->user()->esAdmin()) {
            $datos['role'] = 'empleado';
        }

        $datos['role'] = $datos['role'] ?? 'empleado';

        User::create($datos);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario registrado correctamente.');
    }

    public function show(User $user)
    {
        return view('users.show', ['usuario' => $user]);
    }

    public function edit(User $user)
    {
        $this->revisarPermisoSobre($user);

        return view('users.edit', ['usuario' => $user]);
    }

    public function update(Request $request, User $user)
    {
        $this->revisarPermisoSobre($user);

        $datos = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'confirmed', 'min:8'],
            'role' => ['nullable', Rule::in(['admin', 'empleado'])],
        ]);

        // Solo el administrador puede cambiar roles, y nadie cambia su propio rol
        if (! auth()->user()->esAdmin() || $user->id === auth()->id()) {
            unset($datos['role']);
        }

        // Si dejó la contraseña vacía, se conserva la anterior
        if (empty($datos['password'])) {
            unset($datos['password']);
        }

        $user->update($datos);

        return redirect()->route('usuarios.index')->with('ok', 'Usuario actualizado correctamente.');
    }

    // ---------- Solo administrador (protegido por el middleware role:admin) ----------

    // Eliminación lógica: el registro se queda en la base de datos con deleted_at
    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->route('usuarios.index')->with('ok', 'Usuario deshabilitado (Soft Delete). Puedes restaurarlo desde la papelera.');
    }

    // Lista de usuarios deshabilitados
    public function trashed()
    {
        $usuarios = User::onlyTrashed()->orderBy('name')->paginate(10);

        return view('users.papelera', compact('usuarios'));
    }

    public function restore($id)
    {
        $usuario = User::onlyTrashed()->findOrFail($id);
        $usuario->restore();

        return redirect()->route('usuarios.papelera')->with('ok', 'Usuario restaurado correctamente.');
    }

    // Eliminación permanente: el registro desaparece de la base de datos
    public function forceDelete($id)
    {
        $usuario = User::withTrashed()->findOrFail($id);

        if ($usuario->id === auth()->id()) {
            return back()->with('error', 'No puedes eliminar tu propia cuenta.');
        }

        $usuario->forceDelete();

        return back()->with('ok', 'Usuario eliminado de forma permanente.');
    }

    // Un empleado no puede editar a un administrador
    private function revisarPermisoSobre(User $user): void
    {
        if (! auth()->user()->esAdmin() && $user->esAdmin()) {
            abort(403, 'Un empleado no puede modificar a un administrador.');
        }
    }
}
