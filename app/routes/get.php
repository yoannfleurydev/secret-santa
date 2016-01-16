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

$app->get('/user/{id}/', function () use ($app) {
    return $app['twig']->render('user.html.twig');
});

$app->get('/logout', function () use ($app) {
    $app['session']->clear();

    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => 'Vous êtes déconnecté'
        )
    );

    return $app->redirect($app['url_generator']->generate('index'));
})->bind('logout');