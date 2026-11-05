{{--
    Configuración del sitio (admin): datos del negocio, contacto, redes, textos de Quiénes somos / Misión / Visión
    y las fotos de la página Nosotros. El logo del sitio es el archivo public/img/logo.png.
--}}
@php
    $c = $config;
    $redes = [
        ['whatsapp', 'WhatsApp', 'text', '573001234567', 'Número con indicativo de país, sin espacios ni signos.'],
        ['instagram', 'Instagram', 'url', 'https://instagram.com/arrecifebeautystudio', null],
        ['facebook', 'Facebook', 'url', 'https://facebook.com/tu-pagina', null],
        ['tiktok', 'TikTok', 'url', 'https://tiktok.com/@tu-perfil', null],
        ['youtube', 'YouTube', 'url', 'https://youtube.com/@tu-canal', null],
    ];
    $galerias = [
        'quienes_somos' => ['Quiénes somos', 'Las dos primeras salen juntas en la parte de arriba de la página Nosotros.'],
        'mision'        => ['Misión', 'Se muestran hasta tres, junto al texto de la misión.'],
        'vision'        => ['Visión', 'Se muestran hasta tres, junto al texto de la visión.'],
    ];
    $secciones = [['datos', 'Negocio y contacto'], ['redes', 'Redes sociales'], ['textos', 'Quiénes somos, misión y visión'], ['fotos', 'Fotos de la página Nosotros']];
@endphp

