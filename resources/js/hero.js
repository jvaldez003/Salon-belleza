/**
 * Presentación del inicio (banners). Componente Alpine: x-data="heroArrecife({ total, intervalo, fotos })".
 *
 *  - Cambia sola cada `intervalo` ms con una barra de progreso; se detiene con el mouse encima,
 *    con el foco del teclado dentro, fuera de pantalla, o con el botón de pausa.
 *  - En el celular se cambia deslizando la foto.
 *  - La foto usa HeroAgua (WebGL). Sin WebGL o con "reducir movimiento" se usan las fotos normales
 *    y no hay cambio automático.
 */
import { HeroAgua } from './hero-agua';

export function registrarHero(Alpine) {
    Alpine.data('heroArrecife', (cfg) => {
        // Estado que no debe ser reactivo (Alpine envolvería los objetos WebGL en Proxy)
        const priv = { gl: null, t: 0, ultimo: 0, visible: true, barras: [], toque: null, origen: null, ultMov: 0 };

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
                requestAnimationFrame(() => this.$el.setAttribute('data-listo', ''));

                const fotos = cfg.fotos || [];
                if (!this.reducir && this.$refs.lienzo && fotos.length && fotos.every(Boolean)) {
                    priv.gl = HeroAgua.crear(this.$refs.lienzo, fotos, {
                        listo: () => { this.glActivo = true; },
                        fallo: () => { this.glActivo = false; priv.gl = null; },
                    });
                }

                if ('IntersectionObserver' in window) {
                    new IntersectionObserver(([e]) => { priv.visible = e.isIntersecting; }).observe(this.$el);
                }

                const bucle = (ahora) => {
                    const dt = priv.ultimo ? Math.min(100, ahora - priv.ultimo) : 0;
                    priv.ultimo = ahora;
                    if (this.automatico && !this.pausado && !this.encima && !this.enfoque && priv.visible && !document.hidden) {
                        priv.t += dt;
                        if (priv.t >= cfg.intervalo) this.ir(this.activa + 1);
                    }
                    this.pintarBarras();
                    requestAnimationFrame(bucle);
                };
                if (this.total > 1) requestAnimationFrame(bucle);
                else this.pintarBarras();
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

            // Posición del puntero sobre la foto: mueve la foto y marca dónde nace la próxima onda
            mover(e) {
                const r = this.$refs.foto.getBoundingClientRect();
                const x = Math.min(1, Math.max(0, (e.clientX - r.left) / r.width));
                const y = Math.min(1, Math.max(0, (e.clientY - r.top) / r.height));
                priv.origen = [x, y];
                priv.ultMov = performance.now();
                if (e.pointerType === 'mouse') priv.gl?.mover(x * 2 - 1, y * 2 - 1);
            },

            soltar() {
                priv.gl?.mover(0, 0);
            },

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
