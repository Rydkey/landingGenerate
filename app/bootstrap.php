<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 06/04/17
 * Time: 14:37
 */


use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\FormServiceProvider;


/*
 * Script de paramêtres de l'appliquation,
 * Dans l'idéal, ne pas troucher.
 * Pour plus d'informations :  https://silex.symfony.com/doc/2.0/usage.html#bootstrap
 * */

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), [
  'twig.path' => __DIR__ . '/../views',
]);
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = [
  'driver'   => BDD['driver'], //définit le driver pour la BDD
  'charset'  => BDD['charset'], //définit le charset pour la BDD
  'host'     => BDD['host'],  //serveur pour la BDD
  'port'     => BDD['port'],  //port du serveur
  'dbname'   => BDD['dbname'],  //nom de la BDD
  'user'     => BDD['user'], //Utilisateur
  'password' => BDD['password'], //mot de passe
];
$app->register(new DoctrineOrmServiceProvider, [
  'orm.em.options' => [
    'mappings' => [
      // Using actual filesystem paths
      [
        'type'      => 'annotation',
        'namespace' => 'landingBundle\Entity',
        'path'      => __DIR__ . '/../src/Entity',
      ],
    
    ],
  ],
]);

$app->register(new Silex\Provider\TranslationServiceProvider(), [
  'translator.domains' => [],
]);
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), [
  'translator.domains' => [],
]);
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
// rajoute la méthode asset dans twig
$app->register(new Silex\Provider\AssetServiceProvider(), [
  'assets.named_packages' => [
    'css' => ['base_path' => BASE_URL . 'assets/css'],
    'img' => ['base_path' => BASE_URL . 'assets/img'],
    'lib' => ['base_path' => BASE_URL . 'assets/lib'],
    'js'  => ['base_path' => BASE_URL . 'assets/js'],
  ]]);
