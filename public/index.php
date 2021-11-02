<?php

define('BASE', dirname(__DIR__, 1));

require_once BASE . '/resources/helpers.php';
require_once BASE . '/autoload.php';

use Template\Core\{Router, Request, App};
use Template\App\Data;

App::bind('data', new Data(BASE . '/resources/data.json'));

try {
    Router::load(BASE . '/resources/routes.php')
        ->direct(Request::uri(), Request::method());
} catch (Exception $exception) {
    die($exception->getMessage());
}

