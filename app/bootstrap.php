<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 06/04/17
 * Time: 14:37
 */

//ATTENTION ! N'a rien à voir avec le Framework, ici seront géré toutes les dépendances de la landing

use Symfony\Component\Debug\ErrorHandler;
use Symfony\Component\Debug\ExceptionHandler;
use Dflydev\Provider\DoctrineOrm\DoctrineOrmServiceProvider;
use Silex\Provider\FormServiceProvider;

// Register global error and exception handlers
ErrorHandler::register();
ExceptionHandler::register();

// Register service providers.
$app->register(new FormServiceProvider());
$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));
$app->register(new Silex\Provider\DoctrineServiceProvider());
$app['db.options'] = array(
  'driver'   => BDD['driver'], //définit le driver pour la BDD
  'charset'  => BDD['charset'], //définit le charset pour la BDD
  'host'     => BDD['host'],  //serveur pour la BDD
  'port'     => BDD['port'],  //port du serveur
  'dbname'   => BDD['dbname'],  //nom de la BDD
  'user'     => BDD['user'], //Utilisateur
  'password' => BDD['password'], //mot de passe
);
$app->register(new DoctrineOrmServiceProvider, array(
    'orm.em.options' => array(
        'mappings' => array(
            // Using actual filesystem paths
            array(
                'type' => 'annotation',
                'namespace' => 'landingBundle\Entity',
                'path' => __DIR__ . '/../src/Entity',
            )

        ),
    ),
));

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\SessionServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'translator.domains' => array(),
));
$app->register(new Silex\Provider\LocaleServiceProvider());
$app->register(new Silex\Provider\TranslationServiceProvider());
$app->register(new Silex\Provider\ValidatorServiceProvider());
// rajoute la méthode asset dans twig
$app->register(new Silex\Provider\AssetServiceProvider(), array(
    'assets.named_packages' => array(
        'css' => array('base_path' => BASE_URL.'/'),
        'img' => array('base_path' => BASE_URL.'/'),
    )));
