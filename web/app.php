<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 06/04/17
 * Time: 14:31
 */
require_once __DIR__.'/../vendor/autoload.php';

$app = new Silex\Application();


//Si le site est host derrière un proxy, décommenter la fonction suivante en remplaçant $ip par l'ip du proxy

//use Symfony\Component\HttpFoundation\Request;
//Request::setTrustedProxies(array($ip));



// ... definitions

require __DIR__.'/../app/config/dev.php';
require __DIR__.'/../app/bootstrap.php';
require __DIR__.'/../app/routes.php';

$app->run();