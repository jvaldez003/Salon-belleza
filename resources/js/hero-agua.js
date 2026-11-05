/**
 * Foto principal del inicio dibujada con WebGL:
 *  - Transición entre banners como una onda de agua que nace donde está el cursor (o donde se tocó).
 *  - Acercamiento lento de la foto activa (efecto Ken Burns) y leve desplazamiento con el mouse.
 *  - Reflejo de luz de agua (cáusticas) y una ondulación muy suave, como vista bajo el agua.
 *
 * Si el navegador no tiene WebGL, `HeroAgua.crear` devuelve null y la página usa las fotos normales.
 */

const VERTICES = `
attribute vec2 aPos;
varying vec2 vUv;
void main() {
    vUv = vec2(aPos.x * .5 + .5, .5 - aPos.y * .5); // (0,0) = esquina superior izquierda
    gl_Position = vec4(aPos, 0., 1.);
}`;

const FRAGMENTOS = `
precision highp float;
varying vec2 vUv;
uniform sampler2D uT0;
uniform sampler2D uT1;
uniform vec2 uRes;
uniform vec2 uS0;
uniform vec2 uS1;
uniform float uP;
uniform float uTiempo;
uniform float uZ0;
uniform float uZ1;
uniform vec2 uRaton;
uniform vec2 uOrigen;
uniform vec2 uFoco;

// Recorta la foto para cubrir el marco (como object-fit: cover) con punto de foco y zoom.
vec2 cubrir(vec2 uv, vec2 img, float zoom) {
    float ra = uRes.x / uRes.y;
    float ia = img.x / img.y;
    vec2 s = ra > ia ? vec2(1., ia / ra) : vec2(ra / ia, 1.);
    s /= zoom;
    // Igual que object-position: el punto de foco de la foto queda en el mismo punto relativo del marco
    vec2 c = clamp((1. - s) * uFoco + s * .5 + uRaton * .02, s * .5, 1. - s * .5);
    return c + (uv - .5) * s;
}

// Cáusticas: la red de luz que se forma en el fondo de una piscina.
float causticas(vec2 uv, float t) {
    vec2 p = uv * 6.28318 * vec2(1.1, 1.4) - 250.;
    vec2 i = p;
    float c = 1.;
    float inten = .005;
    for (int n = 0; n < 4; n++) {
        float tt = t * (1. - (3.5 / float(n + 1)));
        i = p + vec2(cos(tt - i.x) + sin(tt + i.y), sin(tt - i.y) + cos(tt + i.x));
        c += 1. / length(vec2(p.x / (sin(i.x + tt) / inten), p.y / (cos(i.y + tt) / inten)));
    }
    c = 1.17 - pow(c / 4., 1.4);
    return min(1., pow(abs(c), 8.));
}

void main() {
    vec2 uv = vUv;
    float t = uTiempo;

    // Ondulación permanente muy leve
    uv += vec2(sin(uv.y * 9. + t * 1.3), cos(uv.x * 11. - t * 1.1)) * .0016;

    // Onda de la transición
    vec2 asp = vec2(uRes.x / uRes.y, 1.);
    vec2 d = (vUv - uOrigen) * asp;
    float dist = length(d);
    float lejos = length(max(uOrigen, 1. - uOrigen) * asp);
    float r = uP * (lejos + .3);
    float frente = r - dist;
    float banda = exp(-frente * frente * 140.);
    float vida = sin(uP * 3.14159);
    vec2 dir = dist > .0001 ? (d / dist) / asp : vec2(0.);
    vec2 empuje = dir * sin((dist - r) * 48.) * banda * vida * .028;

    vec3 sale = texture2D(uT0, cubrir(uv + empuje, uS0, uZ0)).rgb;
    vec3 entra = texture2D(uT1, cubrir(uv - empuje * .6, uS1, uZ1 + (1. - uP) * .08)).rgb;
    vec3 col = mix(sale, entra, smoothstep(-.015, .05, frente));

    // Anillo de luz en el frente de la onda
    col += vec3(.75, .93, 1.) * banda * vida * .28;

    // Luz de agua, más intensa arriba (cerca de la superficie)
    float desvanece = clamp((1. - vUv.y) * 1.6, 0., 1.);
    float c = causticas(vUv, t * .35 + 23.);
    col = 1. - (1. - col) * (1. - vec3(.745, .925, 1.) * c * .45 * desvanece);

    gl_FragColor = vec4(col, 1.);
}`;

