<?php

//GET route
$app->get('/dashboard/', $authenticate($app, 'admin'), function() use ($app){
   $app->render('dashboard.html.twig');
});

//POST route

//PUT route

//DELETE route

//OPTIONS route

//PATCH route

?>