@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="mb-0">Usuarios</h3>
    <a href="{{ route('usuarios.create') }}" class="btn btn-primary">+ Registrar usuario</a>
</div>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>
                            <span class="badge {{ $usuario->esAdmin() ? 'bg-danger' : 'bg-info text-dark' }}">
                                {{ $usuario->role }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('usuarios.show', $usuario) }}" class="btn btn-sm btn-outline-secondary">Ver</a>

                            {{-- El empleado no puede editar administradores --}}
                            @if (auth()->user()->esAdmin() || ! $usuario->esAdmin())
                                <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            @endif

                            {{-- Opciones de eliminar: SOLO se muestran al administrador --}}
                            @if (auth()->user()->esAdmin() && $usuario->id !== auth()->id())
                                <div class="btn-group">
                                    <button type="button" class="btn btn-sm btn-outline-danger dropdown-toggle" data-bs-toggle="dropdown">
                                        Eliminar
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <form method="POST" action="{{ route('usuarios.destroy', $usuario) }}"
                                                  onsubmit="return confirm('¿Deshabilitar a {{ $usuario->name }}? Podrás restaurarlo desde la papelera.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item">Deshabilitar (Soft Delete)</button>
                                            </form>
                                        </li>
                                        <li>
                                            <form method="POST" action="{{ route('usuarios.forceDelete', $usuario->id) }}"
                                                  onsubmit="return confirm('¿Eliminar PERMANENTEMENTE a {{ $usuario->name }}? Esta acción no se puede deshacer.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="dropdown-item text-danger">Eliminar permanente (Delete)</button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $usuarios->links('pagination::bootstrap-5') }}
</div>
@endsection
