const mix = require('laravel-mix');
mix.disableNotifications();

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

// Application global styles and scripts with Bootstrap
mix.js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .css('resources/css/app.css', 'public/css');
    
// Abcjs styles and scripts
mix.scripts(['resources/js/abcjs-basic.js', 'resources/js/abcjs-editor.js'], 'public/js/abcjs.js')
    .css('resources/css/abcjs-editor.css', 'public/css/abcjs.css');
