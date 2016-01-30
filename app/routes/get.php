<?php

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
    if (null === $user = $app['session']->get('user')) {
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
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    $participations = $app['dao.participation']->findParticipationsUserId($user->getUserId());
    $instances = $app['dao.instance']->findAll();
    $results = $app['dao.result']->findAll();
    $users = $app['dao.user']->findAll();

    $drawIsDone = array();
    foreach($instances as $instance) {
        $drawIsDone[$instance->getInstanceId()] = $app['dao.result']->resultInstanceIdExist($instance->getInstanceId());
    }

    return $app['twig']->render(
        'user.html.twig',
        array(
            'user' => $user,
            'participations' => $participations,
            'instances'=> $instances,
            'drawIsDone' => $drawIsDone,
            'users' => $users,
            'results' => $results
        )
    );
})->bind('user')->assert('id', '\d+');

$app->get('/modify/user/{id}', function() use ($app) {
    if (null === $user = $app['session']->get('user')) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'warning',
                'content' => 'Vous n\'avez pas les droits d\'accès suffisant pour accéder à cette partie'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    if ($user->getUserId() !== $app['session']->get('user')->getUserId()) {
        $app['session']->getFlashBag()->add(
            'message',
            array(
                'type' => 'warning',
                'content' => 'Vous n\'avez pas les droits d\'accès suffisant pour accéder à cette partie'
            )
        );
        return $app->redirect($app['url_generator']->generate('login_get'));
    }

    return $app['twig']->render('modify_user.html.twig', array('user' => $user));
})->bind('modify_user_id')->assert('id', '\d+');

$app->get('/logout', function () use ($app) {
    $app['session']->clear();

    $app['session']->getFlashBag()->add('message',
        array(
            'type' => 'success',
            'content' => 'Vous êtes maintenant déconnecté'
        )
    );

    return $app->redirect($app['url_generator']->generate('index'));
})->bind('logout');

$app->get('/instance/join/{user_id}/{participation_id}/{participation_result}',
    function($user_id, $participation_id, $participation_result) use ($app) {
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

        if ($app['session']->get('user')->getUserId() !== $user_id) {
            $app['session']->getFlashBag()->add('message', array('type' => 'warning', 'content' => 'Cette opération ne
            vous est pas permise'));
            return $app->redirect($app['url_generator']->generate('login_get'));
        }

        $app['dao.participation']->updateParticipationResult($participation_id, $participation_result);

        return $app->redirect($app['url_generator']->generate('user', array('id' => $user_id)));
})->bind('instance_join_userId_participationId_participationResult')
    ->assert('user_id', '\d+')
    ->assert('participation_id', '\d+')
    ->assert('participation_result','[0|1]');

$app->get('/administration/delete/user/{id}', function($id) use ($app) {
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

    if ($app['session']->get('user')->getUserId() === $id) { $app['session']->clear(); }
    $app['dao.user']->deleteUser($id);

    $app['session']->getFlashBag()->add(
        'message',
        array(
            'type' => 'success',
            'content' => 'L\'utilisateur ' . $id . ' a bien été supprimé'
        )
    );

    return $app->redirect($app['url_generator']->generate('index'));
})->bind('delete_user')->assert('id', '\d+');

$app->get('/instance/run/{instance_id}', function ($instance_id) use ($app) {
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

    // TODO test si le tirage a déjà était fait, en gros si l'identifiant de l'instance existe

    $participations = $app['dao.participation']->findAll();

    $participationsInstance = array();
    foreach($participations as $participation) {
        if ($participation->getParticipationInstanceId() == $instance_id) {
            $participationsInstance[] = $participation;
        }
    }

    $participationsInstanceCopy = array();
    foreach($participationsInstance as $participation) {
        $participationsInstanceCopy[] = $participation;
    }

    // Loop to shuffle the second array.
    do {
        $arraysAreDifferent = true;
        shuffle($participationsInstanceCopy);
        for ($i = 0; $i < count($participationsInstance); $i++) {
            if ($participationsInstance[$i]->getParticipationUserId() ===
                $participationsInstanceCopy[$i]->getParticipationUserId()) {
                $arraysAreDifferent = false;
            }
        }
    } while(!$arraysAreDifferent);

    for ($i = 0; $i < count($participationsInstance); $i++) {
        $app['dao.result']->setResult(
            $instance_id,
            $participationsInstance[$i]->getParticipationUserId(),
            $participationsInstanceCopy[$i]->getParticipationUserId()
        );
    }

    return $app->redirect($app['url_generator']->generate('administration'));
})->bind('instance_run')->assert('instance_id', '\d+');

$app->get('/administration', function () use ($app) {
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

    $users = $app['dao.user']->findAll();
    $instances = $app['dao.instance']->findAll();
    $drawIsDone = array();

    foreach($instances as $instance) {
        $drawIsDone[] = $app['dao.result']->resultInstanceIdExist($instance->getInstanceId());
    }

    return $app['twig']->render(
        'administration.html.twig',
        array(
            'users' => $users,
            'instances'=> $instances,
            'drawIsDone' => $drawIsDone
        )
    );
})->bind('administration');

/**
 * Function tu shuffle an array with the key => value
 * @param $array
 * @return bool
 */
function shuffle_assoc(&$array) {
    $keys = array_keys($array);
    shuffle($keys);
    foreach($keys as $key) {
        $new[$key] = $array[$key];
    }
    $array = $new;

    return true;
}