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
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;

$dir = explode('web/app.php', $_SERVER['SCRIPT_NAME'])[0];


/*
 * Vous trouverez plus bas toutes les routes de l'applications (home est la page d'accueil)
 * Pour savoir comment faire une route : https://silex.symfony.com/doc/2.0/usage.html#routing
 * */
$app->match($dir, function (Request $request) use ($app) {
  return landingController($request, $app, "1");
})->bind('1_home');

$app->match($dir . "export/", function (Request $request) use ($app) {
  $result = dataExportCsv($app, '1');
  $fileName = "result-" . date("Y-m-d") . ".csv";
  $response = new Response($result);
  $disposition = $response->headers->makeDisposition(
    ResponseHeaderBag::DISPOSITION_ATTACHMENT,
    $fileName
  );
  $response->headers->set('Content-Disposition', $disposition);
  return $response;
})->bind("1_export");

$app->match($dir . "confirmation/", function () use ($app) {
  return confirmation($app, '1');
})->bind("1_confirmation");