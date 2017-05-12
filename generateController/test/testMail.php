<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 04/05/17
 * Time: 16:28
 */
require_once '../../vendor/swiftmailer/swiftmailer/lib/swift_required.php';
foreach ($_POST as $key => $v){
  if (empty($v)) {
    $result = 'Un champ à été laisser vide ou est incorrect';
  }
}
if(!filter_var($_POST['username'],FILTER_VALIDATE_EMAIL))$result = "l'addresse à afficher n'est pas valide";
if(!filter_var($_POST['to'],FILTER_VALIDATE_EMAIL))$result = "l'adresse fournit pour le test n'est pas valide";
if(!isset($result)){
  mail($_POST['to'], 'test', 'ceci est un test, vous pourrez modifier l\'apparence de ce mail dans le fichier "/views/Mail/mail.html.twig"'
    ,'From: '.$_POST['username'].", \r\n"
  );
  $result = 'Un mail à été envoyé';
}
echo $result;