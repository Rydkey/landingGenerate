<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 02/05/17
 * Time: 14:18
 */

session_start();

function is_Mail(array $posts){
  $form_id = 'pf_std';
  unset($_SESSION[$form_id]['message_error_mail']);
  $msg=[];
//  idem que pour is_BDD()
  foreach ($posts as $key => $v){
    if (empty($v)) {
      $msg[$key] = 'le champ ' . $key . ' est vide';
    }
  }
//  on vérifie ici que tout les mails founi sont valides
  if(!filter_var($posts['username'],FILTER_VALIDATE_EMAIL) 
    &&empty($msg['username']))$msg['username']='Le champs username n\'est pas une adresse mail valide';
  if(isset($posts['mail_provided']) && !filter_var($posts['mail_provided'],FILTER_VALIDATE_EMAIL))
    $msg['to_mail']='Le champs destinataire n\'est pas une adresse mail valide';
  
  foreach ($msg as $key => $message){
    $_SESSION[$form_id]['message_error_mail'][$key]=$message;
  }
  if(!empty($_SESSION[$form_id]['message_error_mail'])) return false;
  return true;
}