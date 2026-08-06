import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    safelist: [
        {
        pattern: /bg-(yellow|red|blue|indigo|green|gray)-(100|900)/,
        },
        {
        pattern: /text-(yellow|red|blue|indigo|green|gray)-(800|300)/,
        },
        {
        pattern: /border-(yellow|red|blue|indigo|green|gray)-(200|700)/,
        },
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms],
};
