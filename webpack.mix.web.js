const mix                 = require("laravel-mix");
const VuetifyLoaderPlugin = require("vuetify-loader/lib/plugin");

mix.setPublicPath('public/web/');

Mix.listen('configReady', config => {
    const scssRule = config.module.rules.find(r => r.test.toString() === /\.scss$/.toString());
    const scssOptions = scssRule.loaders.find(l => l.loader === 'sass-loader').options;
    scssOptions.data = '@import "./resources/web/sass/app.scss"';

    const sassRule = config.module.rules.find(r => r.test.toString() === /\.sass$/.toString());
    const sassOptions = sassRule.loaders.find(l => l.loader === 'sass-loader').options;
    sassOptions.data = '@import "./resources/web/sass/app.scss"';
});

mix.options({
    extractVueStyles: true,
    processCssUrls: false
})
    .webpackConfig({
        plugins: [new VuetifyLoaderPlugin()]
    })
    .js('resources/web/js/entry.js', 'js')
    .sass('resources/web/sass/app.scss', 'css');
