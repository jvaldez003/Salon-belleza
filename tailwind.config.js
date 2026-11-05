import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/*
 | Sistema de diseño Arrecife — "descenso al arrecife"
 | La página empieza clara (superficie) y baja hacia el azul abismo.
 |
 | Usa SIEMPRE los nombres nuevos (superficie, bruma, laguna, agua, oceano, abismo, tinta, texto).
 | Los grupos rose / gold / cream / warm son alias heredados: se mantienen solo para no romper
 | las vistas que aún no se han migrado y se eliminarán al terminar el rediseño.
 */

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['Manrope', 'Segoe UI', ...defaultTheme.fontFamily.sans],
                display: ['"Bodoni Moda"', 'Didot', '"Bodoni 72"', 'Georgia', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // ── Paleta Arrecife (nombres nuevos) ─────────────────
                superficie: '#F8FCFF',            // fondo principal
                bruma: {
                    DEFAULT: '#EBF5FC',           // secciones
                    honda:   '#D9EAF2',           // secciones más profundas, fondos de foto
                },
                laguna:  '#008FE8',               // enlaces, foco, acentos
                agua:    '#7FD8FF',               // botones y detalles sobre fondo oscuro
                oceano:  '#0E4A9E',               // botones primarios, precios
                abismo:  '#0A2F6B',               // títulos, secciones oscuras, pie
                tinta:   '#0A2F6B',               // color de títulos
                texto: {
                    DEFAULT: '#2C4468',           // texto de párrafos
                    suave:   '#546C8C',           // texto secundario
                },
                linea:   '#C5DAE8',               // bordes y divisores
                'sobre-abismo': {
                    DEFAULT: '#EAF6FF',           // texto sobre abismo
                    suave:   '#A9C6E4',
                },

                // ── Alias heredados (no usar en código nuevo) ────────
                rose: {
                    primary: '#008FE8',
                    light:   '#7FD8FF',
                    dark:    '#0E4A9E',
                    deeper:  '#0A2F6B',
                },
                gold: {
                    primary: '#008FE8',
                    light:   '#7FD8FF',
                    dark:    '#0E4A9E',
                    deeper:  '#0A2F6B',
                },
                cream: {
                    DEFAULT: '#F8FCFF',
                    dark:    '#EBF5FC',
                    darker:  '#D9EAF2',
                },
                warm: {
                    50:  '#F8FCFF',
                    100: '#EBF5FC',
                    200: '#D9EAF2',
                    300: '#BFC6CC',
                    400: '#9CA3AF',
                    500: '#6B7280',
                    600: '#4B5563',
                    700: '#374151',
                    800: '#1F2937',
                    900: '#111827',
                },
            },
            borderRadius: {
                foto: '14px',                     // radio único para todas las fotos
            },
            aspectRatio: {
                '4/5':  '4 / 5',                  // tarjetas de servicio / categoría
                '3/4':  '3 / 4',                  // retratos (nosotros)
                '16/9': '16 / 9',                 // banners escritorio
            },
            maxWidth: {
                arrecife: '1180px',
            },
        },
    },

    plugins: [forms],
};
