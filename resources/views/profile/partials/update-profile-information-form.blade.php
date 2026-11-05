<form id="send-verification" method="post" action="{{ route('verification.send') }}" hidden>
    @csrf
</form>

<div class="grid gap-6">
    <header class="grid gap-1">
        <h2 class="font-display text-xl">Tus datos</h2>
        <p class="text-sm text-texto-suave">Con tu celular el salón puede escribirte por WhatsApp sobre tus citas.</p>
    </header>

    @if (session('status') === 'profile-updated')
        <div class="alert-success" role="status">Guardaste tus datos.</div>
    @endif

    <form method="post" action="{{ route('profile.update') }}" class="grid gap-6">
        @csrf
        @method('patch')

        <div class="grid gap-1.5">
            <label for="name" class="label">Nombre completo</label>
            <input id="name" name="name" type="text" class="form-input" value="{{ old('name', $user->name) }}" required autocomplete="name">
            <x-input-error :messages="$errors->get('name')" />
        </div>

        <div class="grid sm:grid-cols-[minmax(0,1fr)_14rem] gap-6">
            <div class="grid gap-1.5 content-start">
                <label for="email" class="label">Correo</label>
                <input id="email" name="email" type="email" class="form-input" value="{{ old('email', $user->email) }}" required autocomplete="username">
                <x-input-error :messages="$errors->get('email')" />

                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                    <p class="text-sm text-texto">
                        Todavía no confirmas este correo.
                        <button form="send-verification" class="font-semibold text-oceano hover:underline underline-offset-4">Enviar el enlace otra vez</button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="text-sm font-semibold text-oceano" role="status">Te enviamos un nuevo enlace. Revisa tu correo.</p>
                    @endif
                @else
                    <p class="text-xs text-texto-suave">Si lo cambias, tendrás que confirmar el nuevo correo.</p>
                @endif
            </div>
            <div class="grid gap-1.5 content-start">
                <label for="telefono" class="label">Celular <span class="font-normal text-texto-suave">(opcional)</span></label>
                <input id="telefono" name="telefono" type="tel" inputmode="tel" maxlength="15" class="form-input tabular" value="{{ old('telefono', $user->telefono) }}" autocomplete="tel" placeholder="3001234567">
                <x-input-error :messages="$errors->get('telefono')" />
            </div>
        </div>

        <div>
            <button type="submit" class="btn-primary">Guardar datos</button>
        </div>
    </form>
</div>
