<?php

use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;
date_default_timezone_set('Europe/Madrid');

$app['twig.path'] = array(__DIR__ . '/../templates');
$app['twig.options'] = [];

$app['db.options'] = [
    "driver" => "pdo_mysql",
    "host" => 'localhost',
    "user" => 'root',
    "password" => '',
    "dbname" => 'mpwar_performance_blog',
    "charset" => "utf8",
];
$app['orm.proxies_dir'] = '/tmp/proxies';
$app['orm.auto_generate_proxies'] = true;
$app['orm.em.options'] = [
    "mappings" => [
        [
            "type" => "simple_yml",
            "namespace" => "Performance",
            "path" => __DIR__ . "/../../src/Performance/Infrastructure/Database/mappings",
        ],
    ],
];

$app['db.redis.options'] = [
    "host" => "127.0.0.1",
    "port" => "6379",
];

$redisClient = new Redis();
$redisClient->connect($app['db.redis.options']['host']);

$app['session.storage.handler'] = new RedisSessionHandler($redisClient, 60 * 60 * 24 * 7);
