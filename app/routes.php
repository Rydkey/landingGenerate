<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 06/04/17
 * Time: 14:44
 */
// Ce fichier permet d'indiquer les routes de l'application.

include('Controller.php');
use Symfony\Component\HttpFoundation\Request;

$dir=explode('web/app.php', $_SERVER['SCRIPT_NAME'])[0];

// Home page
$app->match($dir, function (Request $request) use ($app) {
  return indexController($request,$app);
})->bind('home');