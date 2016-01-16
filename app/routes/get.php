<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

$app->get('/', function () use ($app) {
    return $app['twig']->render('index.html.twig');
})->bind('index');

$app->get('/signup', function () use ($app) {
    return $app['twig']->render('signup.html.twig');
})->bind('signup_get');

$app->get('/login', function () use ($app) {
    return $app['twig']->render('login.html.twig');
})->bind('login_get');