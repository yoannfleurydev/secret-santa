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

$app->get('/user/{id}', function ($id) use ($app) {
    if (null === $app['session']->get('user')) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'danger',
                'content' => 'Vous devez être connecté pour accéder à ce profil'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    if ($app['session']->get('user')->getUserId() !== $id) {
        $app['session']->getFlashBag()->add('message', array('type' => 'danger', 'content' => 'Cette opération ne vous est pas permise'));
        return $app->redirect('/login');
    }

    $user = $app['dao.user']->find($id);

    return $app['twig']->render('user.html.twig', array('user' => $user));
})->bind('user')->assert('id', '\d+');

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

$app->get('/administration', function () use ($app) {
    if (null === $user = $app['session']->get('user')) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'danger',
                'content' => 'Vous n\'avez pas les droits d\'accès suffisant pour accéder à cette partie'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    if ($user->getUserAccess() !== 'ADMIN') {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'danger',
                'content' => 'Vous n\'avez pas les droits d\'accès suffisant pour accéder à cette partie'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    $users = $app['dao.user']->findAll();
    $instances = $app['dao.instance']->findAll();

    return $app['twig']->render(
        'administration.html.twig',
        array(
            'users' => $users,
            'instances'=> $instances
        )
    );
})->bind('administration');