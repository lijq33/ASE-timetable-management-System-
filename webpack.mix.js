let mix = require('laravel-mix');

let tailwindcss = require('tailwindcss');

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



// mix.styles([
  	// 'public/assets/plugins/bootstrap-datepicker/css/datepicker3.css',
    // 'public/assets/plugins/boostrapv3/css/bootstrap.min.css',
  	// 'public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.css',
    // 'public/assets/plugins/bootstrap-select2/select2.css',
    // 'public/assets/plugins/switchery/css/switchery.min.css',
    // 'public/assets/plugins/bootstrap-tag/bootstrap-tagsinput.css',
    // 'pblic/assets/plugins/lightbox/css/lightbox.css',  
    // 'public/assets/plugins/jquery-scrollbar/jquery.scrollbar.css',
// ], 'public/css');

// Tailwind CSS
mix.sass('resources/assets/sass/app.scss', 'public/css')
  .options({
    processCssUrls: false,
    postCss: [ tailwindcss('./tailwind.js') ],
});

mix.js([
	'resources/assets/js/app.js',
	'resources/assets/js/bootstrap.js',
  // 'public/assets/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
	// 'public/assets/plugins/lightbox/js/lightbox.min.js',
	// 'public/assets/plugins/bootstrap-timepicker/bootstrap-timepicker.min.js',
	// 'public/assets/plugins/boostrap-form-wizard/js/jquery.bootstrap.wizard.min.js',
	// 'public/assets/plugins/jquery-inputmask/jquery.inputmask.min.js',
	// 'public/assets/plugins/bootstrap-tag/bootstrap-tagsinput.js',
	// 'public/js/jquery.autosize.min',
], 'public/js');
