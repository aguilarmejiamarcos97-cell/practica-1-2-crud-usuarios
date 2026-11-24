@extends('layouts.app')

@section('content')
<h3 class="mb-3">Papelera (usuarios deshabilitados)</h3>

<div class="card shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Correo</th>
                    <th>Rol</th>
                    <th>Deshabilitado el</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($usuarios as $usuario)
                    <tr>
                        <td>{{ $usuario->id }}</td>
                        <td>{{ $usuario->name }}</td>
                        <td>{{ $usuario->email }}</td>
                        <td>{{ $usuario->role }}</td>
                        <td>{{ $usuario->deleted_at->format('d/m/Y H:i') }}</td>
                        <td class="text-end">
                            <form method="POST" action="{{ route('usuarios.restore', $usuario->id) }}" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success">Restaurar</button>
                            </form>

                            <form method="POST" action="{{ route('usuarios.forceDelete', $usuario->id) }}" class="d-inline"
                                  onsubmit="return confirm('¿Eliminar PERMANENTEMENTE a {{ $usuario->name }}? Esta acción no se puede deshacer.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Eliminar permanente</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">La papelera está vacía.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-3">
    {{ $usuarios->links('pagination::bootstrap-5') }}
</div>
@endsection
