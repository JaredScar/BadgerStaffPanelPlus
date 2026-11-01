/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    corePlugins: {
        preflight: false,
    },
    theme: {
        extend: {
            colors: {
                brand: {
                    50: '#fff7ed',
                    100: '#ffedd5',
                    200: '#fed7aa',
                    400: '#fb923c',
                    500: '#fd7e14',
                    600: '#e8590c',
                    700: '#c2410c',
                },
                panel: {
                    800: '#1c1e22',
                    900: '#121316',
                },
            },
            boxShadow: {
                card: '0 10px 28px rgba(0, 0, 0, 0.16)',
                lift: '0 14px 32px rgba(0, 0, 0, 0.18)',
                nav: '6px 0 28px rgba(0, 0, 0, 0.12)',
            },
            fontFamily: {
                sans: ['Inter', 'Roboto', 'ui-sans-serif', 'system-ui', 'sans-serif'],
            },
            borderRadius: {
                panel: '14px',
            },
        },
    },
    plugins: [],
};
