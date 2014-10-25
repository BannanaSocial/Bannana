<?php

//GET route
$app->get('/dashboard/', $authenticate($app, 'admin'), function() use ($app){
	$user = R::load('user', $_SESSION['user']);

	$title = "Dashboard";

	$fanpages = R::find('fanpage',' user = :param ',
	           array(':param' => $user->id )
	         );

	$profilepic = "https://graph.facebook.com/".$user->fbid."/picture?access_token=".$user->fbtoken;

	$allpages = json_decode(file_get_contents("https://graph.facebook.com/".$user->fbid."/accounts?access_token=".$user->fbtoken));

	$data = array('title' => $title,
				  'profilepic' => $profilepic,
				  'name' => $user->name,
				  'fanpages' => $fanpages,
				  'allpages' => $allpages->data);

    $app->render('dashboard.html.twig', $data);
});

//POST route

$app->post('/dashboard/fanpage/add/', $authenticate($app, 'admin'), function() use ($app){
	$post = (object)$app->request()->post();

	$user = R::load('user', $_SESSION['user']);

	$fanpage = R::dispense('fanpage');
	$fanpage->user = $user->id;
	$fanpage->name = $post->name;
	$fanpage->fbid = $post->fbid;
	$fanpage->fbtoken = $post->fbtoken;

	R::store($fanpage);

    echo $fanpage->name;
});

//PUT route

//DELETE route

//OPTIONS route

//PATCH route

?>