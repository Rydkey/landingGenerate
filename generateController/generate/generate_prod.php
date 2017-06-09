<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 10/05/17
 * Time: 11:16
 */

$prod='';

function add_bdd($bdd){
  global $prod;
  $prod.= '
$db_config=[
  \'driver\'=> \'pdo_'.$bdd['driver'].'\',
  \'charset\'=> \''.$bdd['charset'].'\',
  \'host\'=> \''.$bdd['host'].'\',
  \'port\'=> \''.$bdd['port'].'\',
  \'dbname\'=> \''.$bdd['name'].'\',
  \'user\'=> \''.$bdd['username'].'\',
  \'password\'=> \''.$bdd['password'].'\',
];
  ';
}

function add_mail($mail){
  global $prod;
  $prod.='
$mail_bool=[
  \'form\'=>\''.$mail['to_form'].'\',
  \'provided\'=>\''.$mail['to_provided_mail'].'\',
];
  ';
  $prod.= '$mail_config[\'display-mail\'] = \''.$mail['username'].'\';';
  if(($mail['to_provided_mail']))$prod.='
  $mail_to[]= \''.$mail['mail_provided'].'\';
  ';
}

function add_field($field){
  global $prod;
  foreach($field as $key => $v){
    $prod.='
$f_bool[\''.$key.'\']=\''.boolval($v).'\';
    ';
    if (!empty($v)){
      $prod.='
$f_name[\''.$key.'\']=\''.$v.'\';
    ';
    }
  }
}

function add_required($bdd,$mail){
  global $prod;
  $prod.='
$config=[
  \'db_register\'=>\''.$bdd.'\',
  \'mail_send\'=>\''.$mail.'\',
];
  ';
  $prod.='
$headers  = \'From: \'.$mail_config[\'display-mail\'].", \r\n";
$headers .= \'Content-type: text/html; charset=utf-8\' . "\r\n";
$app[\'orm.proxies_dir\'] = __DIR__.\'/../cache/doctrine/proxies\';
$app[\'orm.default_cache\'] = \'array\';
$app[\'orm.em.options\'] = array(
    \'mappings\' => array(
        array(
            \'type\' => \'annotation\',
            \'path\' => __DIR__.\'/../../src\',
            \'namespace\' => \'landingBundle\Entity\',
        ),
    ),
);
  ';
}

/**
 * @param array $data
 *
 * @return bool
 */
function generate_prod(array $data){
  global $prod;
  if($data['bdd']['register']) add_bdd($data['bdd']);
  if($data['mail']['register']) add_mail($data['mail']);
  add_field($data['field']);
  add_required($data['bdd']['register'],$data['mail']['register']);
  $file= __DIR__."/../../app/config/prod.php";
  $content=file_get_contents($file);
  $content = str_replace('//ecrire_ici',$prod,$content);
  file_put_contents($file,$content);
}