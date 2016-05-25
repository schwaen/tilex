<?php
ini_set('display_errors', 'on');
require __DIR__ . '/vendor/autoload.php';

$app = new \Tilex\Application([
]);

$app->get('/', function() use($app) {
  echo '<pre>';
    $a = new \Tilex\Annotation\AnnotationHandler($app);
    print_r($a->listClasses([__DIR__.'/Controller']));
    
    return '';
});

$app->run();
/*
echo '<pre>';
\Doctrine\Common\Annotations\AnnotationRegistry::registerLoader(function ($class) { return class_exists($class); });
$reader = new \Doctrine\Common\Annotations\AnnotationReader();

$rc = new ReflectionClass('\Tilex\Huch');
$rm = new ReflectionMethod(new \Tilex\Huch(), 'foo');


//print_r($reader->getClassAnnotations($rc));
print_r($reader->getMethodAnnotations($rm));

//var_dump($reader);

$huch = new \Tilex\Huch();

var_dump($huch->foo());
*/
