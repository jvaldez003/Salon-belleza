{{-- Paginación con el diseño Arrecife (la usan todos los ->links()). --}}
@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Páginas" class="flex flex-wrap items-center justify-between gap-3">
        <p class="text-sm text-texto-suave">
            @if ($paginator->firstItem())
                {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} de {{ $paginator->total() }}
            @endif
        </p>

        <ul class="flex flex-wrap items-center gap-1.5">
            <li>
                @if ($paginator->onFirstPage())
                    <span class="inline-flex items-center min-h-[40px] px-4 rounded-full text-sm text-texto-suave/60" aria-disabled="true">Anterior</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center min-h-[40px] px-4 rounded-full border border-linea bg-white text-sm font-semibold text-oceano hover:border-oceano">Anterior</a>
                @endif
            </li>

            @foreach ($elements as $element)
                @if (is_string($element))
                    <li class="hidden sm:block px-1 text-texto-suave" aria-hidden="true">…</li>
                @endif
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <li class="hidden sm:block">
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page" class="inline-grid place-items-center min-w-[40px] min-h-[40px] px-2 rounded-full bg-oceano text-white text-sm font-semibold tabular">{{ $page }}</span>
                            @else
                                <a href="{{ $url }}" class="inline-grid place-items-center min-w-[40px] min-h-[40px] px-2 rounded-full text-sm text-texto hover:bg-bruma tabular" aria-label="Página {{ $page }}">{{ $page }}</a>
                            @endif
                        </li>
                    @endforeach
                @endif
            @endforeach

            <li class="sm:hidden px-2 text-sm text-texto tabular">{{ $paginator->currentPage() }} de {{ $paginator->lastPage() }}</li>

            <li>
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center min-h-[40px] px-4 rounded-full border border-linea bg-white text-sm font-semibold text-oceano hover:border-oceano">Siguiente</a>
                @else
                    <span class="inline-flex items-center min-h-[40px] px-4 rounded-full text-sm text-texto-suave/60" aria-disabled="true">Siguiente</span>
                @endif
            </li>
        </ul>
    </nav>
@endif
