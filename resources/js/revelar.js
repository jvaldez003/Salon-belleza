/**
 * Animaciones al bajar por la página (sitio público). Se usan con atributos en el HTML:
 *
 *   data-revelar="sube"       aparece subiendo y enfocándose
 *   data-revelar="palabras"   (títulos) las palabras emergen una a una desde una máscara
 *   data-revelar="foto"       la foto se descubre de abajo hacia arriba, como agua que sube
 *   data-revelar="linea"      una línea horizontal se dibuja de izquierda a derecha
 *   data-revelar="lectura"    (párrafos) el texto se va entintando palabra por palabra al bajar
 *   style="--d: 200ms"        retraso, para escalonar varios elementos
 *
 *   data-progreso             recibe --p de 0 a 1 según cuánto se ha bajado por él (p. ej. el paso a paso)
 *   data-paralaje="0.1"       la foto de adentro se mueve más lento que la página (profundidad)
 *   data-magnetico            el botón se acerca un poco al cursor
 *
 * Cada elemento se anima una sola vez, cuando entra en pantalla (recibe data-visto).
 * Con "reducir movimiento" todo se muestra de inmediato y sin paralaje ni efecto magnético.
 * Estilos en app.css, bloque "Animaciones al bajar".
 */
const reducir = () => window.matchMedia('(prefers-reduced-motion: reduce)').matches;

function partirPalabras(el, clase = 'revelar-palabra', conMascara = true) {
    if (el.dataset.partido) return;
    el.dataset.partido = '1';
    let i = 0;
    const recorrer = (nodo) => {
        [...nodo.childNodes].forEach((hijo) => {
            if (hijo.nodeType === Node.TEXT_NODE) {
                const partes = hijo.textContent.split(/(\s+)/);
                if (!partes.some((p) => p.trim())) return;
                const frag = document.createDocumentFragment();
                partes.forEach((p) => {
                    if (!p) return;
                    if (!p.trim()) { frag.appendChild(document.createTextNode(p)); return; }
                    const palabra = document.createElement('span');
                    palabra.className = clase;
                    palabra.style.setProperty('--i', i++);
                    palabra.textContent = p;
                    if (conMascara) {
                        const mascara = document.createElement('span');
                        mascara.className = 'hero-mascara';
                        mascara.appendChild(palabra);
                        frag.appendChild(mascara);
                    } else {
                        frag.appendChild(palabra);
                    }
                });
                hijo.replaceWith(frag);
            } else if (hijo.nodeType === Node.ELEMENT_NODE) {
                recorrer(hijo);
            }
        });
    };
    recorrer(el);
    el.style.setProperty('--n', i);
}

function iniciarRevelar() {
    const elementos = [...document.querySelectorAll('[data-revelar]')];
    if (!elementos.length) return;

    elementos.filter((el) => el.dataset.revelar === 'palabras').forEach((el) => partirPalabras(el));

    if (reducir() || !('IntersectionObserver' in window)) {
        elementos.forEach((el) => el.setAttribute('data-visto', ''));
        return;
    }

    // Una foto oculta con clip-path no cuenta como visible para el navegador:
    // en ese caso se vigila su contenedor y se marca la foto.
    const destino = new Map();
    const obs = new IntersectionObserver((entradas) => {
        entradas.forEach((e) => {
            if (!e.isIntersecting) return;
            (destino.get(e.target) || []).forEach((el) => el.setAttribute('data-visto', ''));
            obs.unobserve(e.target);
        });
    }, { threshold: .18, rootMargin: '0px 0px -8% 0px' });

    elementos.forEach((el) => {
        const vigilado = el.dataset.revelar === 'foto' && el.parentElement ? el.parentElement : el;
        if (!destino.has(vigilado)) destino.set(vigilado, []);
        destino.get(vigilado).push(el);
        obs.observe(vigilado);
    });
}

function iniciarParalaje() {
    const elementos = [...document.querySelectorAll('[data-paralaje]')];
    if (!elementos.length || reducir()) return;

    const visibles = new Set();
    if ('IntersectionObserver' in window) {
        const obs = new IntersectionObserver((entradas) => {
            entradas.forEach((e) => (e.isIntersecting ? visibles.add(e.target) : visibles.delete(e.target)));
        }, { rootMargin: '20% 0px' });
        elementos.forEach((el) => obs.observe(el));
    } else {
        elementos.forEach((el) => visibles.add(el));
    }

    let pendiente = false;
    const actualizar = () => {
        pendiente = false;
        const alto = window.innerHeight;
        visibles.forEach((el) => {
            const r = el.getBoundingClientRect();
            // -1 cuando el centro está abajo de la pantalla, 1 cuando está arriba
            const pos = ((r.top + r.height / 2) - alto / 2) / (alto / 2 + r.height / 2);
            const factor = parseFloat(el.dataset.paralaje) || .1;
            el.style.setProperty('--py', `${(-pos * factor * r.height).toFixed(1)}px`);
        });
    };
    const pedir = () => { if (!pendiente) { pendiente = true; requestAnimationFrame(actualizar); } };
    window.addEventListener('scroll', pedir, { passive: true });
    window.addEventListener('resize', pedir, { passive: true });
    actualizar();
}

function iniciarMagneticos() {
    if (reducir() || !window.matchMedia('(hover: hover) and (pointer: fine)').matches) return;
    document.querySelectorAll('[data-magnetico]').forEach((el) => {
        const fuerza = 0.28;
        el.addEventListener('pointermove', (e) => {
            const r = el.getBoundingClientRect();
            const x = (e.clientX - (r.left + r.width / 2)) * fuerza;
            const y = (e.clientY - (r.top + r.height / 2)) * fuerza;
            el.style.transform = `translate(${x.toFixed(1)}px, ${y.toFixed(1)}px)`;
        });
        el.addEventListener('pointerleave', () => { el.style.transform = ''; });
    });
}

/** Texto que se entinta al bajar: --p va de 0 a 1 según cuánto ha recorrido el párrafo la pantalla. */
function iniciarLectura() {
    const elementos = [...document.querySelectorAll('[data-revelar="lectura"], [data-progreso]')];
    if (!elementos.length) return;
    elementos.filter((el) => el.dataset.revelar === 'lectura').forEach((el) => partirPalabras(el, 'lectura-palabra', false));
    if (reducir()) { elementos.forEach((el) => el.style.setProperty('--p', 1)); return; }

    let pendiente = false;
    const actualizar = () => {
        pendiente = false;
        const alto = window.innerHeight;
        elementos.forEach((el) => {
            const r = el.getBoundingClientRect();
            if (!r.height || r.bottom < -alto || r.top > alto * 2) return; // oculto o lejos
            // Empieza cuando el párrafo entra por abajo y termina cuando su final pasa el 45 % de la pantalla
            const p = (alto * .92 - r.top) / (r.height + alto * .47);
            el.style.setProperty('--p', Math.min(1, Math.max(0, p)).toFixed(3));
        });
    };
    const pedir = () => { if (!pendiente) { pendiente = true; requestAnimationFrame(actualizar); } };
    window.addEventListener('scroll', pedir, { passive: true });
    window.addEventListener('resize', pedir, { passive: true });
    // Cuando un panel oculto se muestra (p. ej. al elegir otro protocolo) hay que recalcular
    document.addEventListener('recalcular-progreso', pedir);
    actualizar();
}

export function iniciarAnimacionesPagina() {
    iniciarLectura();
    iniciarRevelar();
    iniciarParalaje();
    iniciarMagneticos();
}
