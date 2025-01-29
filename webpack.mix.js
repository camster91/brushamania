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

mix.js('resources/js/app.js', 'public/js')
	.js('resources/js/registration', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .styles(['public/css/app.css',
    	'resources/paperdashboard/css/paper-dashboard.css',
    	'resources/paperdashboard/css/custom.css',
    	'resources/regform/vendor/font-awesome-4.7/css/font-awesome.css'], 'public/css/all.css')
    .styles(['public/css/app.css',
    	'resources/regform/vendor/mdi-font/css/material-design-iconic-font.css',
		'resources/regform/vendor/font-awesome-4.7/css/font-awesome.css',
		'resources/regform/vendor/select2/select2.css',
		'resources/regform/vendor/datepicker/daterangepicker.css',
		'resources/regform/css/main.css',
		'resources/regform/css/custom.css',
		'resources/regform/vendor/font-awesome-4.7/css/font-awesome.css'], 'public/css/reg.css')
	.scripts(['public/js/registration.js',
		'resources/regform/vendor/datepicker/moment.js',
		'resources/regform/vendor/datepicker/daterangepicker.js',
		'resources/regform/js/global.js'], 'public/js/reg.js')
	.scripts(['public/js/app.js'], 'public/js/all.js')
	.copyDirectory('resources/regform/vendor/mdi-font/fonts', 'public/fonts')
	.copyDirectory('resources/paperdashboard/fonts/', 'public/fonts')
	.copyDirectory('resources/regform/vendor/font-awesome-4.7/fonts', 'public/fonts').version();
