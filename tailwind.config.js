/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./assets/**/*.js",
        "./templates/**/*.html.twig",
        "./vendor/tales-from-a-dev/flowbite-bundle/templates/**/*.html.twig",
    ],
    theme: {
        extend: {
            keyframes: {
                wiggle: {
                    '0%, 100%': {transform: 'rotate(-5deg)'},
                    '50%': {transform: 'rotate(5deg)'},
                }
            },
            animation: {
                wiggle: 'wiggle 1s ease-in-out infinite',
            },
        },
    },
    plugins: []
}
