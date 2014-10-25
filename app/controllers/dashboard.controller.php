<?php

//GET route
$app->get('/dashboard/', $authenticate($app, 'admin'), function() use ($app){
	$user = R::load('user', $_SESSION['user']);

	$profilepic = "https://graph.facebook.com/".$user->fbid."/picture?access_token=".$user->fbtoken;

	$data = array('profilepic' => $profilepic,
				  'name' => $user->name);

    $app->render('dashboard.html.twig', $data);
});

//POST route

//PUT route

//DELETE route

//OPTIONS route

//PATCH route

?>