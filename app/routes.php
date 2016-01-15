<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('index');