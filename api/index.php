<?php
spl_autoload_register(); // don't load our classes unless we use them
require_once __DIR__ . '/Include/loader.php';
require_once __DIR__ . '/_env.php';

use Jacwright\RestServer\RestServer;

$mode = 'production'; // 'debug' or 'production'
$server = new RestServer($mode);
// $server->refreshCache(); // uncomment momentarily to clear the cache if classes change in production mode

$server->addClass('VotersController', '/voters');
$server->useCors = true;
$server->allowedOrigin = '*';

$server->handle();
