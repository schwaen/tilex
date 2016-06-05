<?php
ini_set('display_errors', 'on');
require __DIR__ . '/vendor/autoload.php';

$app = new \Tilex\Application([
    'app.name' => 'MUUH',
    'servies' => [
        'Tilex\\Provider\\AnnotationServiceProvider' => [
            'annotation.dirs' => [__DIR__.'/Controller']
        ],
        'Tilex\\Provider\\CorsServiceProvider' => []
    ]
]);

$app->get('/', function() use($app) {
    return 'index';
});
$app->match('/test/cors', function() use ($app) {
    return $app->json([['greet'=>'World']]);
})->method('POST|PUT');

$app->get('/test_get', function() use ($app) {
    return __METHOD__;
});

$app->run();
