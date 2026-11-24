@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Detalles del usuario</h4>

                <dl class="row mb-4">
                    <dt class="col-sm-4">ID</dt>
                    <dd class="col-sm-8">{{ $usuario->id }}</dd>

                    <dt class="col-sm-4">Nombre</dt>
                    <dd class="col-sm-8">{{ $usuario->name }}</dd>

                    <dt class="col-sm-4">Correo</dt>
                    <dd class="col-sm-8">{{ $usuario->email }}</dd>

                    <dt class="col-sm-4">Rol</dt>
                    <dd class="col-sm-8">{{ $usuario->role }}</dd>

                    <dt class="col-sm-4">Registrado</dt>
                    <dd class="col-sm-8">{{ $usuario->created_at?->format('d/m/Y H:i') }}</dd>

                    <dt class="col-sm-4">Última actualización</dt>
                    <dd class="col-sm-8">{{ $usuario->updated_at?->format('d/m/Y H:i') }}</dd>
                </dl>

                <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Volver</a>

                @if (auth()->user()->esAdmin() || ! $usuario->esAdmin())
                    <a href="{{ route('usuarios.edit', $usuario) }}" class="btn btn-primary">Editar</a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
