const mix = require('laravel-mix');

mix.options({
        extractVueStyles: true,
        processCssUrls: false
    })
    .setPublicPath('public/admin/')
    .js('resources/admin/js/entry.js', 'js')
    .sass('resources/admin/sass/app.scss', 'css');
