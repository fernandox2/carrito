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

/*mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');*/

    mix.styles([
        'public/argon/css/argon.css'
    ], 'public/css/plantilla.css')
    .scripts([
        'public/argon/vendor/jquery/dist/jquery.min.js',
        'public/argon/vendor/bootstrap/dist/js/bootstrap.bundle.min.js',
        'public/argon/js/argon.js'
    ], 'public/js/plantilla.js')
    .js(['resources/js/app.js'], 'public/js/app.js');
