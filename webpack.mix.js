const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('tailwindcss'),
        require('autoprefixer'),
    ]);

// THÊM ĐOẠN NÀY ĐỂ FIX LỖI PROGRESS PLUGIN
mix.webpackConfig({
    stats: {
        children: true,
    }
});

// Tắt hoàn toàn thông báo progress gây lỗi schema
mix.options({
    processCssUrls: false,
    terser: {
        extractComments: false,
    }
});
