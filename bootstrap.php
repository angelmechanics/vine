<?php

require_once __DIR__ . '/../vendor/silex/autoload.php';

use Silex\Application;
use Silex\Extension\TwigExtension;

$app = new Application();

// Register Vine classes for autoloading
$app['autoloader']->registerNamespaces(array(
  'Vine' => __DIR__,
));

// Register Twig Extensions
$app->register(new TwigExtension(), array(
  'twig.path'       => __DIR__.'/../views',
  'twig.class_path' => __DIR__.'/../vendor/silex/vendor/twig/lib',
  'twig.options'    => array(
    'debug' => true,
    'cache' => __DIR__.'/../cache',
  ),
));

return $app;