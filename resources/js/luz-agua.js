/**
 * Reflejo de luz de agua (cáusticas) para <canvas data-luz-agua>.
 * Se dibuja a baja resolución y el navegador lo escala con un leve desenfoque: barato y suave.
 * Con "reducir movimiento" activado se dibuja un solo cuadro fijo.
 */
const TAU = Math.PI * 2;

function dibujar(ctx, img, W, H, t) {
    const d = img.data;
    let k = 0;
    for (let y = 0; y < H; y++) {
        const fade = Math.min(1, (1 - y / H) * 1.6);
        for (let x = 0; x < W; x++) {
            const px = (x / W) * TAU * 1.1 - 250;
            const py = (y / H) * TAU * 1.4 - 250;
            let ix = px, iy = py, c = 1;
            const inten = 0.005;
            for (let n = 0; n < 4; n++) {
                const tt = t * (1 - 3.5 / (n + 1));
                const nx = px + Math.cos(tt - ix) + Math.sin(tt + iy);
                const ny = py + Math.sin(tt - iy) + Math.cos(tt + ix);
                ix = nx; iy = ny;
                const a = px / (Math.sin(ix + tt) / inten);
                const b = py / (Math.cos(iy + tt) / inten);
                c += 1 / Math.sqrt(a * a + b * b);
            }
            c = 1.17 - Math.pow(c / 4, 1.4);
            const v = Math.min(1, Math.pow(Math.abs(c), 8));
            d[k] = 190; d[k + 1] = 236; d[k + 2] = 255; d[k + 3] = v * 255 * fade;
            k += 4;
        }
    }
    ctx.putImageData(img, 0, 0);
}

export function iniciarLuzAgua() {
    const reducir = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    document.querySelectorAll('canvas[data-luz-agua]').forEach((cv) => {
        const ctx = cv.getContext('2d');
        if (!ctx) return;
        const W = cv.width, H = cv.height;
        const img = ctx.createImageData(W, H);

        if (reducir) { dibujar(ctx, img, W, H, 23); return; }

        let visible = true;
        if ('IntersectionObserver' in window) {
            new IntersectionObserver(([e]) => { visible = e.isIntersecting; }).observe(cv);
        }
        const t0 = performance.now();
        const bucle = (now) => {
            if (visible && !document.hidden) dibujar(ctx, img, W, H, ((now - t0) / 1000) * 0.35 + 23);
            requestAnimationFrame(bucle);
        };
        requestAnimationFrame(bucle);
    });
}
