<div class="grid gap-6" x-data="{ ver: false }">
    <header class="grid gap-1">
        <h2 class="font-display text-xl">Contraseña</h2>
        <p class="text-sm text-texto-suave">Usa al menos 8 caracteres. Una frase corta es fácil de recordar y difícil de adivinar.</p>
    </header>

    @if (session('status') === 'password-updated')
        <div class="alert-success" role="status">Cambiaste tu contraseña.</div>
    @endif

    <form method="post" action="{{ route('password.update') }}" class="grid gap-6">
        @csrf
        @method('put')

        <div class="grid gap-1.5 sm:max-w-[calc(50%-0.75rem)]">
            <label for="update_password_current_password" class="label">Contraseña actual</label>
            <input id="update_password_current_password" name="current_password" :type="ver ? 'text' : 'password'" type="password" class="form-input" autocomplete="current-password">
            <x-input-error :messages="$errors->updatePassword->get('current_password')" />
        </div>

        <div class="grid sm:grid-cols-2 gap-6">
            <div class="grid gap-1.5 content-start">
                <label for="update_password_password" class="label">Nueva contraseña</label>
                <input id="update_password_password" name="password" :type="ver ? 'text' : 'password'" type="password" class="form-input" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password')" />
            </div>
            <div class="grid gap-1.5 content-start">
                <label for="update_password_password_confirmation" class="label">Repite la nueva contraseña</label>
                <input id="update_password_password_confirmation" name="password_confirmation" :type="ver ? 'text' : 'password'" type="password" class="form-input" autocomplete="new-password">
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" />
            </div>
        </div>

        <label class="inline-flex items-center gap-2 text-sm text-texto cursor-pointer w-fit">
            <input type="checkbox" x-model="ver" class="w-4 h-4 rounded border-linea text-oceano focus:ring-laguna">
            Mostrar contraseñas
        </label>

        <div>
            <button type="submit" class="btn-primary">Cambiar contraseña</button>
        </div>
    </form>
</div>
