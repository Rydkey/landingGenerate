<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 02/05/17
 * Time: 11:49
 */

session_start();

include('generateController/format/trim_value.php');

include('generateController/verification/bddVerif.php');
include('generateController/verification/fieldVerif.php');
include('generateController/verification/mailVerif.php');


include('generateController/generate/generate_prod.php');
include('generateController/generate/generate_landing_entity.php');
include('generateController/generate/generate_database.php');

//affichage des erreurs
//ini_set('display_startup_errors', 1);
//ini_set('display_errors', 1);
//error_reporting(-1);

//définition de l'url du formulaire.
$dir=explode('generateController.php', $_SERVER['SCRIPT_NAME'])[0];

//variable booléenne pour vérifier la validité des test.
$valid=true;
$form_id = 'pf_std';

//unset des champs précédents.
unset($_SESSION[$form_id]);

//vérification du champs BDD si il est activer.
$data['bdd']=[
  'register'=>boolval(htmlentities($_POST['register_bdd'])),
  'driver'=>htmlentities($_POST['driver_bdd']),
  'charset'=>htmlentities($_POST['charset_bdd']),
  'username'=>htmlentities($_POST['username_bdd']),
  'password'=>htmlentities($_POST['password_bdd']),
  'host'=>htmlentities($_POST['host_bdd']),
  'port'=>htmlentities($_POST['port_bdd']),
  'name'=>htmlentities($_POST['name_bdd']),
];
if (isset($_POST['register_bdd'])){
  array_walk($data['bdd'], 'trim_value');
  $valid=is_BDD($data['bdd']);
}

$data['mail']=[
  'register'=>boolval($_POST['register_mail']),
  'username'=>htmlentities($_POST['username_mail']),
];
if(isset($_POST['to_form_mail']))$data['mail']['to_form']=boolval(htmlentities($_POST['to_form_mail']));
if(isset($_POST['to_provided_mail'])){
  $data['mail']['to_provided_mail']=boolval(htmlentities($_POST['to_provided_mail']));
  if (!empty($_POST['to_mail'])){
    $data['mail']['mail_provided']=htmlentities($_POST['to_mail']);
  }else{
    $_SESSION[$form_id]['message_error_mail']['to_mail']=
      'Si vous souhaitez envoyer le mail à une adresse définit, entrez une adresse valide';
  }
}
if (isset($_POST['register_mail'])){
  array_walk($data['mail'], 'trim_value');
  $valid=is_Mail($data['mail']);
}

$data['field']=[
  'mail'=>$_POST['field_mail'],
  'nom'=>$_POST['field_nom'],
  'prenom'=>$_POST['field_prenom'],
  'numeroAdresse'=>$_POST['field_numeroAdresse'],
  'voieAdresse'=>$_POST['field_voieAdresse'],
  'codePostal'=>$_POST['field_codePostal'],
  'ville'=>$_POST['field_ville'],
  'telephone'=>$_POST['field_telephone'],
  'entreprise'=>$_POST['field_entreprise'],
  'message'=>$_POST['field_message'],
  'opt_in'=>$_POST['field_optionnel'],
];
array_walk($data['field'], 'trim_value');
$valid=is_Field($data['field'],$data['mail']['to_form']);

if (!$valid || isset($_SESSION[$form_id]['message_error_bdd'])
  || isset($_SESSION[$form_id]['message_error_mail'])
  || isset($_SESSION[$form_id]['message_error_field'])
  || isset($_SESSION[$form_id]['message_error_connection'])
  ){
  if(isset($_POST['register_bdd'])){
    if(isset($data['bdd']))$_SESSION[$form_id]['field_bdd']=$data['bdd'];
  }
  if(isset($_POST['register_mail'])){
    if(isset($data['mail']))$_SESSION[$form_id]['field_mail']=$data['mail'];
  }
  if(isset($data['field']))$_SESSION[$form_id]['field_field']=$data['field'];
  $_SESSION[$form_id]['field_bdd']['register_bdd']=htmlentities($_POST['register_bdd']);
  $_SESSION[$form_id]['field_mail']['register_mail']=htmlentities($_POST['register_mail']);
  header('Location:' . $dir.'generate.php');
}else{
  generate_prod($data);
  if(($data['bdd']['register'])) {
    $test=generate_landing_entity($data['field'], $data['bdd']['name']);
    $test=  generate_database($data['bdd']);
    if ($test){
      exec('php vendor/bin/doctrine orm:schema-tool:create');
      shell_exec('php vendor/bin/doctrine orm:generate-entities --filter=Entity src/ 2>&1');
    }
  }
  header('Location:'.$dir);
}