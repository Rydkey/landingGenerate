<?php

use landingBundle\Entity\Landing;
use landingBundle\Entity\LandingVotreGuide;
use landingBundle\Form\Type\adminType;
use landingBundle\Form\Type\landingType;
use landingBundle\Form\Type\landingVotreGuideType;
use Symfony\Component\Serializer\Encoder\CsvEncoder;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 27/04/17
 * Time: 16:00
 */


function homeController($app)
{
  return $app['twig']->render('home.html.twig');
}

/**
 * fonction principale de l'application, elle permet de créer le formulaire et d'y rattacher la
 * bonne table.
 *
 * @param \Symfony\Component\HttpFoundation\Request $request
 * @param \Silex\Application                        $app
 *
 * @return
 */

function landingController(Symfony\Component\HttpFoundation\Request $request, Silex\Application $app, $landing)
{
  $base_url = _getBaseUrl($landing);
  // paramêtres POST de la fonction, il n'y en a pas de base.
  $parameters = $request->query->all();
  //si enregistrement en base
  if (CONFIG['db_register']) {
    $em = $app['orm.em'];
    $entity = _getEntity($landing);
    $formBuilder = _getFormBuilder($app, $landing, $entity);
  } else {
    $formBuilder = _getFormBuilder($app, $landing, NULL);
  }
  $form = $formBuilder->getForm();
  $form->handleRequest($request);
  //variable correspondant à la page de retour (pour l'instant la page d'atterissage).
  $retour = $app['twig']->render($base_url . '/landing.html.twig', ['form'       => $form->createView(),
                                                                    'parameters' => $parameters]);
  if ($form->isSubmitted()) {
    if ($form->isValid() && time()) {
      //Si il y à des envoies de mail
      if (CONFIG['mail_send']) {
        //Mail fournit dans le form
        if (MAIL_BOOL['form']) {
          mail($form[NAME['mail']]->getData(), 'test', $app['twig']
            ->render($base_url . '/Mail/mail.html.twig', ['items' => $form->getData()]), HEADER);
        }
        //mail fournit par le developpeur (voir MAIL_TO dans app/config/prod.php)
        if (MAIL_BOOL['provided']) {
          foreach (MAIL_TO as $mail) {
            mail($mail, 'test', $app['twig']->render($base_url . 'Mail/mail.html.twig',
              ['items' => $form->getData()]), HEADER);
          }
        }
      }
      // enregistrement en base, c'est ici qu'il faut agir si vous souhaitez modifier
      // vos données avant enregistrement.
      if (CONFIG['db_register']) {
        $em->persist($entity);
        $em->flush($entity);
      }
      //ne pas modifier si vous souhaitez ajouter un tracker.
      $app['session']->set("datas", [
        "mail" => $form[NAME['mail']]->getData(),
        "nom"  => $form[NAME['nom']]->getData(),
      ]);
      $retour = $app->redirect($app["url_generator"]->generate($base_url . "_confirmation"));
    } else {
      // Message si erreur
      $app['session']->getFlashBag()
        ->add('notice', 'Le formulaire comporte des erreurs');
    }
  }
  return $retour;
}


/**
 * Permet d'exporter au format csv les données de la base.
 *
 * @param \Silex\Application $app
 * @param                    $landing
 *
 * @return bool|float|int|string
 */
function dataExportCsv(Silex\Application $app, $landing)
{
  $arr = [];
  $datas = _getAllDatas($app, $landing);
  $object = _getEntity($landing);
  $functions = preg_grep('/^get/', get_class_methods($object));
  $encoders = [new XmlEncoder(), new JsonEncoder(), new CsvEncoder()];
  $normalizers = [new ObjectNormalizer()];
  $serializer = new Serializer($normalizers, $encoders);
  foreach ($datas as $key => $data) {
    $temp = [];
    foreach ($functions as $function) {
      $temp[explode("get", $function)[1]] = $data->$function();
    }
    $arr[] = $temp;
  }
  $csv = $serializer->serialize($arr, "csv");
  return $csv;
}

/**
 * fonction redirigeant vers la page de confirmation.
 *
 * @param $app
 * @param $landing
 *
 * @return mixed
 */
function confirmation($app, $landing)
{
  $url_base = _getBaseUrl($landing);
  return $app['twig']->render($url_base . '/confirmation.html.twig');
}


/**
 * Permet de changer le dossier views.
 *
 * @param $landing
 *
 * @return string
 */
function _getBaseUrl($landing)
{
  switch ($landing) {
    case  "1":
      $url_base = '1';
      break;
  }
  return $url_base;
}

/**
 * pemet de récupérer l'objet "ORM" définit dans src/landingBundel/Entity
 *
 * @param $landing
 *
 * @return Landing
 */
function _getEntity($landing)
{
  switch ($landing) {
    case  "1":
      $entity = new Landing();
      break;
    case "votre_guide":
      $entity = new LandingVotreGuide();
      break;
  }
  return $entity;
}


/**
 * permet de récuperer l'objet définissant le formulaire définit dans src/landingBundel/Form/Type
 *
 * @param      $app
 * @param      $landing
 * @param null $entity
 *
 * @return mixed
 */
function _getFormBuilder($app, $landing, $entity = NULL)
{
  switch ($landing) {
    case "1":
      $result = $app['form.factory']->createBuilder(landingType::class, $entity);
      break;
    default:
      $result = $app['form.factory']->createBuilder(landingType::class, NULL);
      break;
  }
  return $result;
}

/**
 * Permet de récuperer toutes les entrées en base.
 *
 * @param $app
 * @param $landing
 *
 * @return mixed
 */
function _getAllDatas($app, $landing)
{
  $em = $app['orm.em'];
  switch ($landing) {
    case  "landing_1":
      $repository = $em->getRepository(Landing::class);
      break;
  }
  $result = $repository->findBy([], ["id" => "ASC"]);
  return $result;
}