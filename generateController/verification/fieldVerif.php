<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 02/05/17
 * Time: 14:18
 */

session_start();

function is_Field(array $posts,$mail){
  $form_id = 'pf_std';
  unset($_SESSION[$form_id]['message_error_field']);
  $msg=[];
  $vide=true;
  foreach ($posts as $key => $v){
    if (!empty($v)){
      $vide=false;
      break;
    }
  }
  if(empty($posts['mail'])&& $mail)$msg['mail']='Pour un envoie au mail renseigné dans le formulaire, il faut un champ mail.';
  if($vide)$msg['empty']='Il faut au minimum un champs de renseigné';
  foreach ($posts as $key => $v) {
    if (strcspn($v, '0123456789@~&^()[]{}`\\=+-*/°|\'"<>.') != strlen($v)) {
      $msg[$key] = 'le champ ' . $key . ' comporte des nombres ou des caractères spéciaux.';
    }
  }
  foreach ($msg as $key => $message){
    $_SESSION[$form_id]['message_error_field'][$key]=$message;
  }
  if(!empty($_SESSION[$form_id]['message_error_field'])) return false;
  return true;

}