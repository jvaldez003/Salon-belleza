import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/*
 | Sistema de diseño Arrecife — "descenso al arrecife"
 | La página empieza clara (superficie) y baja hacia el azul abismo.
 |
 | Usa solo estos nombres (superficie, bruma, laguna, agua, oceano, abismo, tinta, texto, linea).
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
                abismo: {
                    DEFAULT: '#0A2F6B',           // títulos, secciones oscuras, pie
                    fondo:   '#071F49',           // el fondo del mar, al final del pie de página
                },
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
