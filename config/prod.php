<?php

// configure your app for the production environment

$app['twig.path'] = array(__DIR__.'/../templates');
$app['twig.options'] = array('auto_reload' => true, 'cache' => __DIR__.'/../var/cache/twig');
