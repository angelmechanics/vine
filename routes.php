<?php

use Symfony\Component\HttpFoundation\Response;

$account = new stdClass();
$account->name = 'angelmechanics';

// DASHBOARD ---------------------------------------------------------------------------------------

$app->get('/', function() use ($app, $account) {
	if (null === $user = $app['session']->get('user')) {
		return $app->redirect('/login');
	}

	return $app['twig']->render('index.html.twig');
});

// ACCOUNT -----------------------------------------------------------------------------------------

$app->get('/login', function() use ($app, $account) {
	$user = $app['request']->server->get('PHP_AUTH_USER', false);
	$pass = $app['request']->server->get('PHP_AUTH_PW');

	$users = $app['mongo']->vine->{$account->name.'.users'};
	$user = $users->findOne(array('name' => $user));

	if ($user) {
		$bcrypt = new \Vine\Util\Bcrypt();
		if ($bcrypt->verify($pass, $bcrypt->hash($pass))) {
			$app['session']->set('user', $user);
			return $app->redirect('/');
		}
	}

	$response = new Response();
	$response->headers->set('WWW-Authenticate', sprintf('Basic realm="%s"', 'site_login'));
	$response->setStatusCode(401, 'Please sign in.');
	return $response;
});

// @TODO Remove when creating an account and/or adding users is implemented
$app->get('/create-user/{name}', function(Silex\Application $app, $name) use ($account) {

	$bcrypt = new \Vine\Util\Bcrypt();
	$pass = 'developer';

	$mongo = $app['mongo'];
	$users = $mongo->vine->{$account->name.'.users'};

	if (!$users->findOne(array('name' => $name))) {
		$hash = $bcrypt->hash($pass);
		$user = array();
		$user['name'] = $name;
		$user['pass'] = $hash;
		$users->insert($user);
	} else {
		$hash = $bcrypt->hash($pass);
	}

	die('<p>[ '.$name.' | '.$pass.' | '.($bcrypt->verify($pass, $hash)?'true':'false')." ]</p>\n");
});