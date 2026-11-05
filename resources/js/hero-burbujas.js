/**
 * Partículas en 3D alrededor del arco del inicio: burbujas y plancton flotando a distintas profundidades.
 *
 * Una sola nube de partículas se dibuja en dos lienzos:
 *   - "atrás"  (detrás del arco y del texto): las partículas lejanas
 *   - "frente" (delante del arco): las cercanas, más grandes y desenfocadas
 * La cámara gira un poco con el mouse (yaw/pitch) y, al bajar por la página, las burbujas suben más rápido.
 * Todo el movimiento se calcula en la GPU; la CPU solo pasa tiempo, giro y avance.
 *
 * Uso: const b = HeroBurbujas.crear(lienzoAtras, lienzoFrente, { cantidad: 45 });
 *      b.actualizar({ mx, my, hundir })  en cada cuadro (mx/my de -1 a 1, hundir de 0 a 1)
 */

const VERT = `
attribute vec4 aPos;    // x, y, z, tamaño
attribute vec4 aDatos;  // velocidad, fase, tipo (0 plancton, 1 burbuja), brillo
uniform float uTiempo;
uniform float uAvance;
uniform float uYaw;
uniform float uPitch;
uniform float uAspecto;
uniform float uAlto;
uniform vec2 uCapa;     // rango de profundidad que dibuja este lienzo
varying float vTipo;
varying float vAlfa;
varying float vDesenfoque;

const float PIVOTE = 3.2;   // profundidad del arco

void main() {
    float y = mod(aPos.y + uAvance * aDatos.x + 1.3, 2.6) - 1.3;
    float x = aPos.x + sin(uTiempo * .55 + aDatos.y) * .07;
    vec3 p = vec3(x, y, aPos.z - PIVOTE);

    float cy = cos(uYaw), sy = sin(uYaw);
    p = vec3(p.x * cy - p.z * sy, p.y, p.x * sy + p.z * cy);
    float cp = cos(uPitch), sp = sin(uPitch);
    p = vec3(p.x, p.y * cp - p.z * sp, p.y * sp + p.z * cp);
    p.z += PIVOTE;

    if (p.z < uCapa.x || p.z >= uCapa.y || p.z < .4) {
        gl_Position = vec4(2., 2., 2., 1.);
        gl_PointSize = 0.;
        return;
    }

    gl_Position = vec4(p.x / p.z * 1.6 / uAspecto, p.y / p.z * 1.6, 0., 1.);
    gl_PointSize = aPos.w * uAlto * .018 / p.z;

    vTipo = aDatos.z;
    vDesenfoque = clamp(abs(p.z - PIVOTE) / 2.6, 0., 1.);
    // lo muy lejano se desvanece en el agua; lo muy cercano también (para no tapar)
    vAlfa = aDatos.w * smoothstep(7., 4.5, p.z) * smoothstep(.5, 1.3, p.z);
}`;

const FRAG = `
precision mediump float;
varying float vTipo;
varying float vAlfa;
varying float vDesenfoque;

void main() {
    vec2 c = gl_PointCoord - .5;
    float d = length(c) * 2.;
    if (d > 1.) discard;
    float suave = mix(.08, .7, vDesenfoque);
    vec3 laguna = vec3(0., .56, .91);
    vec3 luz = vec3(.92, .97, 1.);
    vec4 col;
    if (vTipo > .5) {
        // Burbuja: aro azul, interior apenas visible y un brillo blanco arriba a la izquierda
        float aro = smoothstep(1., 1. - suave * .6 - .12, d) * smoothstep(.45, .95, d);
        float interior = smoothstep(1., .2, d) * .1;
        float brillo = smoothstep(.32, .0, length(c - vec2(-.16, -.18)) * 2.);
        col.rgb = laguna * aro * .75 + luz * (interior + brillo * .9);
        col.a = clamp(aro * .7 + interior + brillo * .8, 0., 1.);
    } else {
        // Plancton: punto suave
        float a = smoothstep(1., 1. - suave - .25, d);
        col.rgb = mix(laguna, luz, .35) * a;
        col.a = a * .7;
    }
    gl_FragColor = col * vAlfa;   // alfa premultiplicado
}`;

function compilar(gl) {
    const sombreador = (tipo, fuente) => {
        const s = gl.createShader(tipo);
        gl.shaderSource(s, fuente);
        gl.compileShader(s);
        if (!gl.getShaderParameter(s, gl.COMPILE_STATUS)) throw new Error(gl.getShaderInfoLog(s));
        return s;
    };
    const p = gl.createProgram();
    gl.attachShader(p, sombreador(gl.VERTEX_SHADER, VERT));
    gl.attachShader(p, sombreador(gl.FRAGMENT_SHADER, FRAG));
    gl.linkProgram(p);
    if (!gl.getProgramParameter(p, gl.LINK_STATUS)) throw new Error(gl.getProgramInfoLog(p));
    return p;
}

// Generador con semilla: la nube es igual en cada carga
function aleatorio(semilla) {
    let s = semilla;
    return () => { s = (s * 16807) % 2147483647; return (s - 1) / 2147483646; };
}

