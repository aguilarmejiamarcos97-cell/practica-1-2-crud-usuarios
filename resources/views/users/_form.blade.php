{{-- Formulario compartido por "registrar" y "editar". $usuario es null al registrar. --}}

<div class="mb-3">
    <label for="name" class="form-label">Nombre</label>
    <input type="text" id="name" name="name"
           class="form-control @error('name') is-invalid @enderror"
           value="{{ old('name', $usuario->name ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="email" class="form-label">Correo electrónico</label>
    <input type="email" id="email" name="email"
           class="form-control @error('email') is-invalid @enderror"
           value="{{ old('email', $usuario->email ?? '') }}" required>
    @error('email')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="row">
    <div class="col-md-6 mb-3">
        <label for="password" class="form-label">
            Contraseña @if ($usuario ?? false) <small class="text-muted">(déjala vacía para no cambiarla)</small> @endif
        </label>
        <input type="password" id="password" name="password"
               class="form-control @error('password') is-invalid @enderror"
               {{ ($usuario ?? false) ? '' : 'required' }}>
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="col-md-6 mb-3">
        <label for="password_confirmation" class="form-label">Confirmar contraseña</label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control">
    </div>
</div>

{{-- El rol solo lo puede elegir el administrador --}}
@if (auth()->user()->esAdmin() && (($usuario->id ?? null) !== auth()->id()))
    <div class="mb-3">
        <label for="role" class="form-label">Rol</label>
        <select id="role" name="role" class="form-select @error('role') is-invalid @enderror">
            <option value="empleado" @selected(old('role', $usuario->role ?? 'empleado') === 'empleado')>Empleado</option>
            <option value="admin" @selected(old('role', $usuario->role ?? '') === 'admin')>Administrador</option>
        </select>
        @error('role')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>
@else
    <p class="text-muted">Rol: <strong>{{ $usuario->role ?? 'empleado' }}</strong></p>
@endif
