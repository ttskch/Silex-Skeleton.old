<?php

ini_set('display_errors', 0);

require_once __DIR__.'/../vendor/autoload.php';

require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/config-local.php';

$app = require __DIR__.'/../src/app.php';
require __DIR__.'/../config/prod.php';
require __DIR__.'/../src/controllers.php';
require __DIR__ . '/../src/translations.php';
$app->run();
