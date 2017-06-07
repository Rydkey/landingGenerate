<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 06/04/17
 * Time: 14:39
 */

// inclus la définition de la base de données.
require __DIR__.'/prod.php';

//débug de silex, ne pas toucher pour le dev
$app['debug'] = true;

//permet de récupéré l'URL de base de l'appliquation, ne pas toucher
define('BASE_URL', explode('app.php', $_SERVER['SCRIPT_NAME'])[0]);

define('CONFIG',$config);//contient les booléens définissant si il à ou non enregistrement en base ou non
define('HEADER',$headers); //en-tête du mail
define('MAIL_BOOL',$mail_bool);//Contient les bouléens définissant à qui envoyé le mail
define('MAIL_TO',$mail_to);// contient l'adresse mail fourni si elle existe
define('BDD',$db_config);//informations de la BDD si il y à enregistrement
define('BOOL',$f_bool);//booléens des champs du formulaire
define('NAME',$f_name);//noms des champs du formulaires
