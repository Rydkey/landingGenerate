<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 05/05/17
 * Time: 09:15
 */


foreach ($_POST as $key => $v){
  if (empty($v)) {
    $result='un champ à été laisser vide ou est incorrect';
  }
}
if(!in_array($_POST['driver'],['mysql','sqlite','pgsql'])) $result='un champ à été laisser vide ou est incorrect';;
if(!in_array($_POST['charset'],['utf8','utf16','utf32','latin1'])) $result='un champ à été laisser vide ou est incorrect';;
if(!is_numeric($_POST['port']) && empty($msg['port']))$msg['port']=$result='un champ à été laisser vide ou est incorrect';;
if (!isset($result)) {
  $result='La connection à été établit.';
  if ($_POST['driver'] == 'mysql') {
    $connection = mysqli_connect($_POST['host'], $_POST['username'], $_POST['password'], $_POST['database'], $_POST['port'])
    or $result = mysqli_connect_error();
  }
  else if ($_POST['driver'] == 'pgsql') {
    $connection_string = "host=" . $_POST['host'] . ' port=' . $_POST['port'] . ' user=' . $_POST['username'] . ' password=' . $_POST['password'];
    $connect = pg_connect($connection_string);
    if (!$connect) {
      $result = 'impossible de se connecter, veuillez vérifier les paramètres';
    }
  }else{
    $result='les bases de données de type SQLite ne sont pas encore prises en compte.';
  }
}

echo $result;