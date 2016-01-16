<?php

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;

$app->post('/signup', function (Request $request) use ($app) {
    if (strlen($request->request->get('user_login')) < 3) {
        $app['session']->getFlashBag()->add('message', array('type' => 'danger', 'content' => 'Votre identifiant de connexion doit avoir une longueur minimale de 3 caractères.'));
        return $app->redirect($app['url_generator']->generate('signup_get'));
    }

    if (strlen($request->request->get('user_password')) < 4) {
        $app['session']->getFlashBag()->add('message', array('type' => 'danger', 'content' => 'Votre mot de passe doit avoir une longueur minimale de 5 caractères. Nous vous
                conseillons l\'utilisation d\'un mot de passe composé de lettres, majuscules et miniscules ainsi que
                de caractères numériques et de symboles.'));
        return $app->redirect($app['url_generator']->generate('signup_get'));
    }

    if ($request->request->get('user_password') !== $request->request->get('user_password_verification')) {
        $app['session']->getFlashBag()->add('message', array('type' => 'danger', 'content' => 'Les deux mots de passe ne correspondent pas.'));
        return $app->redirect($app['url_generator']->generate('signup_get'));
    }

    if ($app['dao.user']->userLoginExist($request->request->get('user_login'))) {
        $app['session']->getFlashBag()->add('message', array('type' => 'danger', 'content' => "Le pseudo " . $request->request->get('username') . " déjà utilisé"));
        return $app->redirect($app['url_generator']->generate('signup_get'));
    }

    $app['dao.user']->setUser(
        $request->request->get('user_login'),
        $request->request->get('user_password'),
        $request->request->get('user_firstname'),
        $request->request->get('user_lastname'),
        $request->request->get('user_email')
    );

    $user = $app['dao.user']->findByUserLogin($request->request->get('user_login'));

    $app['session']->set('user', $user);
    $app['session']->set('connected', array('connected' => true));
    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => "Votre compte a été créé, vous êtes maintenant connecté à l'application."
        )
    );

    return $app->redirect($app['url_generator']->generate('index'));
})->bind('signup_post');

$app->post('/login', function (Request $request) use ($app) {


    if ($app['dao.user']->verifyLogin($request->request->get('user_login'), $request->request->get('user_password'))) {
        $user = $app['dao.user']->findByUserLogin($request->request->get('user_login'));
        $app['session']->set('user', $user);
        $app['session']->set('connected', array('connected' => true));
        $app['session']->getFlashBag()->add('message',
            array(
                'type' => 'success',
                'content' => 'Connexion réussie'
            )
        );
        return $app->redirect($app['url_generator']->generate('index'));
    } else {
        $app['session']->getFlashBag()->add('message',
            array(
                'type' => 'danger',
                'content' => 'Mauvaise combinaison d\'identifiants.'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }
})->bind('login_post');

$app->post('/administration/new/instance', function(Request $request) use ($app) {
    $instance_name = $request->request->get('instance_name');

    $instance_hash = hash('md5', $instance_name);

    // TODO enregistrer dans la base de données

    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => 'Votre instance ' . $instance_name . ' ' . $instance_hash . ' a bien été créée'
        )
    );
    return $app->redirect($app['url_generator']->generate('index'));
})->bind('administration_new_instance_post');