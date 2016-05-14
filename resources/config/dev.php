<?php

use Silex\Provider;

// include the prod configuration
require __DIR__ . '/prod.php';

// enable the debug mode
$app['debug'] = true;

$app->register(new Provider\HttpFragmentServiceProvider());
$app->register(new Provider\WebProfilerServiceProvider());
$app->register(new Sorien\Provider\DoctrineProfilerServiceProvider());

$app['db.options'] = [
    "driver" => "pdo_mysql",
    "host" => 'localhost',
    "user" => 'root',
    "password" => '',
    "dbname" => 'mpwar_performance_blog',
    "charset" => "utf8",
];

$app['profiler.cache_dir'] 			= __DIR__ . '/../../var/cache/profiler';
$app['profiler.mount_prefix'] 		= '/_profiler';