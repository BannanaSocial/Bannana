<?php

	/*
	$fanpage = R::dispense('fanpage');
	$fanpage->user = $user->id;
	$fanpage->name = "Bannana Social";
	$fanpage->fbid = "392764110879635";
	$fanpage->fbtoken = "CAAKI8xapmdMBAKBqndwWBAaPYtqwlGEq4eBPP7cxNFelHOamXL8zLsZBXZCnw0lU4pHu7alMi5pB2HUJTpiL7tz3E1PhHRsp1YDxu6DgshvIFHrZCtyMybjJ2AyvrVBpThFTazWHpjxQbXXZBtOHqKjC7xIXuLo7ADdjwi9x46JtdKZBhJ5quiviPFNYiiTNQWpVo1alxZA1XTzq3MzDx64eAEGZAesZC3IZD";

	R::store($fanpage);
	*/

//GET route
$app->get('/dashboard/', $authenticate($app, 'admin'), function() use ($app){
	$user = R::load('user', $_SESSION['user']);

	$fanpages = R::find('fanpage',' user = :param ',
	           array(':param' => $user->id )
	         );

	$profilepic = "https://graph.facebook.com/".$user->fbid."/picture?access_token=".$user->fbtoken;

	$data = array('profilepic' => $profilepic,
				  'name' => $user->name,
				  'fanpages' => $fanpages);

    $app->render('dashboard.html.twig', $data);
});

//POST route

//PUT route

//DELETE route

//OPTIONS route

//PATCH route

?>