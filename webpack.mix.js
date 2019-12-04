let mix = require('laravel-mix');

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

mix.js('resources/assets/js/app.js', 'public/js')
   .js('resources/assets/js/case-search-18558.js', 'public/js')
   .js('resources/assets/js/case-submit-18558.js', 'public/js')
   .js('resources/assets/js/app-checkout-18558.js', 'public/js')
   .js('resources/assets/js/jquery-3.4.1-min.js', 'public/js')
   .js('resources/assets/js/bootstrap-3.3.7-min.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
   .sass('resources/assets/sass/web.scss', 'public/css')
   .copyDirectory('resources/assets/images/submissions', 'storage/app/submissions')
   .copyDirectory('resources/assets/images/aerzte', 'storage/app/aerzte')
   .copy('resources/oauth-private.key', 'storage/oauth-private.key')
   .copy('resources/oauth-public.key', 'storage/oauth-public.key');
