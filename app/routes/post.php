<?php

use Symfony\Component\HttpFoundation\Request;

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
        htmlspecialchars($request->request->get('user_login')),
        $request->request->get('user_password'),
        htmlspecialchars($request->request->get('user_firstname')),
        htmlspecialchars($request->request->get('user_lastname')),
        htmlspecialchars($request->request->get('user_email'))
    );

    $user = $app['dao.user']->findByUserLogin($request->request->get('user_login'));

    $app['session']->clear();

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
    $app['session']->clear();
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
    if (!$app['function.connectedUserIsAdmin']) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'warning',
                'content' => 'Vous n\'avez pas les droits d\'accès suffisant pour accéder à cette partie'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    $instance_year = date('Y');

    if (strlen($request->request->get('instance_name')) <= 3) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'warning',
                'content' => 'Le nom de l\'instance doit faire plus de 3 caractères' 
            )
        );
        return $app->redirect($app['url_generator']->generate('administration'));
    } else {
        $instance_name = htmlspecialchars($request->request->get('instance_name'));
    }

    if ($app['dao.instance']->instanceNameExist($instance_name)) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'danger',
                'content' => 'Une instance de ce nom existe déjà'
            )
        );
        return $app->redirect($app['url_generator']->generate('administration'));
    }

    $instance_hash = hash('md5', $instance_year . htmlspecialchars($instance_name));
    $instance_author = $app['session']->get('user')->getUserId();

    $app['dao.instance']->setInstance($instance_year, $instance_name, $instance_hash, $instance_author);
    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => 'Votre instance ' . $instance_name . ' ' . $instance_hash . ' a bien été créée'
        )
    );
    return $app->redirect($app['url_generator']->generate('index'));
})->bind('administration_new_instance_post');

$app->post('/instance/join', function(Request $request) use ($app) {
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

    $instance_hash = $request->get('instance_hash');

    try {
        $instance = $app['dao.instance']->findInstanceHash($instance_hash);
    } catch (Exception $e) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'danger',
                'content' => 'Cette instance n\'existe pas.'
            )
        );
        return $app->redirect($app['url_generator']->generate('index'));
    }

    $instance_id = $instance->getInstanceId();
    $user_id = $user->getUserId();

    if ($app['dao.participation']->participationExist($instance_id, $user_id)) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'warning',
                'content' => 'Vous avez déjà rejoins cette instance.'
            )
        );
        return $app->redirect($app['url_generator']->generate('index'));
    }

    $app['dao.participation']->setParticipation($instance_id, $user_id);

    $app['session']->getFlashBag()->add(
        'message',
        array(
            'type' => 'success',
            'content' => 'Vous avez bien rejoins l\'instance ' . $instance->getInstanceName()
        )
    );
    return $app->redirect($app['url_generator']->generate('index'));
})->bind('instance_join');

$app->post('/modify/user', function(Request $request) use ($app) {
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

    if (strlen($request->request->get('user_password')) != 0 && strlen($request->request->get
        ('user_password_verification')) != 0) {
        if (strlen($request->request->get('user_password')) < 4) {
            $app['session']->getFlashBag()->add('message',
                array(
                    'type' => 'danger',
                    'content' => 'Votre mot de passe doit avoir une longueur minimale de 5 caractères. Nous vous
                conseillons l\'utilisation d\'un mot de passe composé de lettres, majuscules et miniscules ainsi que
                de caractères numériques et de symboles.'
                )
            );
            return $app->redirect($app['url_generator']->generate('modify_user_id', array('id' => $user->getUserId())));
        }

        if ($request->request->get('user_password') !== $request->request->get('user_password_verification')) {
            $app['session']->getFlashBag()->add('message',
                array(
                    'type' => 'danger',
                    'content' => 'Les deux mots de passe ne correspondent pas.'
                )
            );
            return $app->redirect($app['url_generator']->generate('modify_user_id', array('id' => $user->getUserId())));
        }

        $app['dao.user']->updatePassword($request->request->get('user_password'), $user->getUserId());
    }

    $app['dao.user']->updateUser(
        $user->getUserId(),
        htmlspecialchars($request->request->get('user_firstname')),
        htmlspecialchars($request->request->get('user_lastname')),
        htmlspecialchars($request->request->get('user_email')),
        $user->getUserAccess()
    );

    $user = $app['dao.user']->findByUserLogin($app['session']->get('user')->getUserLogin());

    $app['session']->clear();


    $app['session']->set('user', $user);
    $app['session']->set('connected', array('connected' => true));
    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => "Vos paramètres ont bien été sauvegardés"
        )
    );

    return $app->redirect($app['url_generator']->generate('index'));
})->bind('modify_user_post');