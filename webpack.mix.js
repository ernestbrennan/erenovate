const mix = require('laravel-mix');

mix.webpackConfig(require('./webpack.config'));

mix.copy('resources/js/main/functions.js', 'public/js')
  .sass('resources/sass/app.scss', 'public/css');

mix
  .js('resources/js-main/main.js', 'public/js/main.js')
    // .js('resources/js/main/app.js', 'public/js/main.js')
    .js('resources/js/admin/app.js', 'public/js/admin.js')
  .extract([
    'vue',
    'axios',
    'vuex',
    'vue-router',
  ])
  .sourceMaps()
  .version();
