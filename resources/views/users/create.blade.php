@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-7">
        <div class="card shadow-sm">
            <div class="card-body p-4">
                <h4 class="mb-4">Registrar usuario</h4>

                <form method="POST" action="{{ route('usuarios.store') }}">
                    @csrf

                    @include('users._form', ['usuario' => null])

                    <a href="{{ route('usuarios.index') }}" class="btn btn-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
