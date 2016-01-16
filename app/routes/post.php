<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

$app->post('/signup', function (Request $request) use ($app) {
    return $app->redirect($app['url_generator']->generate('index'));
})->bind('signup_post');