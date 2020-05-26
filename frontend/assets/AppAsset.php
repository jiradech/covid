<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [


        'template/covid-19/assets/css/animate.css',
        //'//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css',
        'https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-print-1.6.1/fc-3.3.0/datatables.min.css',

        //'https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css',
        'template/covid-19/assets/css/bootstrap.min.css',
        'template/covid-19/assets/css/all.min.css',

        'template/covid-19/assets/css/icofont.min.css',
        'template/covid-19/assets/css/lightcase.css',
        'template/covid-19/assets/css/swiper.min.css',
        'template/covid-19/assets/css/style.css',


        //'css/site.css',
    ];
    public $js = [

        //'template/covid-19/assets/js/jquery.js',
        //'//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js',
        'https://cdn.datatables.net/v/dt/dt-1.10.20/b-1.6.1/b-flash-1.6.1/b-print-1.6.1/fc-3.3.0/datatables.min.js',
        'template/covid-19/assets/js/fontawesome.min.js',
        'template/covid-19/assets/js/waypoints.min.js',
        'template/covid-19/assets/js/bootstrap.min.js',
        
        'template/covid-19/assets/js/lightcase.js',
        'template/covid-19/assets/js/isotope.pkgd.min.js',
        'template/covid-19/assets/js/swiper.min.js',
        'template/covid-19/assets/js/jquery.countdown.min.js',
        //'https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js',
        'template/covid-19/assets/js/functions.js',

        'https://cdn.datatables.net/buttons/1.6.1/js/dataTables.buttons.min.js',
        'https://cdn.datatables.net/buttons/1.6.1/js/buttons.flash.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
        'https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
        'https://cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js',
        'https://cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js',







    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
