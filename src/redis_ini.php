<?php
use Symfony\Component\HttpFoundation\Session\Storage\Handler\RedisSessionHandler;

$redisClient = new Redis();
$redisClient->connect($app['db.redis.options']['host']);

$app['session.storage.handler'] = new RedisSessionHandler($redisClient, 60 * 60 * 24 * 7);