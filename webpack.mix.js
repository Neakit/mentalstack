/*
 |--------------------------------------------------------------------------
 | Entry Point For Webpack Mix
 |--------------------------------------------------------------------------
 |
 | Specify APP_SECTION to 'web' or 'admin' at scripts section in your package.json
 | file to define type of build process
 |
 */

if(process.env.APP_SECTION){
    require(`./webpack.mix.${process.env.APP_SECTION}.js`);
}