<x-app-layout titulo="Configuración del sitio">
    <x-slot name="header">
        <div class="flex flex-wrap items-end justify-between gap-4">
            <div class="grid gap-1">
                <h1 class="text-[clamp(2rem,1.5rem+2vw,3rem)] leading-none">Configuración del sitio</h1>
                <p class="text-texto-suave">Los datos y textos que aparecen en la página web.</p>
            </div>
            <x-boton variante="secundario" :href="url('/')" target="_blank" rel="noopener">Ver el sitio</x-boton>
        </div>
    </x-slot>

    <div class="max-w-arrecife mx-auto px-4 sm:px-6 py-8 md:py-10 grid lg:grid-cols-[13rem_minmax(0,1fr)] gap-8 items-start">
        <nav class="hidden lg:block sticky top-6" aria-label="Secciones de la configuración">
            <ul class="grid gap-1 text-[0.9375rem]">
                @foreach ($secciones as [$ancla, $texto])
                    <li><a href="#{{ $ancla }}" class="block px-3 py-2 rounded-xl text-texto hover:bg-bruma hover:text-oceano">{{ $texto }}</a></li>
                @endforeach
            </ul>
        </nav>

        <div class="grid gap-8 min-w-0">
            <x-aviso />
            @if ($errors->any())
                <div class="alert-error" role="alert">
                    <div><p class="font-semibold">Revisa estos datos:</p><ul class="list-disc pl-5 text-sm">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
                </div>
            @endif

            <form method="POST" action="{{ route('configuracion.update') }}" enctype="multipart/form-data" class="grid gap-8">
                @csrf @method('PUT')

                {{-- Negocio y contacto --}}
                <section id="datos" class="card p-6 md:p-8 grid gap-6 scroll-mt-6" aria-labelledby="t-datos">
                    <h2 id="t-datos" class="text-2xl">Negocio y contacto</h2>
                    <div class="grid sm:grid-cols-2 gap-6">
                        <div class="grid gap-1.5">
                            <label for="nombre_negocio" class="label">Nombre del negocio</label>
                            <input id="nombre_negocio" type="text" name="nombre_negocio" value="{{ old('nombre_negocio', $c->nombre_negocio) }}" required maxlength="100" class="form-input">
                            <p class="text-xs text-texto-suave">Se usa en el logotipo del encabezado y en el título de la página.</p>
                        </div>
                        <div class="grid gap-1.5">
                            <label for="eslogan" class="label">Eslogan <span class="font-normal text-texto-suave">(opcional)</span></label>
                            <input id="eslogan" type="text" name="eslogan" value="{{ old('eslogan', $c->eslogan) }}" maxlength="150" class="form-input" placeholder="Tu lugar para desconectarte">
                            <p class="text-xs text-texto-suave">Aparece en el pie de página.</p>
                        </div>
                    </div>
                    <div class="grid sm:grid-cols-3 gap-6">
                        <div class="grid gap-1.5">
                            <label for="telefono" class="label">Teléfono</label>
                            <input id="telefono" type="tel" name="telefono" value="{{ old('telefono', $c->telefono) }}" maxlength="30" class="form-input" placeholder="+57 300 000 0000">
                        </div>
                        <div class="grid gap-1.5">
                            <label for="email_contacto" class="label">Correo de contacto</label>
                            <input id="email_contacto" type="email" name="email_contacto" value="{{ old('email_contacto', $c->email_contacto) }}" maxlength="100" class="form-input" placeholder="hola@arrecife.com">
                        </div>
                        <div class="grid gap-1.5">
                            <label for="direccion" class="label">Dirección</label>
                            <input id="direccion" type="text" name="direccion" value="{{ old('direccion', $c->direccion) }}" maxlength="200" class="form-input" placeholder="Calle 00 # 00-00, Candelaria">
                        </div>
                    </div>
                    <p class="text-sm text-texto-suave">Los campos vacíos no se muestran en el sitio. Hoy faltan: {{ collect(['teléfono' => $c->telefono, 'correo' => $c->email_contacto, 'dirección' => $c->direccion])->filter(fn ($v) => ! $v)->keys()->implode(', ') ?: 'ninguno' }}.</p>
                </section>

                {{-- Redes --}}
                <section id="redes" class="card p-6 md:p-8 grid gap-6 scroll-mt-6" aria-labelledby="t-redes">
                    <div class="grid gap-1">
                        <h2 id="t-redes" class="text-2xl">Redes sociales</h2>
                        <p class="text-sm text-texto-suave">Aparecen en el pie de página. WhatsApp además activa el botón flotante y los botones "Preguntar por WhatsApp".</p>
                    </div>
                    <div class="grid sm:grid-cols-2 gap-6">
                        @foreach ($redes as [$campo, $texto, $tipo, $ejemplo, $ayuda])
                            <div class="grid gap-1.5 content-start">
                                <label for="{{ $campo }}" class="label">{{ $texto }}</label>
                                <input id="{{ $campo }}" type="{{ $tipo }}" name="{{ $campo }}" value="{{ old($campo, $c->$campo) }}" maxlength="200" class="form-input" placeholder="{{ $ejemplo }}">
                                @if ($ayuda)<p class="text-xs text-texto-suave">{{ $ayuda }}</p>@endif
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- Textos --}}
                <section id="textos" class="card p-6 md:p-8 grid gap-8 scroll-mt-6" aria-labelledby="t-textos">
                    <h2 id="t-textos" class="text-2xl">Quiénes somos, misión y visión</h2>
                    <p class="text-sm text-texto-suave -mt-4">Puedes escribir en mayúsculas o normal: el sitio los muestra siempre en formato normal. Deja una línea en blanco para separar párrafos.</p>

                    <div class="grid gap-6">
                        <div class="grid gap-1.5">
                            <label for="quienes_somos_titulo" class="label">Título de "Quiénes somos"</label>
                            <input id="quienes_somos_titulo" type="text" name="quienes_somos_titulo" value="{{ old('quienes_somos_titulo', $c->quienes_somos_titulo) }}" maxlength="100" class="form-input" placeholder="Quiénes somos">
                        </div>
                        <div class="grid gap-1.5">
                            <label for="quienes_somos_texto" class="label">Texto de "Quiénes somos"</label>
                            <textarea id="quienes_somos_texto" name="quienes_somos_texto" rows="6" maxlength="2000" class="form-input">{{ old('quienes_somos_texto', $c->quienes_somos_texto) }}</textarea>
                            <p class="text-xs text-texto-suave">En el inicio se muestra un resumen; completo en la página Nosotros.</p>
                        </div>
                        <div class="grid sm:grid-cols-[auto_minmax(0,1fr)] gap-6 items-start">
                            @if ($c->quienes_somos_imagen)
                                <div class="grid gap-1.5 w-32">
                                    <x-foto :src="$c->quienes_somos_imagen" proporcion="3/4" foco="50% 35%" alt="Foto principal actual" />
                                    <p class="text-xs text-texto-suave">Foto actual</p>
                                </div>
                            @endif
                            <x-subir-fotos name="quienes_somos_imagen" id="foto-principal"
                                           :etiqueta="$c->quienes_somos_imagen ? 'Cambiar la foto principal' : 'Foto principal'"
                                           ayuda="Sale en el inicio junto a 'Quiénes somos', en la pantalla de entrar y en el inicio cuando no hay banners. Vertical, con las caras arriba o al centro."
                                           :marcos="[['Inicio', '3/4'], ['Pantalla de entrar', '4/5']]" />
                        </div>
                    </div>

                    <div class="grid sm:grid-cols-2 gap-6 pt-6 border-t border-linea">
                        <div class="grid gap-1.5">
                            <label for="mision_texto" class="label">Misión</label>
                            <textarea id="mision_texto" name="mision_texto" rows="6" maxlength="1000" class="form-input">{{ old('mision_texto', $c->mision_texto) }}</textarea>
                        </div>
                        <div class="grid gap-1.5">
                            <label for="vision_texto" class="label">Visión</label>
                            <textarea id="vision_texto" name="vision_texto" rows="6" maxlength="1000" class="form-input">{{ old('vision_texto', $c->vision_texto) }}</textarea>
                        </div>
                    </div>
                </section>

                <div class="sticky bottom-0 z-10 -mx-4 sm:mx-0 px-4 sm:px-6 py-4 bg-superficie/95 backdrop-blur border-t border-linea sm:rounded-2xl sm:border flex flex-wrap items-center justify-between gap-3 pb-[max(1rem,env(safe-area-inset-bottom))]">
                    <p class="text-sm text-texto-suave">Los cambios se ven en el sitio apenas guardas.</p>
                    <button type="submit" class="btn-primary">Guardar configuración</button>
                </div>
            </form>

            {{-- Fotos de la página Nosotros (cada galería se guarda por separado) --}}
            <section id="fotos" class="grid gap-6 scroll-mt-6" aria-labelledby="t-fotos">
                <div class="grid gap-1">
                    <h2 id="t-fotos" class="text-2xl">Fotos de la página Nosotros</h2>
                    <p class="text-sm text-texto-suave">Cada foto se sube y se quita al momento, sin necesidad de "Guardar configuración".</p>
                </div>

                @foreach ($galerias as $clave => [$titulo, $ayuda])
                    @php $imgs = $imagenesPorSeccion[$clave] ?? collect(); @endphp
                    <div class="card p-6 md:p-8 grid gap-5" aria-labelledby="g-{{ $clave }}">
                        <div class="flex flex-wrap items-baseline justify-between gap-2">
                            <h3 id="g-{{ $clave }}" class="font-display text-xl">{{ $titulo }}</h3>
                            <p class="text-sm text-texto-suave">{{ $imgs->count() }} {{ $imgs->count() === 1 ? 'foto' : 'fotos' }}</p>
                        </div>
                        <p class="text-sm text-texto-suave -mt-3">{{ $ayuda }}</p>

                        @if ($imgs->isNotEmpty())
                            <ul class="flex flex-wrap gap-4">
                                @foreach ($imgs as $img)
                                    <li class="grid gap-1.5 w-28">
                                        <x-foto :src="$img->imagen_url" proporcion="3/4" foco="50% 30%" :alt="$titulo . ', foto ' . $loop->iteration" />
                                        <x-confirmar-borrado :action="route('configuracion.imagenes.destroy', $img)" que="esta foto" texto="Quitar" compacto />
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                        <form method="POST" action="{{ route('configuracion.imagenes.store') }}" enctype="multipart/form-data" class="grid gap-4 max-w-xl">
                            @csrf
                            <input type="hidden" name="seccion" value="{{ $clave }}">
                            <x-subir-fotos name="imagen" :id="'foto-' . $clave" etiqueta="Agregar una foto" :marcos="[['Página Nosotros', '3/4']]" />
                            <div><button type="submit" class="btn-primary">Subir foto</button></div>
                        </form>
                    </div>
                @endforeach
            </section>
        </div>
    </div>
</x-app-layout>
