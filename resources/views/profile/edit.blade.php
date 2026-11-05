<x-app-layout titulo="Mi cuenta">
    <x-slot name="header">
        <div class="grid gap-1">
            <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Mi cuenta</h1>
            <p class="text-texto-suave">{{ $user->rol_nombre }} · cuenta creada el {{ $user->created_at?->translatedFormat('j \d\e F \d\e Y') }}</p>
        </div>
    </x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 py-8 md:py-10 grid gap-6">
        <section class="card p-6 md:p-8">
            @include('profile.partials.update-profile-information-form')
        </section>

        <section class="card p-6 md:p-8">
            @include('profile.partials.update-password-form')
        </section>

        <section class="p-6 md:p-8 rounded-2xl border border-red-200 bg-white">
            @include('profile.partials.delete-user-form')
        </section>
    </div>
</x-app-layout>
