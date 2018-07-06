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
   .sass('resources/assets/sass/app.scss', 'public/css');

mix.styles([
    'public/components/bootstrap/dist/css/bootstrap.css',
    'public/css/style.css',
    'public/css/animate.css',
    'public/css/pe-icon-7-stroke.css',
    'public/css/fontello.css',
    'public/css/socicon.css',
    'public/css/iconsmind.css',
    'public/components/smartwizard/dist/css/smart_wizard.css',
    'public/components/smartwizard/dist/css/smart_wizard_theme_dots.css'
], 'public/css/all.css');

mix.scripts([
    'public/components/popper.js/dist/umd/popper.min.js',
    'public/components/bootstrap/dist/js/bootstrap.min.js',
    'public/components/tether/dist/js/tether.min.js',
    'public/components/ie10-viewport-bug-workaround/dist/ie10-viewport-bug-workaround.js',
    'public/components/smartwizard/dist/js/jquery.smartWizard.min.js',
    'public/js/main.js',
    'public/js/flickity.min.js',
    'public/js/easypiechart.min.js',
    'public/js/parallax.js',
    'public/js/typed.min.js',
    'public/js/datepicker.js',
    'public/js/isotope.min.js',
    'public/js/ytplayer.min.js',
    'public/js/lightbox.min.js',
    'public/js/granim.min.js',
    'public/js/countdown.min.js',
    'public/js/twitterfetcher.min.js',
    'public/js/spectragram.min.js',
    'public/js/smooth-scroll.min.js',
    'public/js/scripts.js'
], 'public/js/all.js');


/**
 * Build vue community components
 */
mix.js('resources/assets/community/js/community.js', 'public/js/community/app.js');
