<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Local: public/ sits inside the Laravel app. Hosting: public_html/ is sibling to skripflow/.
$laravelRoot = file_exists(__DIR__.'/../vendor/autoload.php')
    ? dirname(__DIR__)
    : dirname(__DIR__).'/skripflow';

if (file_exists($maintenance = $laravelRoot.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $laravelRoot.'/vendor/autoload.php';

/** @var Application $app */
$app = require_once $laravelRoot.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
