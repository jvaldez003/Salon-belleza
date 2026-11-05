/**
 * Presentación del inicio (banners). Componente Alpine: x-data="heroArrecife({ total, intervalo, fotos })".
 *
 *  - Cambia sola cada `intervalo` ms con una barra de progreso; se detiene con el mouse sobre la foto,
 *    con el foco del teclado dentro, fuera de pantalla, o con el botón de pausa.
 *  - En el celular se cambia deslizando la foto.
 *  - Escena 3D:
 *      · la foto (HeroAgua, WebGL) con onda de agua, refracción y paralaje,
 *      · las letras del nombre se separan un poco con el mouse (--hmx/--hmy),
 *      · burbujas y plancton en 3D detrás y delante del arco (HeroBurbujas),
 *      · al bajar por la página todo "se hunde" (--hundir).
 *  - Si la página empieza con la entrada desde la superficie (html.intro-superficie), el título espera a que termine.
 *  - Sin WebGL o con "reducir movimiento": fotos normales, sin cambio automático ni escena 3D.
 */
import { HeroAgua } from './hero-agua';
import { HeroBurbujas } from './hero-burbujas';

export function registrarHero(Alpine) {
    Alpine.data('heroArrecife', (cfg) => {
        // Estado que no debe ser reactivo (Alpine envolvería los objetos WebGL en Proxy)
        const priv = {
            gl: null, burbujas: null, t: 0, ultimo: 0, visible: true, barras: [], toque: null, origen: null, ultMov: 0,
            raton: { x: 0, y: 0 }, suave: { x: 0, y: 0 }, hundir: 0, vars: {},
        };

        return {
            activa: 0,
            total: cfg.total,
            pausado: false,
            encima: false,
            enfoque: false,
            glActivo: false,
            reducir: window.matchMedia('(prefers-reduced-motion: reduce)').matches,

            get automatico() {
                return this.total > 1 && !this.reducir;
            },

            init() {
                priv.barras = [...this.$el.querySelectorAll('[data-barra]')];
                // Con la entrada desde la superficie, el título aparece cuando el agua ya se abrió
                const conIntro = document.documentElement.classList.contains('intro-superficie');
                setTimeout(() => requestAnimationFrame(() => this.$el.setAttribute('data-listo', '')), conIntro ? 1150 : 0);

                const fotos = cfg.fotos || [];
                if (!this.reducir && this.$refs.lienzo && fotos.length && fotos.every(Boolean)) {
                    priv.gl = HeroAgua.crear(this.$refs.lienzo, fotos, {
                        listo: () => { this.glActivo = true; },
                        fallo: () => { this.glActivo = false; priv.gl = null; },
                    });
                }
                if (!this.reducir && this.$refs.burbujasAtras && this.$refs.burbujasFrente) {
                    const movil = window.matchMedia('(max-width: 767px)').matches;
                    priv.burbujas = HeroBurbujas.crear(this.$refs.burbujasAtras, this.$refs.burbujasFrente, { cantidad: movil ? 20 : 45 });
                }

                if ('IntersectionObserver' in window) {
                    new IntersectionObserver(([e]) => {
                        priv.visible = e.isIntersecting;
                        if (!priv.visible) priv.burbujas?.pausar();
                    }).observe(this.$el);
                }

                const bucle = (ahora) => {
                    const dt = priv.ultimo ? Math.min(100, ahora - priv.ultimo) : 0;
                    priv.ultimo = ahora;
                    const activo = priv.visible && !document.hidden;

                    if (this.automatico && !this.pausado && !this.encima && !this.enfoque && activo) {
                        priv.t += dt;
                        if (priv.t >= cfg.intervalo) this.ir(this.activa + 1);
                    }
                    this.pintarBarras();
                    if (activo && !this.reducir) this.escena(ahora);
                    requestAnimationFrame(bucle);
                };
                requestAnimationFrame(bucle);
            },

            /** Un cuadro de la escena 3D: suaviza el mouse, calcula el hundimiento y lo reparte. */
            escena(ahora) {
                priv.suave.x += (priv.raton.x - priv.suave.x) * .07;
                priv.suave.y += (priv.raton.y - priv.suave.y) * .07;

                const alto = this.$el.offsetHeight || window.innerHeight;
                priv.hundir = Math.min(1, Math.max(0, window.scrollY / (alto * .85)));

                this.poner('--hmx', priv.suave.x, 3);
                this.poner('--hmy', priv.suave.y, 3);
                this.poner('--hundir', priv.hundir, 3);

                priv.gl?.mover(priv.suave.x, priv.suave.y);
                priv.gl?.hundirse?.(priv.hundir);
                priv.burbujas?.actualizar({ mx: priv.suave.x, my: priv.suave.y, hundir: priv.hundir, ahora });
            },

            // Solo escribe la variable CSS si cambió (evita recalcular estilos en cada cuadro)
            poner(nombre, valor, decimales) {
                const texto = valor.toFixed(decimales);
                if (priv.vars[nombre] === texto) return;
                priv.vars[nombre] = texto;
                this.$el.style.setProperty(nombre, texto);
            },

            pintarBarras() {
                const p = this.automatico ? Math.min(1, priv.t / cfg.intervalo) : 1;
                priv.barras.forEach((b, i) => { b.style.transform = `scaleX(${i === this.activa ? p : 0})`; });
            },

            ir(n) {
                n = (n + this.total) % this.total;
                if (n === this.activa) return;
                this.activa = n;
                priv.t = 0;
                const reciente = priv.origen && performance.now() - priv.ultMov < 1500;
                priv.gl?.ir(n, reciente ? priv.origen : null);
            },

            alternarPausa() {
                this.pausado = !this.pausado;
            },

            // Mouse sobre toda la presentación: inclina el arco y gira la cámara de las partículas
            moverEscena(e) {
                if (e.pointerType !== 'mouse') return;
                const r = this.$el.getBoundingClientRect();
                priv.raton.x = Math.min(1, Math.max(-1, ((e.clientX - r.left) / r.width) * 2 - 1));
                priv.raton.y = Math.min(1, Math.max(-1, ((e.clientY - r.top) / r.height) * 2 - 1));
            },

            soltarEscena() {
                priv.raton.x = 0;
                priv.raton.y = 0;
            },

            // Puntero sobre la foto: marca dónde nace la próxima onda
            mover(e) {
                const r = this.$refs.foto.getBoundingClientRect();
                priv.origen = [
                    Math.min(1, Math.max(0, (e.clientX - r.left) / r.width)),
                    Math.min(1, Math.max(0, (e.clientY - r.top) / r.height)),
                ];
                priv.ultMov = performance.now();
            },

            soltar() {},

            // Deslizar en el celular
            tocar(e) {
                priv.toque = { x: e.clientX, y: e.clientY };
            },

            levantar(e) {
                if (!priv.toque) return;
                const dx = e.clientX - priv.toque.x;
                const dy = e.clientY - priv.toque.y;
                priv.toque = null;
                if (Math.abs(dx) > 45 && Math.abs(dx) > Math.abs(dy) * 1.2) {
                    this.mover(e);
                    this.ir(this.activa + (dx < 0 ? 1 : -1));
                }
            },
        };
    });
}
