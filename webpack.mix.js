const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/scss/app.scss', 'public/css')
    .webpackConfig(require('./webpack.config'))
    .sourceMaps();

if (mix.inProduction()) {
    mix.version();
}
