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


/*
 * Vous trouverez plus bas toutes les routes de l'applications (home est la page d'accueil)
 * Pour savoir comment faire une route : https://silex.symfony.com/doc/2.0/usage.html#routing
 * */
$app->match($dir, function (Request $request) use ($app) {
  return indexController($request,$app);
})->bind('home');