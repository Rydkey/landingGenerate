<?php
use landingBundle\Entity\Landing;
use landingBundle\Form\Type\adminType;
use landingBundle\Form\Type\landingType;

/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 27/04/17
 * Time: 16:00
 *
 * @param \Symfony\Component\HttpFoundation\Request $request
 * @param \Silex\Application $app
 *
 * @return
 */

function indexController(Symfony\Component\HttpFoundation\Request $request, Silex\Application $app) {
  if (CONFIG['db_register']) {
    $em = $app['orm.em'];
    $entity = new Landing();
    $formBuilder = $app['form.factory']->createBuilder(landingType::class, $entity);
  }
  else {
    $formBuilder = $app['form.factory']->createBuilder(landingType::class, NULL);
  }
  $form = $formBuilder->getForm();
  $form->handleRequest($request);
  if ($form->isSubmitted()) {
    if ($form->isValid()) {
      if (CONFIG['mail_send']) {
        if (MAIL_BOOL['form']) {
          mail($form[NAME['mail']]->getData(), 'test', $app['twig']->render('Mail/mail.html.twig', ['items' => $form->getData()]), HEADER);
        }
        if (MAIL_BOOL['provided']) {
          foreach (MAIL_TO as $mail) {
            mail($mail, 'test', $app['twig']->render('Mail/mail.html.twig', ['items' => $form->getData()]), HEADER);
          }
        }
      }
      if (CONFIG['db_register']) {
        $em->persist($entity);
        $em->flush($entity);
      }
      $app['session']->getFlashbag()
        ->add('notice', 'Merci, un mail viens d\'ếtre envoyer');
    }else{
      $app['session']->getFlashBag()
        ->add('notice', 'Le formulaire comporte des erreurs');
    }
    }
  return $app['twig']->render('index.html.twig', ['form' => $form->createView()]);
}