<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 02/05/17
 * Time: 14:18
 */

session_start();
/**
 * @param array $posts
 *
 * @return bool
 */
function is_BDD(array $posts){
  $form_id = 'pf_std';
  unset($_SESSION[$form_id]['message_error_bdd']);
  unset($_SESSION[$form_id]['message_error_connection']);
  $msg=[];
  foreach ($posts as $key => $v){
    if (empty($v)) {
      $msg[$key] = 'le champ ' . $key . ' est vide';
    }
  }
  if(!in_array($posts['driver'],['mysql','sqlite','pgsql'])) $msg['driver']='le champs Driver n\'est pas une valeur valide';
  if(!in_array($posts['charset'],['utf8','utf16','utf32','latin1'])) $msg['charset']='le champs Charset n\'est pas une valeur valide';
  if(!is_numeric($posts['port']) && empty($msg['port']))$msg['port']='Le champs port n\'est pas un nombre';
  foreach ($msg as $key => $message){
    $_SESSION[$form_id]['message_error_bdd'][$key]=$message;
  }
  if (empty($_SESSION[$form_id]['message_error_bdd'])){
    if($posts['driver']=='mysql'){
      $connection=mysqli_connect($posts['host'],$posts['username'],$posts['password'],'mysql',$posts['port'])
      or $_SESSION[$form_id]['message_error_connection']=mysqli_connect_error();
    }else if ($posts['driver']=='pgsql'){
      $connection_string="host=".$posts['host'].' port='.$posts['port'].' user='.$posts['username'].' password='.$posts['password'];
      $connect= pg_connect($connection_string);
      if(!$connect){
        $_SESSION[$form_id]['message_error_connection']='impossible de se connecter, veuillez vérifier les paramètres';
      }
    }
  }
  if(!empty($_SESSION[$form_id]['message_error_bdd']) || !empty($_SESSION[$form_id]['message_error_connection'])) return false;
  return true;
}


