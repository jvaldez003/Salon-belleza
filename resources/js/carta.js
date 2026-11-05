/**
 * Carta de servicios y precios del inicio. Componente Alpine: x-data="cartaServicios('<id inicial>')".
 *
 *  - Marca la sección como vista la primera vez que entra en pantalla (data-visto): ahí arrancan
 *    las animaciones de entrada (CSS en app.css, bloque "Carta de servicios").
 *  - Mueve la línea indicadora bajo la categoría elegida.
 *  - Las filas de precios usan animaciones CSS, que se repiten solas cada vez que el panel
 *    pasa de oculto a visible al cambiar de categoría.
 */
export function registrarCarta(Alpine) {
    Alpine.data('cartaServicios', (inicial) => ({
        cat: inicial,

        init() {
            const marcar = () => this.$el.setAttribute('data-visto', '');
            if ('IntersectionObserver' in window) {
                const obs = new IntersectionObserver(([e]) => {
                    if (e.isIntersecting) { marcar(); obs.disconnect(); }
                }, { threshold: .15 });
                obs.observe(this.$el);
            } else {
                marcar();
            }

            this.$nextTick(() => this.moverIndicador(false));
            window.addEventListener('resize', () => this.moverIndicador(false), { passive: true });
            // Las fuentes cambian el ancho de los nombres: recolocar cuando terminen de cargar
            document.fonts?.ready.then(() => this.moverIndicador(false));
        },

        elegir(id) {
            if (id === this.cat) return;
            this.cat = id;
            this.$nextTick(() => {
                this.moverIndicador(true);
                const boton = this.$refs.categorias?.querySelector(`[data-cat="${id}"]`);
                // En el celular, la categoría elegida se centra en la fila deslizable sin mover la página
                if (boton && this.$refs.categorias.scrollWidth > this.$refs.categorias.clientWidth) {
                    const fila = this.$refs.categorias;
                    const destino = boton.offsetLeft - (fila.clientWidth - boton.offsetWidth) / 2;
                    fila.scrollTo({ left: destino, behavior: 'smooth' });
                }
            });
        },

        moverIndicador(animar) {
            const ind = this.$refs.indicador;
            const boton = this.$refs.categorias?.querySelector(`[data-cat="${this.cat}"]`);
            if (!ind || !boton) return;
            ind.style.transition = animar ? '' : 'none';
            ind.style.width = `${boton.offsetWidth}px`;
            ind.style.transform = `translateX(${boton.offsetLeft}px)`;
            if (!animar) requestAnimationFrame(() => { ind.style.transition = ''; });
        },
    }));
}
