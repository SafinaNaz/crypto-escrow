const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.js('resources/js/app.js', 'public/node/js')
//     .sass('resources/sass/app.scss', 'public/node/css')
//     .sourceMaps();

mix.styles(
    [
        'public/frontend/assets/vendor/bootstrap/css/bootstrap.min.css',
        'public/frontend/assets/vendor/icofont/icofont.min.css',
        'public/frontend/assets/vendor/boxicons/css/boxicons.min.css',
        'public/backend/plugins/fontawesome-free/css/all.min.css',

        'public/frontend/assets/css/remodal.min.css',
        'public/frontend/assets/css/remodal-default-theme.min.css',
        'public/frontend/assets/vendor/aos/aos.css',

        'public/frontend/assets/css/animate.min.css',
        'public/frontend/assets/css/style.css',

    ],
    'public/frontend/assets/css/app.min.css'
);

mix.styles(
    [
        'public/frontend/dashboard/css/vendor.bundle.css',
        'public/frontend/dashboard/css/style.css',
    ],
    'public/frontend/dashboard/css/dashboard.min.css'
);
