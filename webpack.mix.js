const path = require('path');
const mix = require('laravel-mix');



mix.webpackConfig({
   output: {
      chunkFilename: 'js/chunks/[name].[contenthash].js',
   },
   resolve: {
      alias: {
         "@": "..",
         'static': path.resolve(__dirname, 'resources/static/'),
      }
   }
});

mix.js('resources/js/app.js', 'public/js')
   .sass('resources/sass/app.scss', 'public/css');

mix.copy('resources/coreui/public', 'public');

if (mix.inProduction()) {
    mix.version();
} else {
    mix.version();
}


