<?php

use Symfony\Component\Debug\Debug;

ini_set('display_errors', 1);
require_once __DIR__ . '/../vendor/autoload.php';
$app = require __DIR__ . '/../src/app.php';
require __DIR__ . '/../resources/config/prod.php';
require __DIR__ . '/../src/controllers.php';
require __DIR__ . '/../src/redis_ini.php';

$app->run();
