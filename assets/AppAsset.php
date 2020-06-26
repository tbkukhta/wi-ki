<?php

namespace app\assets;

use yii\web\AssetBundle;

/**
 * Main application asset bundle.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/bootstrap/bootstrap-clearmin.min.css',
        'css/font-awesome.min.css',
        'css/roboto.css',
        'css/material-design.css',
        'css/small-n-flat.css',
        'css/editors/all.min.css',
        'css/editors/tomorrow-night-eighties.css',
        'css/global.css',
        'css/bootstrap-datepicker3.css',
        'css/fileinput.min.css',
        'css/comment/pagination.css',
        'css/comment/comment.css'
    ];
    public $js = [
        'js/jquery/jquery.cookie.min.js',
        'js/jquery/jquery.mousewheel.min.js',
        'js/bootstrap/bootstrap.min.js',
        'js/global/clearmin.min.js',
        'js/global/fastclick.min.js',
        'js/global/highlight.pack.js',
        'js/ckeditor/ckeditor.js',
        'js/datepicker/bootstrap-datepicker.js',
        'js/datepicker/locales/bootstrap-datepicker.ru.js',
        'js/fileinput/fileinput.min.js',
        'js/fileinput/locales/ru.js',
        'js/common.js',
        'js/app.js',
        'js/article.js',
        'js/comment/pagination.min.js',
        'js/comment/comment.js',
        'js/comment/filter.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}