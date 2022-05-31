module.exports = {
    content: [
        'templates/**/*.html.twig',
        'assets/**/*.ts',
        'assets/**/*.tsx',
        'src/**/*.php',
        // 'vendor/symfony/twig-bridge/Resources/views/Email/*.html.twig',
        'vendor/symfony/twig-bridge/Resources/views/Form/tailwind_2_layout.html.twig',
    ],
    theme: {
        extend: {},
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
}
