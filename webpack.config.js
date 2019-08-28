var Encore = require('@symfony/webpack-encore');

Encore

    .setOutputPath('public/build/')

    .setPublicPath('/build')


    .addEntry('js/index', './assets/js/index.js')
    .addEntry('js/profile', './assets/js/profile.js')
    .addStyleEntry('css/style',['./assets/css/style.css'])
    .addStyleEntry('css/index',['./assets/css/index.css'])
    .addStyleEntry('css/registerpage',['./assets/css/registerpage.css'])
    .addStyleEntry('css/loginpage',['./assets/css/loginpage.css'])
    .addStyleEntry('css/style2',['./assets/css/style2.css'])
    .addStyleEntry('css/profile',['./assets/css/profile.css'])

    .enableSassLoader()


    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()


    .enableVueLoader()
    .enableVersioning()


;

module.exports = Encore.getWebpackConfig();

