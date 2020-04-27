const mix = require('laravel-mix');

mix.options({
    extractVueStyles: true,
    processCssUrls: false
})
    .setPublicPath('public/web/')
    .js('resources/web/js/entry.js', 'js')
    .sass('resources/web/sass/app.scss', 'css');
