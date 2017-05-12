<?php
/**
 * Created by PhpStorm.
 * User: rrisser
 * Date: 11/05/17
 * Time: 11:27
 *
 * @param $value
 */

//Supprime les espaces et les caractère indésirables.
function trim_value(&$value){
  $value=trim($value);
  $value=str_replace(' ','',$value);
  $value = preg_replace('/\s+/', '', $value);
}