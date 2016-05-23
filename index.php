<?php
ini_set('display_errors', 'on');
require __DIR__ . '/vendor/autoload.php';

$app = new \Tilex\Application();

$app->get('/hello/{name}', function($name) use($app) {
    return 'Hello '.$app->escape($name);
});

$app->get('/test', function() use($app) {
    return 'WAZZZAAAAAAAAA';
});

$app->run();