const DURACION = 1800;     // ms de la onda entre fotos
const ZOOM_BASE = 1;       // arranca igual a la foto normal, para que el cambio a WebGL no se note
const ZOOM_EXTRA = .08;    // cuánto se acerca la foto mientras está activa (da margen al movimiento del mouse)
const ZOOM_TIEMPO = 9000;  // ms del acercamiento completo
const MAX_LADO = 2048;     // las fotos grandes se reducen antes de subirlas a la GPU

const suave = (x) => (x < .5 ? 4 * x * x * x : 1 - Math.pow(-2 * x + 2, 3) / 2);
const salida = (x) => 1 - Math.pow(1 - x, 3);

export class HeroAgua {
    /** @returns {HeroAgua|null} */
    static crear(lienzo, urls, eventos = {}) {
        let gl = null;
        try {
            gl = lienzo.getContext('webgl', { alpha: false, antialias: false, premultipliedAlpha: false, preserveDrawingBuffer: false });
        } catch { return null; }
        if (!gl) return null;
        try {
            return new HeroAgua(lienzo, gl, urls, eventos);
        } catch (e) {
            console.warn('Hero sin WebGL:', e);
            return null;
        }
    }

    constructor(lienzo, gl, urls, eventos) {
        this.lienzo = lienzo;
        this.gl = gl;
        this.eventos = eventos;
        this.prog = this.programa(VERTICES, FRAGMENTOS);
        gl.useProgram(this.prog);

        const buf = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, buf);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, 1, 1]), gl.STATIC_DRAW);
        const aPos = gl.getAttribLocation(this.prog, 'aPos');
        gl.enableVertexAttribArray(aPos);
        gl.vertexAttribPointer(aPos, 2, gl.FLOAT, false, 0, 0);

        this.u = {};
        ['uT0', 'uT1', 'uRes', 'uS0', 'uS1', 'uP', 'uTiempo', 'uZ0', 'uZ1', 'uRaton', 'uOrigen', 'uFoco']
            .forEach((n) => { this.u[n] = gl.getUniformLocation(this.prog, n); });
        gl.uniform1i(this.u.uT0, 0);
        gl.uniform1i(this.u.uT1, 1);
        gl.uniform2f(this.u.uFoco, .5, .3);

        const ahora = performance.now();
        this.fotos = urls.map((url, i) => this.cargar(url, i));
        this.inicio = urls.map(() => ahora);
        this.actual = 0;
        this.siguiente = 0;
        this.tTransicion = null;
        this.origen = [.5, .62];
        this.raton = { x: 0, y: 0, ox: 0, oy: 0 };
        this.visible = true;
        this.primerCuadro = false;

        this.ajustar();
        this.observadorTam = new ResizeObserver(() => this.ajustar());
        this.observadorTam.observe(lienzo);
        if ('IntersectionObserver' in window) {
            this.observadorVis = new IntersectionObserver(([e]) => { this.visible = e.isIntersecting; });
            this.observadorVis.observe(lienzo);
        }
        lienzo.addEventListener('webglcontextlost', (e) => {
            e.preventDefault();
            this.detenido = true;
            this.eventos.fallo?.();
        });

        this.bucle = this.bucle.bind(this);
        requestAnimationFrame(this.bucle);
    }

    programa(vs, fs) {
        const gl = this.gl;
        const sombreador = (tipo, fuente) => {
            const s = gl.createShader(tipo);
            gl.shaderSource(s, fuente);
            gl.compileShader(s);
            if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) throw new Error(gl.getShaderInfoLog(s));
            return s;
        };
        const p = gl.createProgram();
        gl.attachShader(p, sombreador(gl.VERTEX_SHADER, vs));
        gl.attachShader(p, sombreador(gl.FRAGMENT_SHADER, fs));
        gl.linkProgram(p);
        if (!gl.getProgramParameter(p, gl.LINK_STATUS)) throw new Error(gl.getProgramInfoLog(p));
        return p;
    }

    cargar(url, indice) {
        const gl = this.gl;
        const foto = { tex: gl.createTexture(), w: 1, h: 1, lista: false };
        gl.bindTexture(gl.TEXTURE_2D, foto.tex);
        // Color de espera (bruma-honda) mientras llega la foto
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, 1, 1, 0, gl.RGBA, gl.UNSIGNED_BYTE, new Uint8Array([217, 234, 242, 255]));
        [[gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE], [gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE],
         [gl.TEXTURE_MIN_FILTER, gl.LINEAR], [gl.TEXTURE_MAG_FILTER, gl.LINEAR]]
            .forEach(([k, v]) => gl.texParameteri(gl.TEXTURE_2D, k, v));

        const img = new Image();
        img.decoding = 'async';
        img.onload = () => {
            let fuente = img;
            let w = img.naturalWidth, h = img.naturalHeight;
            const escala = Math.min(1, MAX_LADO / Math.max(w, h));
            if (escala < 1) {
                w = Math.round(w * escala); h = Math.round(h * escala);
                const c = document.createElement('canvas');
                c.width = w; c.height = h;
                c.getContext('2d').drawImage(img, 0, 0, w, h);
                fuente = c;
            }
            gl.bindTexture(gl.TEXTURE_2D, foto.tex);
            gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, gl.RGBA, gl.UNSIGNED_BYTE, fuente);
            Object.assign(foto, { w, h, lista: true });
            if (indice === this.actual) this.inicio[indice] = performance.now();
        };
        img.onerror = () => { if (indice === 0) this.eventos.fallo?.(); };
        img.src = url;
        return foto;
    }

    ajustar() {
        const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
        const w = Math.max(1, Math.round(this.lienzo.clientWidth * dpr));
        const h = Math.max(1, Math.round(this.lienzo.clientHeight * dpr));
        if (this.lienzo.width !== w || this.lienzo.height !== h) {
            this.lienzo.width = w;
            this.lienzo.height = h;
        }
    }

    /** Cambia a la foto n. `origen` = [x, y] de 0 a 1 donde nace la onda. */
    ir(n, origen) {
        if (this.tTransicion !== null) this.actual = this.siguiente;
        if (n === this.actual) return;
        this.siguiente = n;
        this.tTransicion = performance.now();
        this.inicio[n] = this.tTransicion;
        this.origen = origen || [.5, .62];
    }

    /** Posición del mouse de -1 a 1 (0,0 = centro). */
    mover(x, y) {
        this.raton.x = x;
        this.raton.y = y;
    }

    zoom(i, ahora) {
        return ZOOM_BASE + ZOOM_EXTRA * salida(Math.min(1, (ahora - this.inicio[i]) / ZOOM_TIEMPO));
    }

    bucle(ahora) {
        if (this.detenido) return;
        requestAnimationFrame(this.bucle);
        if (!this.visible || document.hidden) return;
        if (!this.fotos[this.actual].lista && !this.primerCuadro) return;

        const gl = this.gl;
        let p = 0;
        if (this.tTransicion !== null) {
            const lineal = Math.min(1, Math.max(0, (ahora - this.tTransicion) / DURACION));
            p = suave(lineal);
            if (lineal >= 1) {
                this.actual = this.siguiente;
                this.tTransicion = null;
                p = 0;
            }
        }
        const a = this.fotos[this.actual];
        const b = this.fotos[this.tTransicion !== null ? this.siguiente : this.actual];

        // El desplazamiento con el mouse sigue al cursor con retraso, para que se sienta líquido
        this.raton.ox += (this.raton.x - this.raton.ox) * .05;
        this.raton.oy += (this.raton.y - this.raton.oy) * .05;

        gl.viewport(0, 0, this.lienzo.width, this.lienzo.height);
        gl.uniform2f(this.u.uRes, this.lienzo.width, this.lienzo.height);
        gl.uniform2f(this.u.uS0, a.w, a.h);
        gl.uniform2f(this.u.uS1, b.w, b.h);
        gl.uniform1f(this.u.uZ0, this.zoom(this.actual, ahora));
        gl.uniform1f(this.u.uZ1, this.zoom(this.tTransicion !== null ? this.siguiente : this.actual, ahora));
        gl.uniform1f(this.u.uP, p);
        gl.uniform1f(this.u.uTiempo, ahora / 1000);
        gl.uniform2f(this.u.uRaton, this.raton.ox, this.raton.oy);
        gl.uniform2f(this.u.uOrigen, this.origen[0], this.origen[1]);

        gl.activeTexture(gl.TEXTURE0);
        gl.bindTexture(gl.TEXTURE_2D, a.tex);
        gl.activeTexture(gl.TEXTURE1);
        gl.bindTexture(gl.TEXTURE_2D, b.tex);
        gl.drawArrays(gl.TRIANGLE_STRIP, 0, 4);

        if (!this.primerCuadro && a.lista) {
            this.primerCuadro = true;
            this.eventos.listo?.();
        }
    }
}