class Capa {
    constructor(lienzo, datos, rango) {
        const gl = lienzo.getContext('webgl', { alpha: true, premultipliedAlpha: true, antialias: false });
        if (!gl) throw new Error('sin webgl');
        this.lienzo = lienzo;
        this.gl = gl;
        this.rango = rango;
        this.prog = compilar(gl);
        gl.useProgram(this.prog);

        const enlazar = (nombre, arreglo) => {
            const buf = gl.createBuffer();
            gl.bindBuffer(gl.ARRAY_BUFFER, buf);
            gl.bufferData(gl.ARRAY_BUFFER, arreglo, gl.STATIC_DRAW);
            const loc = gl.getAttribLocation(this.prog, nombre);
            gl.enableVertexAttribArray(loc);
            gl.vertexAttribPointer(loc, 4, gl.FLOAT, false, 0, 0);
        };
        enlazar('aPos', datos.pos);
        enlazar('aDatos', datos.extra);
        this.cantidad = datos.pos.length / 4;

        this.u = {};
        ['uTiempo', 'uAvance', 'uYaw', 'uPitch', 'uAspecto', 'uAlto', 'uCapa'].forEach((n) => { this.u[n] = gl.getUniformLocation(this.prog, n); });
        gl.enable(gl.BLEND);
        gl.blendFunc(gl.ONE, gl.ONE_MINUS_SRC_ALPHA);
        lienzo.addEventListener('webglcontextlost', (e) => { e.preventDefault(); this.perdido = true; });
    }

    ajustar(dpr) {
        const w = Math.max(1, Math.round(this.lienzo.clientWidth * dpr));
        const h = Math.max(1, Math.round(this.lienzo.clientHeight * dpr));
        if (this.lienzo.width !== w || this.lienzo.height !== h) { this.lienzo.width = w; this.lienzo.height = h; }
    }

    dibujar(e) {
        if (this.perdido) return;
        const gl = this.gl;
        gl.viewport(0, 0, this.lienzo.width, this.lienzo.height);
        gl.clearColor(0, 0, 0, 0);
        gl.clear(gl.COLOR_BUFFER_BIT);
        gl.uniform1f(this.u.uTiempo, e.tiempo);
        gl.uniform1f(this.u.uAvance, e.avance);
        gl.uniform1f(this.u.uYaw, e.yaw);
        gl.uniform1f(this.u.uPitch, e.pitch);
        gl.uniform1f(this.u.uAspecto, this.lienzo.width / this.lienzo.height);
        gl.uniform1f(this.u.uAlto, this.lienzo.height);
        gl.uniform2f(this.u.uCapa, this.rango[0], this.rango[1]);
        gl.drawArrays(gl.POINTS, 0, this.cantidad);
    }
}

export class HeroBurbujas {
    /** @returns {HeroBurbujas|null} */
    static crear(atras, frente, opciones = {}) {
        try {
            return new HeroBurbujas(atras, frente, opciones);
        } catch (e) {
            console.warn('Partículas del inicio sin WebGL:', e);
            return null;
        }
    }

    constructor(atras, frente, { cantidad = 45 } = {}) {
        const azar = aleatorio(20261105);
        const pos = new Float32Array(cantidad * 4);
        const extra = new Float32Array(cantidad * 4);
        for (let i = 0; i < cantidad; i++) {
            const burbuja = azar() < .12;
            const z = 1.7 + azar() * 5;
            pos.set([
                // más hacia el arco (derecha) que hacia el título; más ancho hacia el fondo (perspectiva)
                (azar() * 1.55 - .5) * (z * .75 + .4),
                azar() * 2.6 - 1.3,
                z,
                burbuja ? 4 + azar() * 6.5 : 1.6 + azar() * 2.6,
            ], i * 4);
            extra.set([
                burbuja ? .16 + azar() * .14 : .05 + azar() * .08,   // las burbujas suben más rápido
                azar() * 6.28,
                burbuja ? 1 : 0,
                burbuja ? .4 + azar() * .4 : .3 + azar() * .45,
            ], i * 4);
        }
        const datos = { pos, extra };
        this.capas = [new Capa(atras, datos, [3.05, 99]), new Capa(frente, datos, [0, 3.05])];
        this.avance = 0;
        this.ultimo = 0;
        this.yaw = 0;
        this.pitch = 0;
    }

    /** Dibuja un cuadro. mx/my: mouse de -1 a 1; hundir: 0 arriba de la página, 1 cuando el banner ya pasó. */
    actualizar({ mx = 0, my = 0, hundir = 0, ahora = performance.now() }) {
        const dt = this.ultimo ? Math.min(.05, (ahora - this.ultimo) / 1000) : 0;
        this.ultimo = ahora;
        this.avance += dt * (1 + hundir * 4);
        this.yaw = mx * .1;
        this.pitch = my * .07 - hundir * .12;
        const dpr = Math.min(window.devicePixelRatio || 1, 1.5);
        const estado = { tiempo: ahora / 1000, avance: this.avance, yaw: this.yaw, pitch: this.pitch };
        this.capas.forEach((c) => { c.ajustar(dpr); c.dibujar(estado); });
    }

    /** Al reanudar (pestaña oculta, fuera de pantalla) no saltar el tiempo acumulado. */
    pausar() {
        this.ultimo = 0;
    }
}
