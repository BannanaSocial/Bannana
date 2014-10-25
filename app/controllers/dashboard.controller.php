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

	foreach ($allpages->data as $page) {
		$fanpage = R::findOne('fanpage',' user = :param && fbid = :fbid ',
		           array(':param' => $user->id, ':fbid' => $page->id )
		         );

		if($fanpage){
			$fanpage->fbtoken = $page->access_token;

			R::store($fanpage);
		}
	}

	$fanpage = R::findOne('fanpage',' user = :param ',
	           array(':param' => $user->id )
	         );
	if($fanpage){
		$inbox = json_decode(file_get_contents("https://graph.facebook.com/".$fanpage->fbid."/conversations?access_token=".$fanpage->fbtoken));

		foreach ($inbox->data[0]->messages->data as $message) {

			$fbmessage = R::findOne('fbmessage',' fbid = :param ',
			           array(':param' => $message->id )
			         );

			if($fbmessage){

			}else{

				$fbmessage = R::dispense('fbmessage');

				$fbmessage->fbid = $message->id;
				$fbmessage->from = $message->from->name;
				$fbmessage->fromid = $message->from->id;
				$fbmessage->to = $message->to->data[0]->name;
				$fbmessage->toid = $message->to->data[0]->id;
				$fbmessage->message = $message->message;
				$fbmessage->status = "unread";

				if($fbmessage->fromid == $fanpage->fbid){
					$ticket = R::findOne('ticket',' userfbid = :param ',
				           array(':param' => $fbmessage->toid )
				         );
					$userfbid = $fbmessage->toid;
				}else{
					$ticket = R::findOne('ticket',' userfbid = :param ',
				           array(':param' => $fbmessage->fromid )
				         );
					$userfbid = $fbmessage->fromid;
				}

				if($ticket){
					$ticket->status = "pending";
				}else{
					$ticket = R::dispense('ticket');
					$ticket->type = "fbmessage";
					$ticket->status = "pending";
					$ticket->department = 1;
					$ticket->userfbid = $userfbid;
					$ticket->fanpage = $fanpage->id;
				}

				R::store($ticket);

				$fbmessage->ticket = $ticket->id;
				R::store($fbmessage);
				
				setFirebaseValue('/ticket/fbmessage/'.$fbmessage->id.'/to', $fbmessage->to);
				setFirebaseValue('/ticket/fbmessage/'.$fbmessage->id.'/from', $fbmessage->from);
				setFirebaseValue('/ticket/fbmessage/'.$fbmessage->id.'/message', $fbmessage->message);
			}
		}

		$inbox = array();

		$tickets = R::find('ticket',' fanpage = :param ',
		           array(':param' => $fanpage->id )
		         );

		foreach ($tickets as $ticket) {
			if($ticket->type == "fbmessage"){
				$fbmessages = R::find('fbmessage',' ticket = :param ',
			           array(':param' => $ticket->id )
			         );

				foreach ($fbmessages as $message) {
					$inbox[] = $message;
				}
			}
		}
	}

	$data = array('title' => $title,
				  'profilepic' => $profilepic,
				  'name' => $user->name,
				  'fanpages' => $fanpages,
				  'allpages' => $allpages->data,
				  'messages' => $inbox);

    $app->render('dashboard.html.twig', $data);
});

$app->get('/dashboard/department/', $authenticate($app, 'admin'), function() use ($app){

	$data = array('title' => "Department");

    $app->render('department.html.twig', $data);
});

$app->get('/dashboard/staff/', $authenticate($app, 'admin'), function() use ($app){

	$data = array('title' => "Staff");

    $app->render('staff.html.twig', $data);
});

$app->get('/dashboard/insights/', $authenticate($app, 'admin'), function() use ($app){

	$data = array('title' => "Insights");

    $app->render('insights.html.twig', $data);
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