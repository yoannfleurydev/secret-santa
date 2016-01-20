<?php

$app->register(new Silex\Provider\DoctrineServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array('twig.path' => __DIR__ . '/../views',));
$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app->register(new Silex\Provider\SessionServiceProvider());

$app['dao.user'] = $app->share(function ($app) {
    return new SecretSanta\DAO\UserDAO($app['db']);
});

$app['dao.instance'] = $app->share(function ($app) {
    return new SecretSanta\DAO\InstanceDAO($app['db']);
});

$app['dao.participation'] = $app->share(function ($app) {
    return new SecretSanta\DAO\ParticipationDAO($app['db']);
});

$app['dao.result'] = $app->share(function ($app) {
    return new SecretSanta\DAO\ResultDAO($app['db']);
});