const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    future: {
        removeDeprecatedGapUtilities: true,
        purgeLayersByDefault: true,
    },
    purge: [
        //if Jetstream
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        //endif Jetstream
        './config/tall-forms.php', //your config for this package
        './vendor/tanthammar/tall-forms/**/*.php', //this package files
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    variants: {
        opacity: ['responsive', 'hover', 'focus', 'disabled'],
    },

    plugins: [
        require('@tailwindcss/ui'),
        require('@tailwindcss/custom-forms')
    ],
};
