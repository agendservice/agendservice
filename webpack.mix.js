const mix = require('laravel-mix');
const path = require('path');

// Carregar dependências do Tailwind CSS
require('laravel-mix-tailwind');

// Configura Laravel Mix para Vue.js 3 e Tailwind
mix.css('resources/css/app.css', 'public/css', [
      require('tailwindcss'),
      require('autoprefixer'),
   ])
   .js('resources/js/app.js', 'public/js')
   .vue()
   .version(); // para cache busting em produção

// Desabilitar notificações do Mix
mix.disableNotifications();

// Configuração do Webpack
mix.webpackConfig({
   resolve: {
      alias: {
         '@tailwindConfig': path.resolve(__dirname, 'tailwind.config.js'),
      },
      fallback: {
         "stream": require.resolve("stream-browserify")
      }
   },
});