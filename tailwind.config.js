import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans:    ['Poppins', ...defaultTheme.fontFamily.sans],
                display: ['Playfair Display', ...defaultTheme.fontFamily.serif],
            },
            colors: {
                // Primary action color (was dusty rose, now bright blue)
                rose: {
                    primary: '#008FE8',
                    light:   '#7FD8FF',
                    dark:    '#0E4A9E',
                    deeper:  '#0A2F6B',
                },
                // Secondary accent (was warm gold, now ocean accent)
                gold: {
                    primary: '#008FE8',
                    light:   '#7FD8FF',
                    dark:    '#0E4A9E',
                    deeper:  '#0A2F6B',
                },
                // Background (was warm cream, now light blue-white)
                cream: {
                    DEFAULT: '#F8FCFF',
                    dark:    '#EBF5FC',
                    darker:  '#D9EAF2',
                },
                // Neutral scale (was warm browns, now cool grays)
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
        },
    },

    plugins: [forms],
};
