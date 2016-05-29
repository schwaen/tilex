<?php
ini_set('display_errors', 'on');
require __DIR__ . '/vendor/autoload.php';

$app = new \Tilex\Application([
    'servies' => [
        'Tilex\\Provider\\AnnotationServiceProvider' => [
            'annotation.dirs' => [__DIR__.'/Controller']
        ]
    ]
]);

$app->get('/', function() use($app) {
    return 'index';
});

$app->run();
