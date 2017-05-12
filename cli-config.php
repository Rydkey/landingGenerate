
<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 07/04/17
 * Time: 11:51
 */
// http://docs.doctrine-project.org/en/latest/reference/configuration.html
use Doctrine\ORM\Configuration;

require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/app/config/dev.php';
$newDefaultAnnotationDrivers = array(
  __DIR__ . "/src/landingBundle/Entity",
);
$config = new Configuration();
$config->setMetadataCacheImpl(new \Doctrine\Common\Cache\ArrayCache());
$driverImpl = $config->newDefaultAnnotationDriver($newDefaultAnnotationDrivers);
$config->setMetadataDriverImpl($driverImpl);
$config->setProxyDir($app['orm.proxies_dir']);
$config->setProxyNamespace('Proxies');
$em = \Doctrine\ORM\EntityManager::create($db_config, $config);
$helpers = new Symfony\Component\Console\Helper\HelperSet(array(
  'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($em->getConnection()),
  'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($em),
));