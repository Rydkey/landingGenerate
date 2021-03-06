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
$dir = explode('generateController.php', $_SERVER['SCRIPT_NAME'])[0];

//variable booléenne pour vérifier la validité des test.
$valid = true;
$form_id = 'pf_std';

//unset des champs précédents.
unset($_SESSION[$form_id]);

//vérification du champs BDD si il est activer.

/**
 *  On attribut les champs dans un tableau
 **/
$data['bdd'] = [
  'register' => boolval(htmlentities($_POST['register_bdd'])),
  'driver'   => htmlentities($_POST['driver_bdd']),
  'charset'  => htmlentities($_POST['charset_bdd']),
  'username' => htmlentities($_POST['username_bdd']),
  'password' => htmlentities($_POST['password_bdd']),
  'host'     => htmlentities($_POST['host_bdd']),
  'port'     => htmlentities($_POST['port_bdd']),
  'name'     => htmlentities($_POST['name_bdd']),
  'table'    => htmlentities($_POST['name_table']),
];
/**
 *  Si l'option est selectionnée,
 **/
if (isset($_POST['register_bdd'])) {
  array_walk($data['bdd'], 'trim_value'); //on supprimes les espaces
  $valid = is_BDD($data['bdd']); //on vérifie la validité des champs
}

//on attribut les valeurs dans un tableau
$data['mail'] = [
  'register' => boolval($_POST['register_mail']),
  'username' => htmlentities($_POST['username_mail']),
];
//si l'option "mail fournit dans le formulaire" est séléctionnée
if (isset($_POST['to_form_mail'])) $data['mail']['to_form'] = boolval(htmlentities($_POST['to_form_mail']));
// si l'option "Envoi au(x) mail(s) fourni. " est selectionner
if (isset($_POST['to_provided_mail'])) {
  $data['mail']['to_provided_mail'] = boolval(htmlentities($_POST['to_provided_mail']));
  if (!empty($_POST['to_mail'])) {
    $data['mail']['mail_provided'] = htmlentities($_POST['to_mail']);
  } else {
    $_SESSION[$form_id]['message_error_mail']['to_mail'] =
      'Si vous souhaitez envoyer le mail à une adresse définit, entrez une adresse valide';
  }
}
//si l'option "envoi de mail" est selectionnée
if (isset($_POST['register_mail'])) {
  array_walk($data['mail'], 'trim_value');
  $valid = is_Mail($data['mail']);
}

//attribution des valeurs dans un tableau
$data['field'] = [
  'mail'          => $_POST['field_mail'],
  'civilite'      => $_POST['field_civilite'],
  'nom'           => $_POST['field_nom'],
  'prenom'        => $_POST['field_prenom'],
  'numeroAdresse' => $_POST['field_numeroAdresse'],
  'voieAdresse'   => $_POST['field_voieAdresse'],
  'codePostal'    => $_POST['field_codePostal'],
  'ville'         => $_POST['field_ville'],
  'telephone'     => $_POST['field_telephone'],
  'entreprise'    => $_POST['field_entreprise'],
  'message'       => $_POST['field_message'],
  'opt_in'        => $_POST['field_optionnel'],
];
array_walk($data['field'], 'trim_value');
$valid = is_Field($data['field'], $data['mail']['to_form']);//fonction de vérification
$valid = is_Field($data['field'], $data['mail']['to_form']);//fonction de vérification

if (!$valid || isset($_SESSION[$form_id]['message_error_bdd'])
  || isset($_SESSION[$form_id]['message_error_mail'])
  || isset($_SESSION[$form_id]['message_error_field'])
  || isset($_SESSION[$form_id]['message_error_connection'])
) {
  if (isset($_POST['register_bdd'])) {
    if (isset($data['bdd'])) $_SESSION[$form_id]['field_bdd'] = $data['bdd'];
  }
  if (isset($_POST['register_mail'])) {
    if (isset($data['mail'])) $_SESSION[$form_id]['field_mail'] = $data['mail'];
  }
  if (isset($data['field'])) $_SESSION[$form_id]['field_field'] = $data['field'];
  $_SESSION[$form_id]['field_bdd']['register_bdd'] = htmlentities($_POST['register_bdd']);
  $_SESSION[$form_id]['field_mail']['register_mail'] = htmlentities($_POST['register_mail']);
  header('Location:' . $dir . 'generate.php');
} else {
  generate_prod($data);
//  si l'option "enregistrement en base de données" est selectionnée
  if (($data['bdd']['register'])) {
    $test = generate_landing_entity($data['field'], $data['bdd']['table']);//fonction d'écriture de l'entité
    $test = generate_database($data['bdd']); //fonction de création de la base de données
    if ($test) {
      exec('php vendor/bin/doctrine orm:schema-tool:create');
      shell_exec('php vendor/bin/doctrine orm:generate-entities --filter=Entity src/ 2>&1');
    }
  }
  header('Location:' . $dir);
}