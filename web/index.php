<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../app/AppKernel.php";

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Debug\Debug;

if (!isset($_ENV['SYMFONY_ENV'])) {
    Dotenv::makeMutable();
    Dotenv::load(__DIR__ . '/../');
}

if ((bool) $_ENV['SYMFONY_DEBUG']) {
    Debug::enable();
}

$request  = Request::createFromGlobals();
$kernel   = new AppKernel($_ENV['SYMFONY_ENV'], (bool) $_ENV['SYMFONY_DEBUG']);
$response = $kernel->handle($request);
$response->send();
$kernel->terminate($request, $response);
